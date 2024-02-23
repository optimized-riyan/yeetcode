<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'testcase'
    ];
    public $timestamps = false;

    public function problem(): BelongsTo {
        return $this->belongsTo(Problem::class);
    }
}
