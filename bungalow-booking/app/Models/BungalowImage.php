<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BungalowImage extends Model
{
    protected $fillable = [
        'bungalow_id',
        'path',
        'caption',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function bungalow()
    {
        return $this->belongsTo(Bungalow::class);
    }
}
