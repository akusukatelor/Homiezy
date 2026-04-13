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
        $monthOrders = Order::where('user_id', $user_id)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->get();

        $stats = [
            'total' => $monthOrders->sum('price'),
            'kos' => $monthOrders->where('type', 'kos')->sum('price'),
            'katering' => $monthOrders->where('type', 'katering')->sum('price'),
            'laundry' => $monthOrders->where('type', 'laundry')->sum('price'),
        ];
        $orders = Order::where('user_id', $user_id)->latest()->take(5)->get();
        $chartMonths = [];
        $chartData = ['kos' => [], 'katering' => [], 'laundry' => []];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartMonths[] = $month->format('M');

            $chartData['kos'][] = Order::where('user_id', $user_id)->where('type', 'kos')->whereMonth('created_at', $month->month)->sum('price');
            $chartData['katering'][] = Order::where('user_id', $user_id)->where('type', 'katering')->whereMonth('created_at', $month->month)->sum('price');
            $chartData['laundry'][] = Order::where('user_id', $user_id)->where('type', 'laundry')->whereMonth('created_at', $month->month)->sum('price');
        }
            $activeSub = Order::where('user_id', $user_id)
                            ->whereIn('type', ['kos', 'paket', 'bundling']) 
                            ->latest()
                            ->first();
            return view('dashboard', compact('stats', 'orders', 'chartMonths', 'chartData', 'activeSub'));
    }
}