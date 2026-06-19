<?php

namespace App\Services;

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    public function createInvoice(array $data): array
    {
        // Tambah ini untuk debug
    \Illuminate\Support\Facades\Log::info('Xendit create invoice data:', $data);

    $params = new CreateInvoiceRequest([
        'external_id'       => $data['external_id'],
        'amount'            => (int) $data['amount'], // ← cast ke integer
        'payer_email'       => $data['email'],
        'description'       => $data['description'],
        'currency'          => 'IDR',
        'invoice_duration'  => 86400,
        'success_redirect_url' => 'http://localhost:8000/dashboard-saya',
'failure_redirect_url' => 'http://localhost:8000/payment/failure',
        'payment_methods'   => ['QRIS', 'BCA', 'BNI', 'BRI', 'MANDIRI', 'OVO', 'DANA', 'GOPAY'],
    ]);

    $response = $this->invoiceApi->createInvoice($params);

        return [
            'invoice_id'  => $response->getId(),
            'invoice_url' => $response->getInvoiceUrl(),
            'status'      => $response->getStatus(),
            'external_id' => $response->getExternalId(),
        ];
    }

    public function getInvoice(string $invoiceId): array
    {
        $response = $this->invoiceApi->getInvoiceById($invoiceId);

        return [
            'invoice_id'  => $response->getId(),
            'status'      => $response->getStatus(),
            'external_id' => $response->getExternalId(),
            'amount'      => $response->getAmount(),
        ];
    }
}
