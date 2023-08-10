<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function openTrade(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);
    
    $currentPrice = fetchPriceFromAPi($request->symbol);
    $totalCost = $currentPrice * $reqeuest->quantity;
    $user = Auth::user();
    if($user->balance < $totalCost){
        return response()->json(['message' => 'Not enough for buying']);
    }

    $trade = Trade::create([
        'symbol' => $request->symbol,
        'quantity' => $request->quantity,
        'open_price' => $currentPrice,
        'open_datetime' => now(),
        'open' => true,
    ]);

    $user->balance -= $totalCost;
    $user->save();

    return response()->json(['message' => "It's open successfully"]);
    }
    
    function closeTrade(Request $request)
    {
        $request->validate([
            'profile_id' => 'required|exists:profiles, id',
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

        return response()->json([
            'message' => 'Trade close successfully',
            'Résultat de la transaction' => $res,
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
