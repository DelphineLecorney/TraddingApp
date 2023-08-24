<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wires()
    {
        return $this->hasMany(Wire::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }
}
