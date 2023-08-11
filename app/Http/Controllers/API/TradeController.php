<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Controllers\Auth;
use App\Models\Trade;

class TradeController extends Controller
{
    public function openTrade(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $currentPrice = fetchPriceFromAPi($request->symbol);
        $totalCost = $currentPrice * $request->quantity;
        $user = Auth::user();
        if($user->balance < $totalCost) {
            return response()->json(['message' => 'Not enough money for buying']);
        }

        $openTrade = Trade::create([
            'profile_id' => $profile->id,
            'symbol' => 'TSLA',
            'quantity' => 103059,
            'open_price' => null,
            'open_datetime' => now(),
            'close_datetime' =>null,
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

        $totalTrades = $currentPrice * $trade->quantity;

        $res = ($currentPrice - $trade->open_price) * $trade->quantity;

        $trade->close_price = $currentPrice;
        $trade->close_datetime = now();
        $trade->open = false;
        $trade->save();

        $user = Auth::user();
        $user->balance += $res;
        $user-> save();
        
        $closeTrade = Trade::create([
            'profile_id' => $profile->id,
            'symbol' => 'TSLA',
            'quantity' => 123,
            'open_price' => 103059,
            'close_price' => 119449,
            'open_datetime' => now(),
            'close_datetime' =>now()->addMonth(1),
            'open' => false,
        ]);

        return response()->json([
            'message' => 'Trade close successfully',
            'Résultat de la transaction' => $res,
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
