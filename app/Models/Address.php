<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "address_name",
        "recipient_name",
        "recipient_phone_number",
        "address_line_1",
        "address_line_2",
        "city",
        "state",
        "zip_code",
        "country",
        "is_primary",
        "latitude",
        "longitude",
    ];
}
