<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Classes\DatabaseClass;
use Illuminate\Support\Facades\Artisan;

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
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// create test route
Route::get('test', function () {
    $fields = [
        [
            "name" => "name",
            "type" => "string",
            "length" => 125,
        ],
        [
            "name" => "file",
            "type" => "string",
        ]
    ];

    $schema = new DatabaseClass('categories');

    foreach ($fields as $field) {
        $schema->addField($field['name'], $field['type'], $field['length'] ?? null, $field['nullable'] ?? null, $field['default'] ?? null, $field['foreign'] ?? null);
    }

    $schema->createMigration();
    Artisan::call('migrate');

    return response()->json([
        'message' => 'success',
    ]);
});
