<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Credit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function wibor(): BelongsTo
    {
        return $this->belongsTo(Wibor::class);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d-m-Y / H:i');
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::slug($value)
        );
    }
}
