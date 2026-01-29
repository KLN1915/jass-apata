<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'nulled', 'titular', 'total', 'user_id', 'contract_id',
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y H:i',
        'updated_at' => 'date:d-m-Y H:i',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}