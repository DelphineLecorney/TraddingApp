<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Trade;
use App\Models\Profile;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Function for retrieving the share price from an API
     */
    public function fetchPriceFromApi($symbol)
    {
        $response = Http::withHeaders([
            'X-Rapidapi-Key' => '0a5012542cmsha2cbc9ec2f5dc58p10173cjsn113632b4ef8d',
            'X-Rapidapi-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com'
        ])->get("https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes?region=US&symbols={$symbol}");

        $data = $response->json();

        if (isset($data['quoteResponse']['result'][0]['regularMarketPrice'])) {
            return $data['quoteResponse']['result'][0]['regularMarketPrice'];
        }
        return 0;
    }

    /**
     * Function for open a trade
     */
    public function openTrade(Request $request)
    {
        try {
            $request->validate([
                'symbol' => 'required|string',
                'quantity' => 'required|integer|min:1',
            ]);

            $symbol = $request->input('symbol');

            $currentPriceFloat = $this->fetchPriceFromApi($symbol);
            var_dump($currentPriceFloat); // Debug

            if (is_null($currentPriceFloat)) {
                return response()->json(['message' => 'Current price not available'], 400);
            }

            $currentPrice = intval($currentPriceFloat * 100);
            var_dump($currentPrice); // Debug

            $totalCost = $currentPrice * $request->input('quantity');

            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $profileController = new ProfileController();
            $response = $profileController->fetchProfileWithBalance();
            $profileData = $response->getData();

            $balance = $profileData->data->balance;


            echo 'balance : ', var_dump($profileData); // Debug

            if ($balance < $totalCost) {
                return response()->json(['message' => 'Not enough money for buying'], 400);
            }
            echo 'totalcost : ', var_dump($totalCost); // Debug

            $openTrade = Trade::create([
                'profile_id' => $user->profile->id,
                'symbol' => $request->symbol,
                'quantity' => $request->quantity,
                'open_price' => $currentPrice,
                'open_datetime' => now(),
                'close_datetime' => null,
                'open' => true,
            ]);

            $tradeId = $openTrade->id;
            $user = Auth::user();
            $profile = $user->profile;
            $profile->balance -= $totalCost;
            $profile->save();

            return response()->json([
                'message' => "The trade is open successfully",
                'trade' => $openTrade,
                'trade_id' => $tradeId,
            ]);


        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            \Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred'], 500);
        }

    }

    /**
    * Show the opened trades
    */
    public function indexOpenTrades()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $openTrades = Trade::where('profile_id', $user->profile->id)
                            ->where('open', true)
                            ->get();

        return response()->json([
            'open_trades' => $openTrades,
        ]);
    }

    /**
     * Function for close a trade
     */
    public function closeTrade($id)
{
    try {
        $trade = Trade::findOrFail($id);

        Log::info('Before update - Trade ID: ' . $id . ' - Trade open status: ' . $trade->open);
        Log::info('Trade ID: ' . $id);
        Log::info('Trade open status: ' . $trade->open);

        if ($trade->open === false) {
            return response()->json(['message' => 'Trade is already closed'], 400);
        }

        $currentPrice = $this->fetchPriceFromApi($trade->symbol);
        $currentPrice = intval($currentPrice * 100);

        if ($currentPrice < $trade->open_price) {
            return response()->json(['message' => 'Cannot sell below opening price'], 400);
        }

        $res = ($currentPrice - $trade->open_price) * $trade->quantity;

        $profile = Auth::user()->profile;

        $trade->update([
            'close_price' => $currentPrice,
            'close_datetime' => now()->addMonth(1),
            'open' => false,
        ]);

        $profile->balance += $res;
        $profile->save();

        $trade->refresh();

        Log::info('After update - Trade ID: ' . $id . ' - Trade open status: ' . $trade->open);

        return response()->json([
            'message' => 'Trade closed successfully',
            'result' => $res,
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Trade not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred'], 500);
    }
}




    /**
    * Show the closed trades
    */
    public function indexCloseTrades()
    {
        $user = Auth::user();
        $closedTrades = Trade::where('profile_id', $user->profile->id)
                              ->where('open', false)
                              ->get();

        return response()->json([
            'closed_trades' => $closedTrades,
        ]);
    }

    /**
     * Show the total open PNL (all open trades for an user)
     */
    public function getOpenPNL()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $openTrades = Trade::where('profile_id', $user->profile->id)
                       ->where('open', true)
                       ->get();

    $totalPNL = 0;

    foreach ($openTrades as $trade) {
        $pnl = ($trade->close_price - $trade->open_price) * $trade->quantity;
        $totalPNL += $pnl;
    }

    Log::info('open_price : ' . $trade->open_price . ' close_price : ' . $trade->close_price);
    Log::info('total : ' . $totalPNL);

    return response()->json([
        'open_trade_ids' => $openTrades->pluck('id'),
        'total_open_pnl' => $totalPNL,
    ]);
}


    public function getClosedPNL()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $closedTrades = Trade::where('profile_id', $user->profile->id)
                         ->where('open', false)
                         ->get();

    $totalPNL = 0;

    foreach ($closedTrades as $trade) {
        $pnl = ($trade->close_price - $trade->open_price) * $trade->quantity;
        $totalPNL += $pnl;
    }

    return response()->json([
        'closed_trades' => $closedTrades,
        'total_closed_pnl' => $totalPNL,
    ]);
}



    public function index()
    {
        $trades = Trade::all();
        $response = [
            'success' => true,
            'message' => 'List of trades retrieved successfully',
            'data' => $trades,
        ];

        return response()->json([
            'trades' => $trades
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $trade = Trade::findOrFail($id);

            return response()->json(['trade' => $trade], 200, [], JSON_PRETTY_PRINT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Trade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
