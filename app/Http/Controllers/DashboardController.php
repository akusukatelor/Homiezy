<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => '1.800.000',
            'kos' => '1.200.000',
            'katering' => '450.000',
            'laundry' => '150.000'
        ];

        // Data Pesanan Terbaru
        $orders = [
            ['id' => 'ORD001', 'name' => 'QuickWash Express - Cuci Setrika', 'status' => 'Diproses', 'price' => '45.000'],
            ['id' => 'ORD002', 'name' => 'Dapur Mama Rina - Paket Harian', 'status' => 'Diantar', 'price' => '25.000'],
        ];

        return view('dashboard', compact('stats', 'orders'));
    }
}
