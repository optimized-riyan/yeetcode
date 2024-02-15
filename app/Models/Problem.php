<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    public $timestamps = false;

    public function difficulty(): BelongsTo {
        return $this->belongsTo(Difficulty::class);
    }

    public function description(): HasOne {
        return $this->hasOne(Description::class, 'problem_id', 'id');
    }

    public function hints(): HasMany {
        return $this->hasMany(Hint::class);
    }

    public function topics(): HasMany {
        return $this->hasMany(Topic::class);
    }

    public function similarProblems(): BelongsToMany {
        return $this->belongsToMany(Problem::class, 'problem_problem', 'problem1_id', 'problem2_id');
    }
}
