<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', // Contoh: ORD001
        'name',         // Nama layanan yang dibeli
        'status',       // Diproses, Diantar, Selesai
        'price',        // Total harga pesanan
        'type',         // kos, katering, laundry
        'user_id'       // Relasi ke user yang memesan
    ];

    /**
     * Relasi ke model User
     * Setiap pesanan dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}