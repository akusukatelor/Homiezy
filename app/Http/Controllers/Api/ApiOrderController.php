<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiOrderController extends Controller
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($o) => $this->formatOrder($o));

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'service_id'    => 'nullable|string', // ← ubah jadi nullable
        'tanggal_mulai' => 'required|date',
        'durasi_bulan'  => 'required|integer|min:1|max:12',
        'alamat'        => 'nullable|string', // ← ubah jadi nullable
        'catatan'       => 'nullable|string',
        'tipe'          => 'nullable|string', // ← tambah ini
        'kos_id'        => 'nullable|exists:services,id', // ← tambah ini
    ]);

    // Tentukan service_id yang valid
    $serviceId = $request->service_id;

    // Kalau service_id bukan angka (berarti ID paket), pakai kos_id
    if (!is_numeric($serviceId)) {
        $serviceId = $request->kos_id;
    }

    if (!$serviceId) {
        return response()->json([
            'success' => false,
            'message' => 'Service tidak ditemukan.',
        ], 422);
    }

    $service = Service::findOrFail($serviceId);
    $total   = (float) $request->total_harga ?? ($service->price * $request->durasi_bulan);

    $order = Order::create([
        'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
        'user_id'        => $request->user()->id,
        'service_id'     => $service->id,
        'name'           => $service->name . ' (' . ($request->tipe ?? 'Mobile') . ')',
        'price'          => $total,
        'status'         => 'Pending',
        'type'           => $request->tipe ?? $service->type,
        'payment_status' => 'pending',
        'kos_price'      => $service->type == 'kos' ? $total : 0,
        'catering_price' => $service->type == 'katering' ? $total : 0,
        'laundry_price'  => $service->type == 'laundry' ? $total : 0,
    ]);

    // Buat Xendit Invoice
    try {
        $invoice = $this->xenditService->createInvoice([
            'external_id' => 'HOMIEZY-ORDER-' . $order->id . '-' . time(),
            'amount'      => (int) $order->price,
            'email'       => $request->user()->email,
            'description' => 'Pembayaran Order Homiezy #' . $order->id,
        ]);

        $order->update([
            'xendit_invoice_id'  => $invoice['invoice_id'],
            'xendit_invoice_url' => $invoice['invoice_url'],
        ]);

    } catch (\Exception $e) {
        \Log::error('Xendit invoice error: ' . $e->getMessage());
    }

    return response()->json([
        'success' => true,
        'message' => 'Order berhasil dibuat.',
        'data'    => $this->formatOrder($order->fresh()),
    ], 201);
}

    public function show(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $this->formatOrder($order),
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'Pending')
            ->firstOrFail();

        $order->update(['status' => 'Cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibatalkan.',
        ]);
    }

    private function formatOrder($order)
    {
        return [
            'id'                 => $order->id,
            'order_number'       => $order->order_number,
            'nama_layanan'       => $order->name,
            'tipe'               => $order->type,
            'status'             => strtolower($order->status),
            'payment_status'     => $order->payment_status ?? 'pending',
            'total_harga'        => (float) $order->price,
            'xendit_invoice_url' => $order->xendit_invoice_url,
            'created_at'         => $order->created_at->toIso8601String(),
        ];
    }
}
