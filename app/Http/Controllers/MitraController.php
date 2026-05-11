<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
   public function index()
    {
        return view('mitra'); 
    }

    // HALAMAN ADMIN PANEL (Method yang dicari Error tadi)
    public function dashboard()
{
    $user = Auth::user();
    $service = Service::where('user_id', $user->id)->first();

    if (!$service) {
        return redirect()->route('mitra')->with('error', 'Silakan daftar bisnis dulu.');
    }

    // Tentukan nama kolom berdasarkan tipe bisnis mitra
    $column = ($service->type == 'katering' ? 'catering' : $service->type) . '_id';

    // Ambil SEMUA pesanan yang pernah masuk ke mitra ini
    $allOrders = Order::where($column, $service->id)->latest()->get();

    // Pisahkan mana yang masih pending untuk tabel "Pesanan Masuk"
    $incomingOrders = $allOrders->where('status', 'Pending');

    $stats = [
        'earnings' => (int) $allOrders->where('status', 'Success')->sum('price'),
        'pending'  => $incomingOrders->count(),
        'total'    => $allOrders->count(),
    ];

    return view('mitra.dashboard', compact('service', 'incomingOrders', 'stats'));
}

   public function update(Request $request, $id)
{
    $service = Service::findOrFail($id);
    
    if ($service->user_id !== auth()->id()) {
        abort(403);
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'location' => 'required|string',
        'distance' => 'required|string',
        'whatsapp' => 'required|string', // Tambahkan ini
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        
        // Field Opsional (Bisa null tergantung tipe bisnis)
        'gender' => 'nullable|string',
        'subtitle' => 'nullable|string',
        'schedule' => 'nullable|string',
        'room_size' => 'nullable|string',
        'electricity' => 'nullable|string',
        'water' => 'nullable|string',
        'features' => 'nullable|array',
        'extra_info' => 'nullable|array', // Penting untuk menu katering
    ]);

    if ($request->hasFile('image')) {
        // Hapus foto lama jika ada
        if ($service->image) {
            // Pastikan path yang dihapus benar (tanpa prefix /storage/)
            $oldPath = str_replace('/storage/', '', $service->image);
            Storage::disk('public')->delete($oldPath);
        }
        
        $path = $request->file('image')->store('services', 'public');
        $data['image'] = '/storage/' . $path; // Samakan format dengan PartnerController
    }

    $service->update($data);

    return back()->with('success', 'Data bisnis berhasil diperbarui!');
}
}