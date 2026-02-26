<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'end_date',
        'service_id',
    ];

    protected $casts = [
        'end_date' => 'date:Y-m-d',
    ];
}
