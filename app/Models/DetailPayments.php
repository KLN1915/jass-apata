<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'amount_payed', 'payment_id', 'debt_id', 'additional_debt_id', 'additional_service_id'
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'debt_id');
    }

    public function additionalDebt()
    {
        return $this->belongsTo(AdditionalDebt::class, 'additional_debt_id');
    }

    public function additionalService()
    {
        return $this->belongsTo(AdditionalService::class, 'additional_service_id');
    }
}