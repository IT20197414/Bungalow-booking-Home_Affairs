<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bungalow extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'address',
        'city',
        'google_maps_url',
        'capacity',
        'bedrooms',
        'bathrooms',
        'nightly_rate',
        'status',
        'featured',
        'check_in_time',
        'check_out_time',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'nightly_rate' => 'decimal:2',
        ];
    }

    public function images()
    {
        return $this->hasMany(BungalowImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(BungalowImage::class)->where('is_primary', true);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
