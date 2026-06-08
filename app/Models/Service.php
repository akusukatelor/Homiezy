<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type', 'price', 'image', 'whatsapp',
        'gender', 'location', 'distance', 'is_verified',
        'subtitle', 'schedule', 'features', 'extra_info','room_size',
        'electricity', 'water'
    ];

    protected $casts = [
        'features' => 'array',
        'extra_info' => 'array',
        'is_verified' => 'boolean'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}

public function averageRating()
{
    return $this->reviews()->avg('rating') ?? 0;
}
}
