<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|min:10|max:500',
        ]);

        // Pastikan order milik user yang login dan statusnya Success
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', auth()->id())
                      ->where('status', 'Success')
                      ->firstOrFail();

        // Cegah review dobel
        $existing = Review::where('user_id', auth()->id())
                          ->where('service_id', $request->service_id)
                          ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk layanan ini.');
        }

        Review::create([
            'user_id'    => auth()->id(),
            'service_id' => $request->service_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // Update rating di tabel services
        $avg = Review::where('service_id', $request->service_id)->avg('rating');
        $count = Review::where('service_id', $request->service_id)->count();

        Service::where('id', $request->service_id)->update([
            'rating'        => round($avg, 1),
            'reviews_count' => $count,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih 😊');
    }
}
