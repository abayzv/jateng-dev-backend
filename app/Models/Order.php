<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'shipping_number',
        'shipping_company',
        'shipping_status',
        'voucher_id',
    ];
}
