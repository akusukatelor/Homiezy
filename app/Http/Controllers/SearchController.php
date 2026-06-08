<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Service;

class SearchController extends Controller
{
    public function getServicesData() {
        return Service::all();
    }

    public function index(Request $request) {
        $lokasi = $request->input('lokasi');
        $layanan = $request->input('layanan');
        $query = Service::query();

        if ($lokasi) {
            $query->where(function($q) use ($lokasi) {
                $q->where('location', 'like', "%$lokasi%")
                  ->orWhere('distance', 'like', "%$lokasi%");
            });
        }

        if ($layanan && $layanan !== 'Semua Layanan') {
            $query->where('type', strtolower($layanan));
        }

        return view('hasil-pencarian', [
            'recommendations' => $query->get(),
            'search_lokasi' => $lokasi,
            'search_layanan' => $layanan
        ]);
    }

    public function detail($type, $slug)
{
    $item = Service::all()->first(function($value) use ($slug) {
        return \Illuminate\Support\Str::slug($value->name) === $slug;
    });

    if (!$item) abort(404);

    // Ambil semua review
    $reviews = $item->reviews()->with('user')->latest()->get();

    // Cek apakah user sudah pernah order layanan ini dan statusnya Success
    $userOrder = null;
    $hasReviewed = false;

    if (auth()->check()) {
        $userOrder = auth()->user()->orders()
            ->where('service_id', $item->id)
            ->where('status', 'Success')
            ->first();

        $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
            ->where('service_id', $item->id)
            ->exists();
    }

    return view('detail', compact('item', 'reviews', 'userOrder', 'hasReviewed'));
}
}
