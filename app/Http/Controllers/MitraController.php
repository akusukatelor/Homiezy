<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    public function index() {
        return view('mitra');
    }

    public function dashboard() {
        $user = Auth::user();
        $serviceIds = Service::where('user_id', $user->id)->pluck('id');
        $incomingOrders = Order::whereIn('service_id', $serviceIds)
                               ->with('user') 
                               ->latest()
                               ->get();
        $totalEarnings = Order::whereIn('service_id', $serviceIds)
                             ->where('status', 'Success')
                             ->sum('price');

        return view('dashboard-mitra', compact('incomingOrders', 'totalEarnings'));
    }
}
