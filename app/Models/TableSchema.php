<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSchema extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_name',
        'name',
        'type',
        'length',
        'nullable',
        'default',
        'foreign',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
