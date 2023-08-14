<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Trade;
use App\Models\Wire;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if(!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
            ]);
    }

    public function signup(Request $request)
    {
        // \Log::info('Received signup request: ' . json_encode($request->all()));

        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        $openTrade = Trade::create([
            'profile_id' => $profile->id,
            'symbol' => 'TSLA',
            'quantity' => 123,
            'open_price' => 103059,
            'close_price' => null,
            'open_datetime' => now(),
            'close_datetime' => null,
            'open' => true,
        ]);

        $closeTrade = Trade::create([
            'profile_id' => $profile->id,
            'symbol' => 'TSLA',
            'quantity' => 123,
            'open_price' => 103059,
            'close_price' => 119449,
            'open_datetime' => $openTrade->open_datetime,
            'close_datetime' => now()->addMonth(1),
            'open' => false,
        ]);
            

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'profile' => $profile,
            'token' => $token,
        ], 201);
    }


    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
            ]);
    }

}
