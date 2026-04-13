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
        $item = Service::where('type', $type)->get()->first(function($value) use ($slug) {
            return Str::slug($value->name) === $slug;
        });

        if (!$item) abort(404);

        return view('detail', compact('item'));
    }
}