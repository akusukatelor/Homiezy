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

    // Ganti query — pakai service_id bukan kos_id/catering_id/laundry_id
    $allOrders = Order::where('service_id', $service->id)
                      ->latest()
                      ->get();

    // ← Ubah: tampilkan SEMUA order, bukan hanya pending
    $incomingOrders = $allOrders;

    // Hitung notifikasi — order yang baru dibayar (payment_status = paid tapi belum dikonfirmasi mitra)
    $newPaidOrders = $allOrders->where('payment_status', 'paid')
                               ->where('status', 'Pending')
                               ->count();

    $stats = [
        'earnings' => (int) $allOrders->where('status', 'Success')->sum('price'),
        'pending'  => $allOrders->where('status', 'Pending')->count(),
        'total'    => $allOrders->count(),
    ];

    return view('mitra.dashboard', compact(
        'service', 'incomingOrders', 'stats', 'newPaidOrders'
    ));
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
