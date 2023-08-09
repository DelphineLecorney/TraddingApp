<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'porfile_id',
        'symbol',
        'quantity',
        'open_price',
        'close_price',
        'open_datetime',
        'close_datetime',
        'open',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
