<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class SchoolSession extends Model
{   
    protected $fillable = ['intitule','date','user_id','period'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'school_session_id');
    }

    
}
