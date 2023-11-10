<?php

namespace App\Models\Contracts;

interface DistanceCalculator
{
    public function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit);
}
