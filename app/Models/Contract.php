<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'code', 'start_date', 'status', 'direction_id', 'service_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            // Obtener el último ID insertado
            $lastId = self::max('id') ?? 0;
            $nextId = $lastId + 1;

            // Generar el código tipo C-0001, C-0002, etc.
            $contract->code = 'C-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    public function debts()
    {
        return $this->hasMany(Debt::class, 'contract_id');
    }

    public function additionalDebts()
    {
        return $this->hasMany(AdditionalDebt::class, 'contract_id');
    }

    public function totalDebts()
    {
        $debtsAmount = $this->debts()->sum('amount');
        $additionalDebtsAmount = $this->additionalDebts()->sum('original_amount') - $this->additionalDebts()->sum('amount_payed');

        // return $debtsAmount + $additionalDebtsAmount;
        return [
            'debtsAmount' => $debtsAmount,
            'additionalDebtsAmount' => $additionalDebtsAmount,
            'total' => $debtsAmount + $additionalDebtsAmount
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}