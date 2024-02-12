<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hint extends Model
{
    use HasFactory;

    protected $primaryKey = 'problem_id';
    public $incrementing = false;
    public $timestamps = false;

    public function problem(): BelongsTo {
        return $this->belongsTo(Problem::class);
    }
}