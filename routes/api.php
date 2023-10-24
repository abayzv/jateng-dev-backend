<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CrudController;

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

Route::prefix('v1/auth')->group(function () {
    Route::post('admin/login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh-token', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::prefix('v1')->group(function () {
    Route::get('/crud', [CrudController::class, 'list']);
    Route::get('/crud/{name}', [CrudController::class, 'show']);
    Route::post('/crud/generate', [CrudController::class, 'generate']);
    Route::get('/crud/schema/{name}', [CrudController::class, 'schema']);
    Route::delete('/crud/{name}', [CrudController::class, 'deleteSchema']);
    Route::get('{name}', [CrudController::class, 'index']);
    Route::post('{name}', [CrudController::class, 'store']);
});
