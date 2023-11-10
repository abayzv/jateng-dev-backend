<?php

namespace App\Models;

use App\Services\DistanceCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $distanceCalculator;

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

    public function calculateDistance()
    {
        $calculator = new DistanceCalculator();
        $distance = $calculator->calculateDistance($this->latitude, $this->longitude, -6.914744, 107.609810, "K");
        // make distance to int
        return round($distance);
    }
}
