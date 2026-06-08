<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'kos_id','order_number', 'name', 'status', 'price',
        'kos_price', 'catering_price', 'laundry_price',
        'type', 'user_id', 'service_id',
        'xendit_invoice_id',   // ← tambah
        'xendit_invoice_url',  // ← tambah
        'payment_status',      // ← tambah
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
