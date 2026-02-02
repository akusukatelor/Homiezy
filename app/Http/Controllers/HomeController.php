<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SearchController;

class HomeController extends Controller
{
    public function index()
    {
        $pakets = [
            ['nama' => 'Basic', 'harga' => '700rb', 'hemat' => '150rb'],
            ['nama' => 'Standard Clean', 'harga' => '788rb', 'hemat' => '162rb'],
            ['nama' => 'Standard Meal', 'harga' => '1.6jt', 'hemat' => '300rb', 'populer' => true],
            ['nama' => 'Premium', 'harga' => '1.8jt', 'hemat' => '372rb'],
        ];

        $searchCtrl = new SearchController();
        $recommendations = $searchCtrl->getServicesData();

        return view('beranda', compact('pakets','recommendations'));
    }
}
