<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    
}
