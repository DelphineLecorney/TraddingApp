<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TradeController;
use App\Http\Controllers\API\WireController;
use App\Http\Controllers\API\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
});

Route::prefix('trades')->group(function () {
    Route::get('/', [TradeController::class, 'index']);
    Route::get('/{id}', [TradeController::class, 'show']);
    Route::post('openTrade', [TradeController::class, 'openTrade']);
    Route::post('/closeTrade/{id}', [TradeController::class, 'closeTrade']);
    Route::get('/index/open', [TradeController::class, 'indexOpenTrades']);
    Route::get('/index/closed', [TradeController::class, 'indexCloseTrades']);
    Route::get('/openPNL', [TradeController::class, 'getOpenPNL']);
    Route::get('/closedPNL', [TradeController::class, 'getClosedPNL']);
});

Route::prefix('wires')->group(function() {
    Route::get('/', [WireController::class, 'index']);
    Route::get('/{id}', [WireController::class, 'show']);
    Route::post('createWire', [WireController::class, 'createWire']);
});

Route::prefix('profiles')->group(function() {
    Route::get('/', [ProfileController::class, 'index']);
    Route::get('/{id}', [ProfileController::class, 'show']);
    Route::put('/{id}', [ProfileController::class, 'update'])->middleware('auth:api');
});