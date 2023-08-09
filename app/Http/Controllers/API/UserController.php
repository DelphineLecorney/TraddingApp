<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $response = [
            'success' => true,
            'message' => 'List of users retrieved successfully',
            'data' => $users,
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }


    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('testViews', ['user' => $user]);
    }
}
