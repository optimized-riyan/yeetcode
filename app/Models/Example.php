<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Example extends Model
{
    use HasFactory;


    public function description(): BelongsTo {
        return $this->belongsTo(Description::class);
    }
}
