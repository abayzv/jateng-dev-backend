<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudVisibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'action',
        'visibility',
    ];
}
