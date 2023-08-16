<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wire;
use App\Models\Profile;

class WireController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $wires = Wire::all();
        $response = [
            'success' => true,
            'message' => 'List of wires retrieved successfully',
            'data' => $wires,
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
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

        $message = $request->withdrawal ? 'Your withdrawal is processed successfully' : 'Your deposit is processed successfully';

        return response()->json([
            'message' => 'Your wire is created successfully',
            'wire' => $wire,
        ], 201);
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
        $wire = Wire::find($id);

        if (!$wire) {
            return response()->json([
                'status' => 404,
                'message' => 'Wire not found',
                'data' => null
            ], 404, [], JSON_PRETTY_PRINT);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Wire retrieved successfully',
            'data' => $wire
        ], 200, [], JSON_PRETTY_PRINT);
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
