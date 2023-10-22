<?php

namespace App\Http\Controllers\API;

use App\Classes\DatabaseClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TableSchema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    /**
     * Generate Table Schema
     */

    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.input' => 'required|string',
            'fields.*.length' => 'nullable|integer',
            'fields.*.nullable' => 'nullable|boolean',
            'fields.*.default' => 'nullable|string',
            'fields.*.foreign' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $name = $request->name;
        $fields = $request->fields;

        $isExist = TableSchema::where('table_name', $name)->get();

        if ($isExist->count() > 0) {
            return response()->json([
                'message' => 'Table already exist',
            ]);
        }

        try {
            $schema = new DatabaseClass($name);
            DB::beginTransaction();
            foreach ($fields as $field) {
                $schema->addField($field['name'], $field['type'], $field['length'] ?? null, $field['nullable'] ?? null, $field['default'] ?? null, $field['foreign'] ?? null);

                TableSchema::create([
                    'table_name' => $schema->name,
                    'name' => $field['name'],
                    'type' => $field['input'],
                    'length' => $field['length'] ?? null,
                    'nullable' => $field['nullable'] ?? false,
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
            if ($table->count() == 0) {
                return response()->json([
                    'message' => 'Table not found',
                ]);
            }

            $migration = database_path("migrations/*_create_{$name}_table.php");
            $migration = glob($migration);
            if (count($migration) > 0) {
                unlink($migration[0]);
            }

            $model = app_path("Models/{$name}.php");
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

            $table->each->delete();
            return response()->json([
                'message' => 'success',
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'failed',
            ]);
        }
    }

    /**
     * Get Table Data
     */
    public function index(string $name)
    {
        try {
            // check tableschema
            $table = TableSchema::where('table_name', $name)->get();

            if ($table->count() == 0) {
                return response()->json([
                    'message' => 'Crud not found',
                ]);
            }

            $model = ucfirst($name);
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
            ]);
        }
    }

    /**
     * Create Table Data
     */
    public function store(string $name)
    {
        try {
            $schema = TableSchema::where('table_name', $name)->get();

            if ($schema->count() == 0) {
                return response()->json([
                    'message' => 'Crud not found',
                ]);
            }

            $model = ucfirst($name);
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
            $model->create($data);
            DB::commit();

            return response()->json([
                'message' => 'success',
                'data' => $data,
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
}
