<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AttendanceRecord extends Model
{
    protected $fillable = ['school_sessions_id','user_id','timestamp'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'school_session_id');
    }
}
