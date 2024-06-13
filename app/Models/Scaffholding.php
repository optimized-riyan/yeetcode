<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scaffholding extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "language_id",
        "scaffholding",
        "problem_id"
    ];

    public function problem(): BelongsTo {
        return $this->belongsTo(Problem::class);
    }
}
