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
        $allOrders = Order::where('user_id', $user_id)->get();

       $stats = [
        'kos'      => (int) $allOrders->sum('kos_price'),
        'katering' => (int) $allOrders->sum('catering_price'),
        'laundry'  => (int) $allOrders->sum('laundry_price'),
    ];

    $stats['total'] = $stats['kos'] + $stats['katering'] + $stats['laundry'];
        $orders = Order::where('user_id', $user_id)->latest()->take(5)->get();
        $chartMonths = [];
        $chartData = ['kos' => [], 'katering' => [], 'laundry' => []];

for ($i = 5; $i >= 0; $i--) {
    $month = Carbon::now()->subMonths($i);
    $chartMonths[] = $month->format('M');

  
    $monthlyQuery = Order::where('user_id', $user_id)
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year);
    $chartData['kos'][]      = (int) $monthlyQuery->sum('kos_price');
    $chartData['katering'][] = (int) $monthlyQuery->sum('catering_price');
    $chartData['laundry'][]  = (int) $monthlyQuery->sum('laundry_price');
}
            $activeSub = Order::where('user_id', $user_id)
                            ->whereIn('type', ['kos', 'paket', 'bundling']) 
                            ->latest()
                            ->first();
            return view('dashboard', compact('stats', 'orders', 'chartMonths', 'chartData', 'activeSub'));
    }
}