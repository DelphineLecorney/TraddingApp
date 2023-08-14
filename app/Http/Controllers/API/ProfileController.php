<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function index()
    {
        $profiles = Profile::all();
        $response = [
            'success' => true,
            'message' => 'List of profiles retrieved successfully',
            'data' => $profiles,
        ];

        return response()->json([
            'profiles' => $profiles
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
        $profile = Profile::find($id);

        if(!$profile){
            return response()-json([
                'status' => 404,
                'message' => 'Profile not found',
                'data' => null
            ], 404, [], JSON_PRETTY_PRINT);
        }

        $updatedData = $request->validate([
            'first_name'=> 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
        ]);

        $profile->update($updatedData);

        return response()->json([
            'status' => 200,
            'message' => 'The profile was successfully updated',
            'data' => $profile
        ], 200, [], JSON_PRETTY_PRINT);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
