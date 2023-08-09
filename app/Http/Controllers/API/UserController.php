<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
