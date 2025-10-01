<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'charge_period',
        'late_fee',
    ];

    public function lateFee()
    {
        return $this->hasOne(LateFee::class, 'service_id');
    }

    // protected $casts = [
    //     'end_date' => 'date:d-m-Y',
    // ];
}