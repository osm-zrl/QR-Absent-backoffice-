<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = ['school_sessions_id','user_id','timestamp'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
