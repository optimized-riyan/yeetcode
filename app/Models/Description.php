<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Description extends Model
{
    use HasFactory;

    protected $fillable = [
        'brief',
    ];
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'problem_id';

    public function constraints(): HasMany {
        return $this->hasMany(Constraint::class, 'description_id', 'problem_id');
    }

    public function examples(): HasMany {
        return $this->hasMany(Example::class, 'description_id', 'problem_id');
    }

    public function problem(): BelongsTo {
        return $this->belongsTo(Problem::class, 'problem_id', 'id');
    }
}
