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
