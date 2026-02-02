<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
public function getServicesData() 
{
    return collect([
        ['type'=>'Kos', 'match'=>'95%', 'name'=>'Kos Putri Mawar Residence', 'loc'=>'Dekat Unsoed, Grendeng', 'dist'=>'500m dari Unsoed', 'rate'=>'4.8', 'rev'=>'124', 'tags'=>['WiFi', 'AC', 'Kamar Mandi'], 'price'=>'1.200.000', 'img'=>'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2070', 'verified'=>true],
        ['type'=>'Kos', 'match'=>'90%', 'name'=>'Kost Eksklusif Melati', 'loc'=>'Margono, Purwokerto Selatan', 'dist'=>'300m dari RSUD Margono', 'rate'=>'4.9', 'rev'=>'89', 'tags'=>['WC', 'AC'], 'price'=>'1.500.000', 'img'=>'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2070', 'verified'=>true],
        ['type'=>'Kos', 'match'=>'85%', 'name'=>'Wisma Anggrek', 'loc'=>'Berkoh, Purwokerto Selatan', 'dist'=>'2km dari UMP', 'rate'=>'4.6', 'rev'=>'67', 'tags'=>['WiFi', 'Parkir'], 'price'=>'900.000', 'img'=>'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?q=80&w=2070', 'verified'=>true],
        ['type'=>'Katering', 'match'=>'92%', 'name'=>'Dapur Mama Ricis', 'loc'=>'JL. Pramuka, Purwokerto Barat', 'dist'=>'1km dari Telkom University', 'rate'=>'4.9', 'rev'=>'156', 'tags'=>['Delivery', 'Enak'], 'price'=>'450.000', 'img'=>'https://images.unsplash.com/photo-1547573854-74d2a71d0826?q=80&w=2070', 'verified'=>true],
        ['type'=>'Laundry', 'match'=>'88%', 'name'=>'QuickWash Express', 'loc'=>'Jl. Ahmad Yani, Purwokerto Utara', 'dist'=>'500m dari Alun-alun Purwokerto', 'rate'=>'4.7', 'rev'=>'234', 'tags'=>['Express 3 Jam'], 'price'=>'6.500', 'img'=>'https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=2071', 'verified'=>true],
        ['type'=>'Kos', 'match'=>'85%', 'name'=>'Kos Campur Hijau', 'loc'=>'Karangnanas, Purwokerto Timur', 'dist'=>'1.2km dari kampus UNU Purwokerto', 'rate'=>'4.5', 'rev'=>'42', 'tags'=>['Parkir Luas'], 'price'=>'900.000', 'img'=>'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2070'],
    ]);
}

    public function index(Request $request)
    {
        $lokasi = $request->input('lokasi');
        $layanan = $request->input('layanan');

        $results = $this->getServicesData(); // Ambil data terpusat

        if ($lokasi) {
            $results = $results->filter(fn($item) => 
                str_contains(strtolower($item['loc']), strtolower($lokasi)) || 
                str_contains(strtolower($item['dist']), strtolower($lokasi))
            );
        }

        if ($layanan && $layanan !== 'Semua Layanan') {
            $results = $results->where('type', $layanan);
        }

        return view('hasil-pencarian', [
            'recommendations' => $results, 
            'search_lokasi' => $lokasi, 
            'search_layanan' => $layanan
        ]);
    }

    public function detail($type, $slug)
    {
        $data = $this->getServicesData(); // Ambil data terpusat

        $item = $data->first(function($value) use ($slug) {
            return Str::slug($value['name']) === $slug;
        });

        if (!$item) abort(404);

        return view('detail', compact('item'));
    }
}