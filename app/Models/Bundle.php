<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'price_display', 
        'discount_display', 
        'is_popular'
    ];

    protected $casts = [
        'is_popular' => 'boolean',
    ];
}