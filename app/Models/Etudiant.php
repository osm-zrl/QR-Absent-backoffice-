<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etudiant extends Model
{
    protected $primaryKey = 'id'; // This is to specify the primary key field
    public $incrementing = false; // This tells Eloquent the ID is not auto-incrementing
    protected $keyType = 'string'; // This ensures the ID is treated as a string

    protected $fillable = [
        'id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}