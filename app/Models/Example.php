<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Example extends Model
{
    use HasFactory;

    protected $fillable = [
        'input',
        'output',
        'explaination',
    ];
    public $timestamps = false;

    public function description(): BelongsTo {
        return $this->belongsTo(Description::class, 'description_id', 'problem_id');
    }
}
