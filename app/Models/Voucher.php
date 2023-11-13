<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'type',
        'value',
        'percentage',
        'max_value',
        'min_value',
        'usage_limit',
        'usage_per_user',
        'starts_at',
        'expires_at',
    ];
}
