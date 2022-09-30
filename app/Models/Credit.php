<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends Model
{
    use HasFactory;

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
