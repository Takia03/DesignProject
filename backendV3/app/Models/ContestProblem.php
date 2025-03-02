<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestProblem extends Model
{
    use HasFactory;

    protected $fillable = [
        'contest_id',
        'problem_id',
        'points',
        'order',
        'added_by',
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class)
                    ->with('submissions')
                    ->select(['id', 'title', 'xp', 'tags']);
    }

    public function singleProblem()
    {
        return $this->belongsTo(Problem::class, 'problem_id')
                    ->select(['id', 'title', 'xp', 'tags']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
