<?php

namespace App\Http\Controllers\API;

use Illuminate\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function createProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'first_name'=> 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
        ]);

        $profile = Profile::create([
           'user_id' => $request->user_id,
           'first_name' => $request->first_name,
           'last_name' => $request->last_name,
           'address' => $request->address, 
        ]);

        return response()->json ([
            'message' => 'Your profile is created successfully',
            'profile' => $profile,
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
