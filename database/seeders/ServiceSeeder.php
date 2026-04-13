<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. DATA KOS
        $kos = [
            [
                'name' => 'Kos Putri Mawar Residence',
                'type' => 'kos',
                'price' => 1200000,
                'gender' => 'Putri',
                'location' => 'Dekat Unsoed, Grendeng',
                'distance' => '500m dari Unsoed',
                'rating' => 4.8,
                'reviews_count' => 124,
                'is_verified' => true,
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267',
                'features' => ['WiFi', 'AC', 'Kamar Mandi'],
            ],
            [
                'name' => 'Kost Eksklusif Melati',
                'type' => 'kos',
                'price' => 1500000,
                'gender' => 'Putri',
                'location' => 'Margono, Purwokerto Selatan',
                'distance' => '300m dari RSUD Margono',
                'rating' => 4.9,
                'reviews_count' => 89,
                'is_verified' => true,
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af',
                'features' => ['WC', 'AC'],
            ],
            [
                'name' => 'Kos Campur Hijau',
                'type' => 'kos',
                'price' => 900000,
                'gender' => 'Putra',
                'location' => 'Karangnanas, Purwokerto Timur',
                'distance' => '1.2km dari kampus UNU Purwokerto',
                'rating' => 4.5,
                'reviews_count' => 42,
                'is_verified' => false,
                'image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb',
                'features' => ['Parkir Luas', 'Dapur'],
            ],
        ];

        // 2. DATA KATERING
        $catering = [
            [
                'name' => 'Paket Katering Ekonomis',
                'type' => 'catering',
                'price' => 450000,
                'subtitle' => 'Makan siang & malam untuk hari kerja',
                'frequency' => '2x Makan',
                'schedule' => 'Senin - Sabtu',
                'rating' => 4.5,
                'reviews_count' => 234,
                'image' => 'https://images.unsplash.com/photo-1547573854-74d2a71d0826',
                'features' => ['Menu bervariasi setiap hari', 'Lunch box tersedia', 'Porsi dapat disesuaikan', 'Pengiriman tepat waktu'],
                'extra_info' => ['Ikan Bakar + Nasi + Tumis Kangkung', 'Rendang + Nasi + Perkedel', 'Ayam Goreng + Nasi + Sayur'],
            ],
            [
                'name' => 'Paket Katering Premium',
                'type' => 'catering',
                'price' => 850000,
                'subtitle' => 'Makan 3x sehari setiap hari',
                'frequency' => '3x Makan',
                'schedule' => 'Setiap Hari',
                'rating' => 4.8,
                'reviews_count' => 458,
                'image' => 'https://images.unsplash.com/photo-1543332164-6e82f355badc',
                'features' => ['Menu premium & bergizi', 'Free snack 2x seminggu', 'Nutrisi terjamin', 'Pilihan menu vegetarian'],
                'extra_info' => ['Sarapan: Nasi Uduk + Ayam + Telur', 'Makan Siang: Steak + Mashed Potato', 'Makan Malam: Salmon + Brown Rice'],
            ],
        ];

        // 3. DATA LAUNDRY
        $laundry = [
            [
                'name' => 'Paket Laundry Hemat',
                'type' => 'laundry',
                'price' => 88000,
                'frequency' => '2x seminggu',
                'subtitle' => 'Max 10kg',
                'rating' => 4.5,
                'image' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f',
                'features' => ['Cuci bersih & wangi', 'Lipat teratur', 'Setrika rapi', 'Pick up & delivery gratis'],
                'extra_info' => ['Cuci Kering', 'Setrika', 'Lipat'],
            ],
            [
                'name' => 'Paket Laundry Premium',
                'type' => 'laundry',
                'price' => 150000,
                'frequency' => 'Setiap hari (on-demand)',
                'subtitle' => 'Unlimited',
                'rating' => 4.9,
                'image' => 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60',
                'features' => ['Laundry tanpa batas', 'Parfum premium pilihan', 'Priority pick up & delivery', 'Express service (4 jam)', 'Dry cleaning tersedia'],
                'extra_info' => ['Cuci Kering', 'Setrika', 'Lipat', 'Dry Cleaning', 'Express'],
            ],
        ];

        foreach (array_merge($kos, $catering, $laundry) as $data) {
            Service::create($data);
        }
    }
}
