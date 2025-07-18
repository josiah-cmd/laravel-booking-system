<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'date', 'people', 'start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

