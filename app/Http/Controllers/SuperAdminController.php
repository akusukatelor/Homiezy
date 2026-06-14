<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // Dashboard utama
    public function index()
    {
        $stats = [
            'total_user'     => User::where('role', 'customer')->count(),
            'total_mitra'    => User::where('role', 'mitra')->count(),
            'total_kos'      => Service::where('type', 'kos')->count(),
            'total_catering' => Service::where('type', 'katering')->count(),
            'total_laundry'  => Service::where('type', 'laundry')->count(),
        ];

        return view('superadmin.dashboard', compact('stats'));
    }

    // Halaman form tambah paket
public function createPaket()
{
    $kosList      = Service::where('type', 'kos')->get();
    $cateringList = Service::where('type', 'katering')->get();
    $laundryList  = Service::where('type', 'laundry')->get();
    $mitras       = User::where('role', 'mitra')->get();

    return view('superadmin.paket-create', compact(
        'kosList', 'cateringList', 'laundryList', 'mitras'
    ));
}

// Simpan paket baru
public function storePaket(Request $request)
{
    $request->validate([
        'name'        => 'required|string',
        'tipe_paket'  => 'required|in:kenyang,bersih,lengkap',
        'kos_id'      => 'required|exists:services,id',
        'catering_id' => 'nullable|exists:services,id',
        'laundry_id'  => 'nullable|exists:services,id',
        'diskon'      => 'required|numeric|min:0|max:100',
        'whatsapp'    => 'required|string',
        'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    // Hitung harga
    $kos      = Service::findOrFail($request->kos_id);
    $catering = $request->catering_id ? Service::find($request->catering_id) : null;
    $laundry  = $request->laundry_id  ? Service::find($request->laundry_id)  : null;

    $hargaNormal = $kos->price
        + ($catering ? $catering->price : 0)
        + ($laundry  ? $laundry->price  : 0);

    $hargaPaket = $hargaNormal * (1 - ($request->diskon / 100));

    // Upload image
    $imagePath = null;
    if ($request->hasFile('image')) {
        $filename  = time() . '_' . $request->file('image')->getClientOriginalName();
        $imagePath = '/storage/' . $request->file('image')
                        ->storeAs('services', $filename, 'public');
    } else {
        // Default image berdasarkan tipe
        $defaults = [
            'kenyang' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=400&h=300&fit=crop',
            'bersih'  => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=400&h=300&fit=crop',
            'lengkap' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400&h=300&fit=crop',
        ];
        $imagePath = $defaults[$request->tipe_paket];
    }

    // Susun fitur gabungan
    $features = array_merge(
        $kos->features ?? [],
        $catering ? ($catering->features ?? []) : [],
        $laundry  ? ($laundry->features  ?? []) : [],
    );

    // Susun deskripsi layanan
    $layananList = ['Kos'];
    if ($catering) $layananList[] = 'Catering';
    if ($laundry)  $layananList[] = 'Laundry';

    $subtitle = implode(' + ', $layananList);

    Service::create([
        'user_id'       => $kos->user_id,
        'name'          => $request->name,
        'type'          => 'paket',
        'price'         => $hargaPaket,
        'location'      => $kos->location,
        'distance'      => $kos->distance,
        'whatsapp'      => $request->whatsapp,
        'image'         => $imagePath,
        'subtitle'      => $subtitle,
        'schedule'      => 'Setiap Hari',
        'features'      => json_encode(array_unique($features)),
        'extra_info'    => json_encode([
            $subtitle,
            'Hemat ' . $request->diskon . '%',
            'Harga normal: Rp' . number_format($hargaNormal),
        ]),
        'rating'        => 0,
        'reviews_count' => 0,
    ]);

    return redirect()->route('superadmin.layanan', ['type' => 'paket'])
        ->with('success', 'Paket berhasil ditambahkan! 🎉');
}

// Hapus paket
public function destroyPaket($id)
{
    $service = Service::where('id', $id)->where('type', 'paket')->firstOrFail();
    $service->delete();

    return back()->with('success', 'Paket berhasil dihapus.');
}

    // List semua layanan
    public function layanan(Request $request)
    {
        $type = $request->get('type', 'kos');
        $search = $request->get('search');

        $layanan = Service::with('user')
            ->where('type', $type)
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()
            ->paginate(10);

        return view('superadmin.layanan', compact('layanan', 'type', 'search'));
    }

    // Hapus layanan
    public function destroyLayanan($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    // List semua user
    public function users(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');

        $users = User::when($search, fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
            ->when($role, fn($q) => $q->where('role', $role))
            ->latest()
            ->paginate(10);

        return view('superadmin.users', compact('users', 'search', 'role'));
    }

    // Hapus user
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // Toggle aktif/nonaktif layanan
    public function toggleLayanan($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);

        return back()->with('success', 'Status layanan berhasil diubah.');
    }
}
