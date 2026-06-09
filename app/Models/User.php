<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Properti yang bisa diisi secara massal.
     * Pastikan 'role' ada di sini agar perpindahan role user bisa diproses.
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'password',
        'google_id',
        'role',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Layanan Bisnis (Untuk Mitra)
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Relasi ke Pesanan (Untuk Customer)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Helper check role
     */
    public function isMitra()
    {
        return $this->role === 'mitra';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
