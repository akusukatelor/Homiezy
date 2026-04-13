<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|string',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()), 
            'name' => $request->name,
            'status' => 'Diproses',
            'price' => $request->price,
            'type' => $request->type,
            'user_id' => Auth::id(), 
        ]);

        return response()->json(['success' => true, 'order' => $order]);
    }
}