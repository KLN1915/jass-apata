<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['occupation_id'];

    protected $casts = [
        'datebirth' => 'date:d-m-Y',
    ];

    public function directions()
    {
        return $this->hasMany(Direction::class, 'client_id');
    }

    public function currentTitular()
    {
        return $this->hasOne(HistoryTitular::class, 'client_id')->where('is_current', true);
    }

    public function otherTitulars()
    {
        return $this->hasMany(HistoryTitular::class, 'client_id')->where('is_current', false);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class, 'occupation_id');
    }
}
