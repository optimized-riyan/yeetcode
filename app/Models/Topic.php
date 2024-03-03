<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function problems(): HasMany {
        return $this->hasMany(Problem::class);
    }

    public function scopeTopicsWithFilter($query, $title = '') {
        if ($title)
            return $query->where('name', 'like', '%'.$title.'%');
        else
            return $query;
    }
}
