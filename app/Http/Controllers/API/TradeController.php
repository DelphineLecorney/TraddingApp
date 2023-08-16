<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Trade;
use App\Models\Profile;

class TradeController extends Controller
{

    public function fetchPriceFromApi($symbol)
    {
        $response = Http::withHeaders([
            'X-Rapidapi-Key' => '0a5012542cmsha2cbc9ec2f5dc58p10173cjsn113632b4ef8d',
            'X-Rapidapi-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com'
        ])->get("https://apidojo-yahoo-finance-v1.p.rapidapi.com/auto-complete?q={$symbol}&region=US");

        $data = $response->json();

        if (isset($data['price'])) {
            return $data['price'];
        }

        return 0;
    }



    public function openTrade(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $currentPrice = $this->fetchPriceFromAPi($request->symbol);

        $totalCost = $currentPrice * $request->quantity;
        $user = Auth::user();
        if($user->balance < $totalCost) {
            return response()->json(['message' => 'Not enough money for buying']);
        }
        $user = Auth::user();
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
    
}

    public function indexOpenTrades()
    {
        $user = Auth::user();
        $openTrades = Trade::where('profile_id', $user->profile->id)
                            ->where('open', true)
                            ->get();

        return response()->json([
            'open_trades' =>$openTrades,
        ]);
    }

    public function closeTrade(Request $request)
    {
        $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $trade = Trade::where('profile_id', $request->profile_id)
                        ->where('open', true)
                        ->firstOrFail();

        $currentPrice = fetchPriceFromPrice($trade->symbol);

        $res = ($currentPrice - $trade->open_price) * $trade->quantity;

        $profile = Auth::user()->profile;
        
        $closeTrade = Trade::create([
            'profile_id' => $trade->profile_id,
            'symbol' => $trade->symbol,
            'quantity' => $trade->quantity,
            'open_price' => $trade->open_price,
            'close_price' => 119449,
            'open_datetime' => $trade->open_datetime,
            'close_datetime' =>now()->addMonth(1),
            'open' => false,
        ]);

        $user = Auth::user();
        $user->balance += $res;
        $user->save();

        return response()->json([
            'message' => 'Trade closed successfully',
            'result' => $res,
        ]);
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
        //
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
