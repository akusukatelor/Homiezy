<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditController extends Controller
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    // Dipanggil saat user klik "Bayar" di checkout
    public function createInvoice(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Cegah buat invoice dobel
        if ($order->xendit_invoice_id) {
            return redirect($order->xendit_invoice_url);
        }

        try {
            $invoice = $this->xenditService->createInvoice([
                'external_id' => 'HOMIEZY-ORDER-' . $order->id . '-' . time(),
                'amount'      => $order->price,
                'email'       => auth()->user()->email,
                'description' => 'Pembayaran Order Homiezy #' . $order->id,
            ]);

            // Simpan data invoice ke order
            $order->update([
                'xendit_invoice_id'  => $invoice['invoice_id'],
                'xendit_invoice_url' => $invoice['invoice_url'],
                'payment_status'     => 'pending',
            ]);

            // Redirect ke halaman pembayaran Xendit
            return redirect($invoice['invoice_url']);

        } catch (\Exception $e) {
            Log::error('Xendit create invoice error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat invoice pembayaran. Silakan coba lagi.');
        }
    }

    // Webhook dari Xendit — dipanggil otomatis saat status berubah
    public function webhook(Request $request)
    {
        // Verifikasi token webhook
        $webhookToken = $request->header('x-callback-token');
        if ($webhookToken !== config('xendit.webhook_token')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data   = $request->all();
        $status = $data['status'] ?? null;

        // Cari order berdasarkan external_id
        $externalId = $data['external_id'] ?? null;
        if (!$externalId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Extract order ID dari external_id (format: HOMIEZY-ORDER-{id}-{time})
        preg_match('/HOMIEZY-ORDER-(\d+)-/', $externalId, $matches);
        $orderId = $matches[1] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status berdasarkan status Xendit
        switch ($status) {
            case 'PAID':
                $order->update([
                    'payment_status' => 'paid',
                    'status'         => 'Success',
                ]);
                break;

            case 'EXPIRED':
                $order->update([
                    'payment_status' => 'expired',
                    'status'         => 'Cancelled',
                ]);
                break;

            case 'FAILED':
                $order->update([
                    'payment_status' => 'failed',
                    'status'         => 'Cancelled',
                ]);
                break;
        }

        Log::info('Xendit webhook received', [
            'order_id' => $orderId,
            'status'   => $status,
        ]);

        return response()->json(['message' => 'OK'], 200);
    }

    // Halaman sukses setelah bayar
    public function success(Request $request)
    {
        return view('payment.success');
    }

    // Halaman gagal
    public function failure(Request $request)
    {
        return view('payment.failure');
    }
}
