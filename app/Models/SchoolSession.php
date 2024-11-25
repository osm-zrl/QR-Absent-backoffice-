<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSession extends Model
{
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
