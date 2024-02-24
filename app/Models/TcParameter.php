<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TcParameter extends Model
{
    use HasFactory;

    protected $fillable = ['params'];
    public $timestamps = false;
    public $primaryKey = 'problem_id';

    public function problem(): BelongsTo {
        return $this->belongsTo(Problem::class, 'problem_id', 'id');
    }
}
