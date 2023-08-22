<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $users = User::all();
            $response = [
                'success' => true,
                'message' => 'List of users retrieved successfully',
                'data' => $users,
            ];

            return response()->json($response, 200, [], JSON_PRETTY_PRINT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Trade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('testViews', ['user' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Trade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
