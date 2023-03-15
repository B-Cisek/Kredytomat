<?php

namespace App\Models;

use App\Enums\WiborType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wibor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => WiborType::class
    ];

    public $timestamps = true;


    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function creditSimulations(): HasMany
    {
        return $this->hasMany(CreditSimulation::class);
    }

    public function overpaymentSimulations(): HasMany
    {
        return $this->hasMany(OverpaymentSimulation::class);
    }
}
