<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'bungalow_id',
        'booking_id',
        'rating',
        'comment',
        'approved',
    ];

    protected function casts(): array
    {
        return [
            'approved' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bungalow()
    {
        return $this->belongsTo(Bungalow::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
