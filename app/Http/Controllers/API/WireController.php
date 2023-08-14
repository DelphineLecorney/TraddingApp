<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wire;

class WireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createWire(Request $request)
{
    $request->validate([
        'profile_id' => 'required|integer',
        'amount' => 'required|integer',
        'withdrawal' => 'required|boolean',
    ]);

    $profileId = $request->profile_id;

    $wire = Wire::create([
        'profile_id' => $profileId,
        'amount' => $request->amount,
        'withdrawal' => $request->withdrawal,
    ]);

    return response()->json([
        'message' => 'Your wire is created successfully',
        'wire' => $wire,
    ]);
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
