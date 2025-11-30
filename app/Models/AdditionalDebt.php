<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalDebt extends Model
{
    use HasFactory;

    protected $fillable = ['concept', 'amount_payed', 'original_amount', 'payed', 'contract_id'];
}
