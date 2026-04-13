<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'name', 
        'price_display', 
        'discount_display', 
        'is_popular'
    ];

    // Casting untuk memastikan is_popular dibaca sebagai boolean
    protected $casts = [
        'is_popular' => 'boolean',
    ];
}