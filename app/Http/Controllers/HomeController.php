<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SearchController;
use App\Models\Service;
use App\Models\Bundle;

class HomeController extends Controller
{
    public function index() {
    $pakets = Bundle::all();
    $recommendations = Service::where('is_verified', true)->take(6)->get();
    return view('beranda', compact('pakets', 'recommendations'));
}
}
