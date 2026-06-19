<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    $user = auth()->user();
        $user_id = $user->id;

   $packageSettings = [
        'premium'        => ['kos', 'katering', 'laundry'],
        'basic'          => ['kos', 'katering', 'laundry'],
        'standard-clean' => ['kos', 'laundry'],
        'standard-meal'  => ['kos', 'katering'],
    ];
    

    // Filter hanya pesanan yang sudah dikonfirmasi 'Success' untuk statistik
    $allOrdersSuccess = Order::where('user_id', $user_id)
                             ->where('status', 'Success')
                             ->get();

    $stats = [
        'kos'      => (int) $allOrdersSuccess->sum('kos_price'),
        'katering' => (int) $allOrdersSuccess->sum('catering_price'),
        'laundry'  => (int) $allOrdersSuccess->sum('laundry_price'),
    ];

    $stats['total'] = $stats['kos'] + $stats['katering'] + $stats['laundry'];

    // Daftar riwayat tetap menampilkan semua (agar user bisa lihat yang masih 'Pending')
   $orders = Order::where('user_id', $user_id)->latest()->get();

    $chartMonths = [];
    $chartData = ['kos' => [], 'katering' => [], 'laundry' => []];

    for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $chartMonths[] = $month->format('M');

        // Tambahkan filter status 'Success' pada query grafik bulanan
        $monthlyQuery = Order::where('user_id', $user_id)
                            ->where('status', 'Success') // <--- Kuncinya di sini
                            ->whereMonth('created_at', $month->month)
                            ->whereYear('created_at', $month->year);

        $chartData['kos'][]      = (int) $monthlyQuery->sum('kos_price');
        $chartData['katering'][] = (int) $monthlyQuery->sum('catering_price');
        $chartData['laundry'][]  = (int) $monthlyQuery->sum('laundry_price');
    }

    $activeSub = Order::where('user_id', $user_id)
                        ->whereIn('type', ['kos', 'paket', 'bundling']) 
                        ->where('status', 'Success') // Opsional: Sublangganan aktif jika sudah bayar
                        ->latest()
                        ->first();

    return view('dashboard', compact('stats', 'orders', 'chartMonths', 'chartData', 'activeSub', 'packageSettings'));
}
}