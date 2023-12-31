<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Profile;

class Wire extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'amount',
        'withdrawal',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
