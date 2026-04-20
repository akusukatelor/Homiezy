<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Bundle;

class HomeController extends Controller
{
    public function index() 
    {
        $pakets = Bundle::all();
        $kosItems = Service::where('type', 'kos')->get();
        $cateringItems = Service::where('type', 'katering')->get();
        $laundryItems = Service::where('type', 'laundry')->get();
        $recommendations = Service::latest()->get();

        return view('beranda', compact(
            'pakets', 
            'recommendations', 
            'kosItems', 
            'cateringItems', 
            'laundryItems'
        ));
    }
}