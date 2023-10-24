<?php

namespace App\Http\Controllers\API;

use App\Classes\DatabaseClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CrudVisibility;
use App\Models\TableSchema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    public function __construct()
    {
        $rules = ['generate', 'schema', 'deleteSchema', 'list', 'show'];
        $params = request()->route() ? request()->route()->parameters : [];
        $visibility = CrudVisibility::where('name', $params['name'] ?? null)->get();
        foreach ($visibility as $vis) {
            if ($vis->visibility == 'public') {
                $rules[] = $vis->action;
            }
        }

        $this->middleware('jwt.verify')->except($rules);
    }

    /**
     * Generate Table Schema
     */

    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'model_name' => 'required|string',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.input' => 'required|string',
            'fields.*.length' => 'nullable|integer',
            'fields.*.nullable' => 'nullable|boolean',
            'fields.*.default' => 'nullable|string',
            'fields.*.foreign' => 'nullable|string',
            'visibility' => 'nullable|array',
            'visibility.index' => 'required_with:visibility|string',
            'visibility.store' => 'required_with:visibility|string',
            'visibility.show' => 'required_with:visibility|string',
            'visibility.update' => 'required_with:visibility|string',
            'visibility.destroy' => 'required_with:visibility|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $name = $request->name;
        $model_name = $request->model_name;
        $fields = $request->fields;
        $visibility = $request->visibility;

        $isExist = TableSchema::where('table_name', $name)->get();

        if ($isExist->count() > 0) {
            return response()->json([
                'message' => 'Table already exist',
            ]);
        }

        try {
            $schema = new DatabaseClass($name);
            $schema->setModelName($model_name);
            DB::beginTransaction();
            foreach ($fields as $field) {
                $schema->addField($field['name'], $field['type'], $field['length'] ?? null, $field['nullable'] ?? null, $field['default'] ?? null, $field['foreign'] ?? null);

                TableSchema::create([
                    'table_name' => $schema->name,
                    'model_name' => $schema->model_name,
                    'name' => $field['name'],
                    'type' => $field['input'],
                    'length' => $field['length'] ?? null,
                    'nullable' => $field['nullable'] ?? false,
                    'default' => $field['default'] ?? null,
                    'foreign' => $field['foreign'] ?? null,
                ]);
            }

            $action = ['index', 'store', 'show', 'update', 'destroy'];
            foreach ($action as $act) {
                CrudVisibility::create([
                    'name' => $schema->name,
                    'action' => $act,
                    'visibility' => $visibility[$act] ?? 'public',
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
    }

    /**
     * Get Table Schema
     */
    public  function schema(string $name)
    {
        try {
            $table = TableSchema::where('table_name', $name)->get();

            if ($table->count() == 0) {
                return response()->json([
                    'message' => 'Table not found',
                ]);
            }

            return response()->json([
                'message' => 'success',
                'data' => $table,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'failed',
            ]);
        }
    }

    /**
     * Delete Table Schema
     */

    public function deleteSchema(string $name)
    {
        try {
            $table = TableSchema::where('table_name', $name)->get();
            $model_name = $table->first()->model_name;

            if ($table->count() == 0) {
                return response()->json([
                    'message' => 'Table not found',
                ], 404);
            }

            DB::beginTransaction();
            $table->each->delete();
            $visibility = CrudVisibility::where('name', $name)->get();
            $visibility->each->delete();
            DB::commit();

            $migration = database_path("migrations/*_create_{$name}_table.php");
            $migration = glob($migration);
            if (count($migration) > 0) {
                unlink($migration[0]);
            }

            $model = app_path("Models/{$model_name}.php");
            if (file_exists($model)) {
                unlink($model);
            }

            $database = new DatabaseClass($name);
            $database->deleteMigration();

            Artisan::call('migrate');

            $migration = database_path("migrations/*_delete_{$name}_table.php");
            $migration = glob($migration);
            if (count($migration) > 0) {
                unlink($migration[0]);
            }

            return response()->json([
                'message' => 'success',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'failed',
            ], 400);
        }
    }

    /**
     * Get Table Data
     */
    public function index(string $name)
    {
        try {
            // check tableschema
            $table = TableSchema::where('table_name', $name)->first();

            if ($table->count() == 0) {
                return response()->json([
                    'message' => 'Crud not found',
                ], 404);
            }

            $model = ucfirst($table->model_name);
            $model = "App\\Models\\{$model}";
            $model = new $model;
            $model = $model->all();
            return response()->json([
                'message' => 'success',
                'data' => $model,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'failed',
            ], 400);
        }
    }

    /**
     * Create Table Data
     */
    public function store(string $name)
    {
        try {
            $schema = TableSchema::where('table_name', $name)->get();
            $model_name = $schema->first()->model_name;

            if ($schema->count() == 0) {
                return response()->json([
                    'message' => 'Crud not found',
                ]);
            }

            $model = ucfirst($model_name);
            $model = "App\\Models\\{$model}";
            $model = new $model;

            $rules = createValidator($schema);
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $onlyRequest = [];
            foreach ($schema as $field) {
                $onlyRequest[] = $field->name;
            }

            $data = request()->only($onlyRequest);

            foreach ($rules as $key => $rule) {
                if (strpos($rule, 'file') !== false) {
                    $url = uploadImage(request()->file($key), $name);
                    $data[$key] = $url;
                }
            }

            DB::beginTransaction();
            $response = $model->create($data);
            DB::commit();

            return response()->json([
                'message' => 'success',
                'data' => $response,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            foreach ($rules as $key => $rule) {
                if (strpos($rule, 'file') !== false) {
                    deleteImage($data[$key]);
                }
            }
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'failed',
            ]);
        }
    }

    /**
     * Get Crud List
     */
    public function list()
    {
        try {
            $table = TableSchema::all()->pluck('table_name');

            return response()->json([
                "status" => true,
                'message' => 'success',
                'data' => $table,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                "status" => false,
                'message' => 'failed',
            ], 400);
        }
    }

    /**
     * Get Table Data
     */
    public function show(string $name)
    {
        try {
            $table = TableSchema::all()->where('table_name', $name)
                ->groupBy('table_name')
                ->first();

            return response()->json([
                "status" => true,
                'message' => 'success',
                'data' => $table,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                "status" => false,
                'message' => 'failed',
            ], 400);
        }
    }
}
