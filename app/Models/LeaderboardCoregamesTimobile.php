<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardCoregamesTimobile extends Model
{
    protected $connection = 'coregames_timobile';

    protected $table = 'leaderboard';

    protected $fillable = [
        'msisdn',
        'op_id',
        'app_id',
        'point',
        'time_updated',
    ];

    public $timestamps = false;
}
