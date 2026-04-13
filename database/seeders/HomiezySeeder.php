<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\Bundle;

class HomiezySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run() {

   $user = User::updateOrCreate(
        ['email' => 'alfin@example.com'], // Cek apakah email ini sudah ada
        [
            'name' => 'Alfin Ilham',
            'password' => bcrypt('password'),
            'whatsapp' => '08123456789'
        ]
    );
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
            'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267' 
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
            'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267' 
        ],
        [
            'type' => 'kos', 
            'name' => 'Wisma Anggrek', 
            'location' => 'Berkoh, Purwokerto Selatan', 
            'distance' => '2km dari UMP', 
            'rating' => 4.6, 
            'reviews_count' => 67, 
            'price' => 900000, 
            'is_verified' => true, 
            'image' => 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60' 
        ],
        [
            'type' => 'katering', 
            'name' => 'Dapur Mama Ricis', 
            'location' => 'JL. Pramuka, Purwokerto Barat', 
            'distance' => '1km dari Telkom University', 
            'rating' => 4.9, 
            'reviews_count' => 156, 
            'price' => 450000, 
            'is_verified' => true, 
            'image' => 'https://images.unsplash.com/photo-1547573854-74d2a71d0826' 
        ],
        [
            'type' => 'laundry', 
            'name' => 'QuickWash Express', 
            'location' => 'Jl. Ahmad Yani, Purwokerto Utara', 
            'distance' => '500m dari Alun-alun Purwokerto', 
            'rating' => 4.7, 
            'reviews_count' => 234, 
            'price' => 6500, 
            'is_verified' => true, 
            'image' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f' 
        ],
    ];

    foreach($services as $s) { 
        Service::create($s); 
    }

    // 2. PAKET BUNDLING (Lanjutkan kodenya tetap sama seperti yang kamu buat)
    $bundles = [
        ['name' => 'Basic', 'price_display' => '700rb', 'discount_display' => '150rb'],
        ['name' => 'Standard Clean', 'price_display' => '788rb', 'discount_display' => '162rb'],
        ['name' => 'Standard Meal', 'price_display' => '1.6jt', 'discount_display' => '300rb', 'is_popular' => true],
        ['name' => 'Premium', 'price_display' => '1.8jt', 'discount_display' => '372rb'],
    ];
    foreach($bundles as $b) { Bundle::create($b); }

    // 3. DUMMY ORDERS
    Order::create([
        'order_number' => 'ORD001', 
        'name' => 'QuickWash Express - Cuci Setrika', 
        'status' => 'Diproses', 
        'price' => 45000, 
        'type' => 'laundry', 
        'user_id' => $user->id 
    ]);

    Order::create([
        'order_number' => 'ORD002', 
        'name' => 'Dapur Mama Rina - Paket Harian', 
        'status' => 'Diantar', 
        'price' => 25000, 
        'type' => 'katering', 
        'user_id' => $user->id
    ]);
}
}
