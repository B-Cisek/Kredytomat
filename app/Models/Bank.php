<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'slug',
        'logo_path'
    ];

    public $timestamps = false;

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    protected function logoPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('storage/' . $value),
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::slug($value)
        );
    }
}
