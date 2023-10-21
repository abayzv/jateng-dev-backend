<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Classes\DatabaseClass;
use Illuminate\Support\Facades\Artisan;
use App\Models\TableSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            "input" => "text",
            "type" => "string",
            "length" => 125,
        ],
        [
            "name" => "file",
            "input" => "file",
            "type" => "string",
        ]
    ];

    $isExist = TableSchema::where('table_name', 'categories')->get();

    if ($isExist->count() > 0) {
        return response()->json([
            'message' => 'Table already exist',
        ]);
    }

    try {
        $schema = new DatabaseClass('categories');
        DB::beginTransaction();
        foreach ($fields as $field) {
            $schema->addField($field['name'], $field['type'], $field['length'] ?? null, $field['nullable'] ?? null, $field['default'] ?? null, $field['foreign'] ?? null);

            TableSchema::create([
                'table_name' => $schema->name,
                'name' => $field['name'],
                'type' => $field['input'],
                'length' => $field['length'] ?? null,
                'nullable' => $field['nullable'] ?? null,
                'default' => $field['default'] ?? null,
                'foreign' => $field['foreign'] ?? null,
            ]);
        }
        DB::commit();
        $schema->createMigration();
        $schema->createModel();
        Artisan::call('migrate');

        return response()->json([
            'message' => 'success',
        ]);
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        DB::rollBack();
        return response()->json([
            'message' => 'failed',
        ]);
    }
});

Route::get('/schema/{name}', function () {
    $name = request()->route('name');
    $table = TableSchema::where('table_name', $name)->get();
    return response()->json([
        'message' => 'success',
        'data' => $table,
    ]);
});

Route::get('/v2/{name}', function () {
    $name = request()->route('name');
    $model = ucfirst($name);
    $model = "App\\Models\\{$model}";
    $model = new $model;
    $model = $model->all();
    return response()->json([
        'message' => 'success',
        'data' => $model,
    ]);
});

Route::post('/v2/{name}', function () {
    $name = request()->route('name');
    $model = ucfirst($name);
    $model = "App\\Models\\{$model}";
    $model = new $model;

    $schema = TableSchema::where('table_name', $name)->get();
    $validator = createValidator($schema);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'failed',
            'errors' => $validator->errors(),
        ]);
    }

    $model = $model->create(request()->all());
    return response()->json([
        'message' => 'success',
        'data' => $model,
    ]);
});
