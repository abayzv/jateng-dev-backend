<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::post('admin/login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh-token', [AuthController::class, 'refresh'])->middleware('auth:api');
});

// Test Route
Route::get('test', function () {
    return response()->json([
        'message' => generateUuid()
    ]);
});
