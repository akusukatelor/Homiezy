<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

   protected $fillable = [
    'type', 'name', 'location', 'distance', 'rating', 'reviews_count', 'price', 'is_verified', 'image'
];

    // Casting sangat penting karena data fasilitas dan menu dikirim sebagai JSON
    protected $casts = [
        'features' => 'array',
        'extra_info' => 'array',
        'is_verified' => 'boolean',
        'rating' => 'float',
    ];
}