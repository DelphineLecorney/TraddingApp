<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Trade;
use App\Models\Profile;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TradeController extends Controller
{
    public function fetchPriceFromApi($symbol)
    {
        $response = Http::withHeaders([
            'X-Rapidapi-Key' => '0a5012542cmsha2cbc9ec2f5dc58p10173cjsn113632b4ef8d',
            'X-Rapidapi-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com'
        ])->get("https://apidojo-yahoo-finance-v1.p.rapidapi.com/auto-complete?q={$symbol}&region=US");

        $data = $response->json();

        if (isset($data['quoteResponse']['result'][0]['regularMarketPrice']['raw'])) {
            return $data['quoteResponse']['result'][0]['regularMarketPrice']['raw'];
        }

        return 0;
    }


    public function openTrade(Request $request)
    {
        try {
            $request->validate([
                'symbol' => 'required|string',
                'quantity' => 'required|integer|min:1',
            ]);

            $symbol = $request->input('symbol');
            $currentPrice = $this->fetchPriceFromApi($symbol);
            $totalCost = $currentPrice * $request->input('quantity');

            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            if (!Auth::user()->can('openTrade')) {
                return response()->json(['message' => 'Permission denied'], 403);
            }

            $user = Auth::user();
            if ($user->balance < $totalCost) {
                return response()->json(['message' => 'Not enough money for buying']);
            }

            $profile = $user->profile;

            $openTrade = Trade::create([
                'profile_id' => $profile,
                'symbol' => $request->symbol,
                'quantity' => $request->quantity,
                'open_price' => null,
                'open_datetime' => now(),
                'close_datetime' => null,
                'open' => true,
            ]);

            $user->balance -= $totalCost;
            $user->save();

            return response()->json(['message' => "The trade is open successfully"]);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    public function closeTrade(Request $request)
    {
        try {
            $request->validate([
                'profile_id' => 'required|exists:profiles,id',
            ]);

            $trade = Trade::where('profile_id', $request->input('profile_id'))
            ->where('open', true)
            ->firstOrFail();

            $currentPrice = $this->fetchPriceFromApi($trade->symbol);

            $res = ($currentPrice - $trade->open_price) * $trade->quantity;

            $profile = Auth::user()->profile;

            $closeTrade = Trade::create([
            'profile_id' => $trade->profile_id,
            'symbol' => $trade->symbol,
            'quantity' => $trade->quantity,
            'open_price' => $trade->open_price,
            'close_price' => 119449,
            'open_datetime' => $trade->open_datetime,
            'close_datetime' => now()->addMonth(1),
            'open' => false,
        ]);

            $user = Auth::user();
            $user->balance += $res;
            $user->save();

            return response()->json([
                'message' => 'Trade closed successfully',
                'result' => $res,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Trade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }


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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {

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
