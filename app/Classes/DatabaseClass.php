<?php

namespace App\Classes;

class TableFieldClass
{
    public $name;
    public $type;
    public $length;
    public $nullable;
    public $default;
    public $foreign;

    public function __construct($name, $type, $length = null, $nullable = null, $default = null, $foreign = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->nullable = $nullable;
        $this->default = $default;
        $this->foreign = $foreign;
    }

    public function generate()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'length' => $this->length,
            'nullable' => $this->nullable,
            'default' => $this->default,
            'foreign' => $this->foreign,
        ];
    }
}

class TableClass extends TableFieldClass
{
    public $name;
    public $fields = [];

    public function __construct($name)
    {
        $this->name = $name;
        $this->addField('id', 'increments');
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function addField($name, $type, $length = null, $nullable = null, $default = null, $foreign = null)
    {
        $this->fields[] = new TableFieldClass($name, $type, $length, $nullable, $default, $foreign);
    }

    public function generateSchema()
    {
        $fields = [];
        foreach ($this->fields as $field) {
            $fields[] = $field->generate();
        }

        return [
            'name' => $this->name,
            'fields' => $fields,
        ];
    }
}

class DatabaseClass extends TableClass
{
    public $tables = [];

    public function construct($name)
    {
        $this->setName($name);
    }

    public function createMigration()
    {
        // generate migration file like laravel
        $migration = '<?php' . PHP_EOL . PHP_EOL;
        $migration .= 'use Illuminate\Database\Migrations\Migration;' . PHP_EOL;
        $migration .= 'use Illuminate\Database\Schema\Blueprint;' . PHP_EOL;
        $migration .= 'use Illuminate\Support\Facades\Schema;' . PHP_EOL . PHP_EOL;

        $migration .= 'class Create' . ucfirst($this->name) . 'Table extends Migration' . PHP_EOL;
        $migration .= '{' . PHP_EOL;
        $migration .= '    /**' . PHP_EOL;
        $migration .= '     * Run the migrations.' . PHP_EOL;
        $migration .= '     *' . PHP_EOL;
        $migration .= '     * @return void' . PHP_EOL;
        $migration .= '     */' . PHP_EOL;
        $migration .= '    public function up()' . PHP_EOL;
        $migration .= '    {' . PHP_EOL;
        $migration .= '        Schema::dropIfExists(\'' . $this->name . '\');' . PHP_EOL;
        $migration .= '        Schema::create(\'' . $this->name . '\', function (Blueprint $table) {' . PHP_EOL;
        foreach ($this->fields as $field) {
            if ($field->name === 'id') {
                if ($field->type === 'increments') {
                    $migration .= '            $table->id();' . PHP_EOL;
                } elseif ($field->type === 'bigIncrements') {
                    $migration .= '            $table->bigIncrements(\'id\');' . PHP_EOL;
                }
            } else {
                $migration .= '            $table->' . $field->type . '(\'' . $field->name . '\'';
                if ($field->length) {
                    $migration .= ', ' . $field->length . ')';
                }
                if (!$field->length) {
                    $migration .= ')';
                }
                if ($field->nullable) {
                    $migration .= ')->nullable()';
                }
                if ($field->default) {
                    $migration .= '->default(\'' . $field->default . '\')';
                }
                if ($field->foreign) {
                    $migration .= '->unsigned()';
                }
                $migration .= ';' . PHP_EOL;
            }
        }
        $migration .= '            $table->timestamps();' . PHP_EOL . PHP_EOL;
        $migration .= '        });' . PHP_EOL;
        $migration .= '    }' . PHP_EOL . PHP_EOL;
        $migration .= '    /**' . PHP_EOL;
        $migration .= '     * Reverse the migrations.' . PHP_EOL;
        $migration .= '     *' . PHP_EOL;
        $migration .= '     * @return void' . PHP_EOL;
        $migration .= '     */' . PHP_EOL;
        $migration .= '    public function down()' . PHP_EOL;
        $migration .= '    {' . PHP_EOL;
        $migration .= '        Schema::dropIfExists(\'' . $this->name . '\');' . PHP_EOL;
        $migration .= '    }' . PHP_EOL;
        $migration .= '}' . PHP_EOL;

        // create file migration in database/migrations
        $migrationFile = fopen(base_path('database/migrations/' . date('Y_m_d_His') . '_create_' . $this->name . '_table.php'), 'w');
        fwrite($migrationFile, $migration);
        fclose($migrationFile);
    }

    public function deleteMigration()
    {
        // create migration file to delete the table
        $migration = '<?php' . PHP_EOL . PHP_EOL;
        $migration .= 'use Illuminate\Database\Migrations\Migration;' . PHP_EOL;
        $migration .= 'use Illuminate\Database\Schema\Blueprint;' . PHP_EOL;
        $migration .= 'use Illuminate\Support\Facades\Schema;' . PHP_EOL . PHP_EOL;

        $migration .= 'class Delete' . ucfirst($this->name) . 'Table extends Migration' . PHP_EOL;
        $migration .= '{' . PHP_EOL;
        $migration .= '    /**' . PHP_EOL;
        $migration .= '     * Run the migrations.' . PHP_EOL;
        $migration .= '     *' . PHP_EOL;
        $migration .= '     * @return void' . PHP_EOL;
        $migration .= '     */' . PHP_EOL;
        $migration .= '    public function up()' . PHP_EOL;
        $migration .= '    {' . PHP_EOL;
        $migration .= '        Schema::dropIfExists(\'' . $this->name . '\');' . PHP_EOL;
        $migration .= '    }' . PHP_EOL . PHP_EOL;
        $migration .= '    /**' . PHP_EOL;
        $migration .= '     * Reverse the migrations.' . PHP_EOL;
        $migration .= '     *' . PHP_EOL;
        $migration .= '     * @return void' . PHP_EOL;
        $migration .= '     */' . PHP_EOL;
        $migration .= '    public function down()' . PHP_EOL;
        $migration .= '    {' . PHP_EOL;
        $migration .= '        Schema::create(\'' . $this->name . '\', function (Blueprint $table) {' . PHP_EOL;
        foreach ($this->fields as $field) {
            if ($field->name === 'id') {
                if ($field->type === 'increments') {
                    $migration .= '            $table->id();' . PHP_EOL;
                } elseif ($field->type === 'bigIncrements') {
                    $migration .= '            $table->bigIncrements(\'id\');' . PHP_EOL;
                }
            } else {
                $migration .= '            $table->' . $field->type . '(\'' . $field->name . '\'';
                if ($field->length) {
                    $migration .= ', ' . $field->length . ')';
                }
                if (!$field->length) {
                    $migration .= ')';
                }
                if ($field->nullable) {
                    $migration .= '->nullable()';
                }
                if ($field->default) {
                    $migration .= '->default(\'' . $field->default . '\')';
                }
                if ($field->foreign) {
                    $migration .= '->unsigned()';
                }
                $migration .= ';' . PHP_EOL;
            }
        }
        $migration .= '            $table->timestamps();' . PHP_EOL . PHP_EOL;
        $migration .= '        });' . PHP_EOL;
        $migration .= '    }' . PHP_EOL;
        $migration .= '}' . PHP_EOL;

        // create file migration in database/migrations
        $migrationFile = fopen(base_path('database/migrations/' . date('Y_m_d_His') . '_delete_' . $this->name . '_table.php'), 'w');
        fwrite($migrationFile, $migration);
        fclose($migrationFile);
    }

    public function createModel()
    {
        // generate model file like laravel
        $model = '<?php' . PHP_EOL . PHP_EOL;
        $model .= 'namespace App\Models;' . PHP_EOL . PHP_EOL;
        $model .= 'use Illuminate\Database\Eloquent\Factories\HasFactory;' . PHP_EOL;
        $model .= 'use Illuminate\Database\Eloquent\Model;' . PHP_EOL . PHP_EOL;
        $model .= 'class ' . ucfirst($this->name) . ' extends Model' . PHP_EOL;
        $model .= '{' . PHP_EOL;
        $model .= '    use HasFactory;' . PHP_EOL . PHP_EOL;
        $model .= '    protected $fillable = [' . PHP_EOL;
        foreach ($this->fields as $field) {
            $model .= '        \'' . $field->name . '\',' . PHP_EOL;
        }
        $model .= '    ];' . PHP_EOL;
        $model .= '}' . PHP_EOL;

        // create file model in app/Models
        $modelFile = fopen(base_path('app/Models/' . ucfirst($this->name) . '.php'), 'w');
        fwrite($modelFile, $model);
        fclose($modelFile);
    }
}
