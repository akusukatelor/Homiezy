<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
{
    // Definisikan isi tiap paket secara dinamis
    $packageSettings = [
        'premium'        => ['kos', 'katering', 'laundry'],
        'basic'          => ['kos', 'katering', 'laundry'],
        'standard-clean' => ['kos', 'laundry'],
        'standard-meal'  => ['kos', 'katering'],
    ];

    $orders = Order::where('user_id', auth()->id())->latest()->get();

    return view('dashboard', compact('orders', 'packageSettings'));
}
   public function store(Request $request)
{
    try {
        \Log::info('Store request data:', $request->all());
        $user = auth()->user();
        $masterOrderNumber = 'BNDL-' . strtoupper(Str::random(8));

        // 1. Pesanan untuk Unit Kos
        if ($request->kos_id) {
            Order::create([
                'order_number' => $masterOrderNumber,
                'user_id' => $user->id,
                'kos_id' => $request->kos_id, // <--- TAMBAHKAN INI
                'service_id' => $request->kos_id,
                'name' => $request->name . " (Unit Kos)",
                'price' => $request->kos_price,
                'status' => 'Pending',
                'type' => 'bundling',
                'kos_price' => $request->kos_price,
            ]);
        }

        // 2. Pesanan untuk Katering
        if ($request->catering_id) {
            Order::create([
                'order_number' => $masterOrderNumber,
                'user_id' => $user->id,
                'catering_id' => $request->catering_id, // <--- TAMBAHKAN INI
                'service_id' => $request->catering_id,
                'name' => $request->name . " (Layanan Katering)",
                'price' => $request->catering_price,
                'status' => 'Pending',
                'type' => 'bundling',
                'catering_price' => $request->catering_price,
            ]);
        }

        // 3. Pesanan untuk Laundry
        if ($request->laundry_id) {
            Order::create([
                'order_number' => $masterOrderNumber,
                'user_id' => $user->id,
                'laundry_id' => $request->laundry_id, // <--- TAMBAHKAN INI
                'service_id' => $request->laundry_id,
                'name' => $request->name . " (Layanan Laundry)",
                'price' => $request->laundry_price,
                'status' => 'Pending',
                'type' => 'bundling',
                'laundry_price' => $request->laundry_price,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Pesanan berhasil diteruskan ke semua mitra!']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function process($id)
{
    $service = Service::findOrFail($id);
    $user = auth()->user();

    $order = Order::create([
        'order_number' => 'ORD-' . strtoupper(Str::random(8)),
        'user_id' => $user->id,
        'service_id' => $service->id,
        'name' => $service->name . " (Booking)",
        'price' => $service->price,
        'status' => 'Pending',
        'type' => $service->type,
        'kos_price' => $service->type == 'kos' ? $service->price : 0,
        'catering_price' => $service->type == 'katering' ? $service->price : 0,
        'laundry_price' => $service->type == 'laundry' ? $service->price : 0,
    ]);

    // Hapus redirect WA, ganti ke Xendit
    return redirect()->route('xendit.create', $order->id);
}

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        if ($order->service->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses ini.');
        }

        $order->update(['status' => 'Success']);
        return back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    public function cancel($id)
{
    // Cari pesanan yang milik user login dan berstatus Pending
    $order = Order::where('id', $id)
                  ->where('user_id', auth()->id())
                  ->firstOrFail();

    if ($order->status === 'Pending') {
        // Update status menjadi Cancelled, bukan dihapus
        $order->update([
            'status' => 'Cancelled'
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses mitra.');
}

    public function editItem($id, $category)
{
    // 1. Ambil data order milik user yang sedang login
    $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

    // 2. Definisikan ulang mapping paket (karena scope berbeda dengan DashboardController)
    $packageSettings = [
        'premium'        => ['kos', 'katering', 'laundry'],
        'basic'          => ['kos', 'katering', 'laundry'],
        'standard-clean' => ['kos', 'laundry'],
        'standard-meal'  => ['kos', 'katering'],
    ];

    // 3. Normalisasi package_type agar cocok dengan key (Contoh: "Standard Meal" -> "standard-meal")
    $packageKey = \Illuminate\Support\Str::slug($order->package_type);

    // 4. Proteksi: Pesanan harus Pending
    if ($order->status !== 'Pending') {
        return redirect()->route('dashboard')->with('error', 'Pesanan yang sudah lunas tidak bisa diubah.');
    }

    // 5. Proteksi Sisi Server: Cek apakah kategori layanan memang ada di paket tersebut


    // 6. Ambil daftar layanan (vendor) sesuai kategori (kos/katering/laundry)
    $availableServices = \App\Models\Service::where('type', $category)->get();

    $packageName = "Ganti Layanan " . ucfirst($category);

    return view('paket-edit-item', compact('order', 'category', 'availableServices', 'packageName'));
}

public function updateItem(Request $request, $id)
{
    $oldOrder = Order::findOrFail($id);

    if ($oldOrder->status !== 'Pending') {
        return back()->with('error', 'Status sudah final.');
    }

    // A. Matikan pesanan lama
    $oldOrder->update(['status' => 'Dibatalkan']);

    // B. Buat pesanan baru untuk vendor baru
    $newService = Service::findOrFail($request->service_id);
    $category = $request->category;

    // Tentukan kolom mana yang harus diisi
    $idField = ($category == 'katering' ? 'catering' : $category) . '_id';
    $priceField = ($category == 'katering' ? 'catering' : $category) . '_price';

    Order::create([
        'order_number' => 'HMZ-' . strtoupper(Str::random(8)),
        'user_id' => $oldOrder->user_id,
        $idField => $newService->id, // Mengisi kolom spesifik agar muncul di dashboard mitra
        'service_id' => $newService->id,
        'name' => $oldOrder->user->name . " (Layanan " . ucfirst($category) . ")",
        'price' => $newService->price,
        $priceField => $newService->price,
        'status' => 'Pending',
        'type' => $oldOrder->type,
    ]);

    return redirect()->route('dashboard')->with('success', 'Vendor diganti & pesanan lama dibatalkan.');
}
}
