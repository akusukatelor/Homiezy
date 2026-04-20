<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\Bundle;

class HomiezySeeder extends Seeder
{
    public function run() {

        // 1. Buat atau Ambil User (Pemilik Layanan)
        $user = User::updateOrCreate(
            ['email' => 'alfin@example.com'],
            [
                'name' => 'Alfin Ilham',
                'password' => bcrypt('password'),
                'whatsapp' => '08123456789'
            ]
        );

        // 2. Data Services (Kos, Katering, Laundry)
        $services = [
            [
                'type' => 'kos', 
                'name' => 'Kos Putri Mawar Residence', 
                'location' => 'Dekat Unsoed, Grendeng', 
                'distance' => '500m dari Unsoed', 
                'rating' => 4.8, 
                'reviews_count' => 124, 
                'price' => 1200000, 
                'is_verified' => true, 
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267',
                'gender' => 'Putri',
                'features' => ['WiFi', 'AC', 'KM Dalam', 'Parkir']
            ],
            [
                'type' => 'kos', 
                'name' => 'Kost Eksklusif Melati', 
                'location' => 'Margono, Purwokerto Selatan', 
                'distance' => '300m dari RSUD Margono', 
                'rating' => 4.9, 
                'reviews_count' => 89, 
                'price' => 1500000, 
                'is_verified' => true, 
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af',
                'gender' => 'Putri',
                'features' => ['AC', 'Water Heater', 'TV', 'Laundry']
            ],
            [
                'type' => 'katering', 
                'name' => 'Dapur Mama Ricis', 
                'location' => 'JL. Pramuka, Purwokerto Barat', 
                'distance' => '1km dari Kampus', 
                'rating' => 4.9, 
                'reviews_count' => 156, 
                'price' => 450000, 
                'is_verified' => true, 
                'image' => 'https://images.unsplash.com/photo-1547573854-74d2a71d0826',
                'subtitle' => 'Masakan Rumahan Sehat',
                'frequency' => '2x Makan',
                'schedule' => 'Senin - Sabtu',
                'extra_info' => ['Gratis Ongkir', 'Non MSG', 'Halal']
            ],
            [
                'type' => 'laundry', 
                'name' => 'QuickWash Express', 
                'location' => 'Jl. Ahmad Yani, Purwokerto Utara', 
                'distance' => '500m dari Kampus', 
                'rating' => 4.7, 
                'reviews_count' => 234, 
                'price' => 6500, 
                'is_verified' => true, 
                'image' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f',
                'subtitle' => 'Cuci Setrika Kilat',
                'frequency' => 'On-demand',
                'extra_info' => ['Parfum Premium', 'Express 4 Jam']
            ],
        ];

        foreach($services as $s) { 
            $s['user_id'] = $user->id;
            $s['whatsapp'] = $user->whatsapp;
            Service::create($s); 
        }

        // 3. Data Paket Bundling
        $bundles = [
            ['name' => 'Basic', 'price_display' => '700rb', 'discount_display' => '150rb'],
            ['name' => 'Standard Clean', 'price_display' => '788rb', 'discount_display' => '162rb'],
            ['name' => 'Standard Meal', 'price_display' => '1.6jt', 'discount_display' => '300rb', 'is_popular' => true],
            ['name' => 'Premium', 'price_display' => '1.8jt', 'discount_display' => '372rb'],
        ];
        foreach($bundles as $b) { Bundle::create($b); }

        // 4. Dummy Orders (DENGAN HARGA BREAKDOWN UNTUK DASHBOARD)
        Order::create([
            'order_number' => 'ORD-TEST-001', 
            'name' => 'QuickWash Express - Cuci Setrika', 
            'status' => 'Diproses', 
            'price' => 65000, 
            'laundry_price' => 65000, // Harga spesifik laundry
            'type' => 'laundry', 
            'user_id' => $user->id 
        ]);

        Order::create([
            'order_number' => 'ORD-TEST-002', 
            'name' => 'Paket Standard Meal - Kos Mawar', 
            'status' => 'Diantar', 
            'price' => 1400000, 
            'kos_price' => 1000000,      // Breakdown untuk statistik kos
            'catering_price' => 400000,  // Breakdown untuk statistik katering
            'type' => 'paket', 
            'user_id' => $user->id
        ]);
    }
}