<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; 

class OrderController extends Controller
{
   public function store(Request $request)
{
    try {
        $user = auth()->user();
        $masterOrderNumber = 'BNDL-' . strtoupper(Str::random(8));
        
        if ($request->kos_id) {
            Order::create([
                'order_number' => $masterOrderNumber,
                'user_id' => $user->id,
                'service_id' => $request->kos_id, 
                'name' => $request->name . " (Unit Kos)",
                'price' => $request->kos_price,
                'status' => 'Pending',
                'type' => 'bundling',
                'kos_price' => $request->kos_price,
            ]);
        }

        if ($request->catering_id) {
            Order::create([
                'order_number' => $masterOrderNumber,
                'user_id' => $user->id,
                'service_id' => $request->catering_id, 
                'name' => $request->name . " (Layanan Katering)",
                'price' => $request->catering_price,
                'status' => 'Pending',
                'type' => 'bundling',
                'catering_price' => $request->catering_price,
            ]);
        }

        if ($request->laundry_id) {
            Order::create([
                'order_number' => $masterOrderNumber,
                'user_id' => $user->id,
                'service_id' => $request->laundry_id, 
                'name' => $request->name . " (Layanan Laundry)",
                'price' => $request->laundry_price,
                'status' => 'Pending',
                'type' => 'bundling',
                'laundry_price' => $request->laundry_price,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Pesanan berhasil diteruskan ke semua mitra!']);
        
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function process($id)
    {
        $service = Service::findOrFail($id);
        $user = auth()->user();

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'service_id' => $service->id, 
            'name' => $service->name . " (Booking)",
            'price' => $service->price,
            'status' => 'Pending',
            'type' => $service->type,
            'kos_price' => $service->type == 'kos' ? $service->price : 0,
            'catering_price' => $service->type == 'katering' ? $service->price : 0,
            'laundry_price' => $service->type == 'laundry' ? $service->price : 0,
        ]);

        $wa = $service->whatsapp;
        if (str_starts_with($wa, '0')) { $wa = '62' . substr($wa, 1); }
        
        $pesan = "Halo, saya " . $user->name . ". Saya baru saja melakukan booking *" . $service->name . "* melalui Homiezy.\n\n*ID Pesanan:* " . $order->order_number;

        return redirect()->away("https://wa.me/{$wa}?text=" . urlencode($pesan));
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        if ($order->service->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses ini.');
        }

        $order->update(['status' => 'Success']);
        return back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }
}