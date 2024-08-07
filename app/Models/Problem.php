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
        'description',
        'tc_parameters'
    ];
    public $timestamps = false;

    public function difficulty(): BelongsTo {
        return $this->belongsTo(Difficulty::class);
    }

    public function examples(): HasMany {
        return $this->hasMany(Example::class);
    }

    public function constraints(): HasMany {
        return $this->hasMany(Constraint::class);
    }

    public function hints(): HasMany {
        return $this->hasMany(Hint::class);
    }

    public function scaffholdings(): HasMany {
        return $this->hasMany(Scaffholding::class);
    }

    public function topics(): BelongsToMany {
        return $this->belongsToMany(Topic::class, "related_topics");
    }

    public function similarProblems(): BelongsToMany {
        return $this->belongsToMany(Problem::class, 'similar_problems', 'problem1_id', 'problem2_id');
    }

    public function testcases(): HasMany {
        return $this->hasMany(Testcase::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function tcParameters(): HasOne {
        return $this->hasOne(TcParameter::class, 'problem_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "explored_problems")->withPivot("status");
    }

    public function scopeProblemsByTitle($query, $title = '') {
        if ($title)
            return $query->where('name', 'like', '%'.$title.'%');
        else
            return $query;
    }
}
