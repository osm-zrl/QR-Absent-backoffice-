<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $fillable = [
        'id',
        'user_id'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    
}
