<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function create() {
        return view('partner.register');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'price' => 'required|numeric',
            'whatsapp' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('services', $filename, 'public');
        }

        Service::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'whatsapp' => $request->whatsapp,
            'image' => '/storage/' . $path,
            'gender' => $request->gender,
            'location' => $request->location,
            'subtitle' => $request->subtitle,
            'schedule' => $request->schedule,
            'features' => $request->features, 
            'extra_info' => $request->extra_info, 
        ]);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil!');
    }
}