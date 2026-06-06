<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentGatewayService
{
    public function createPayment(Order $order): array
    {
        if (config('services.midtrans.server_key')) {
            return $this->createMidtransSnapPayment($order);
        }

        Log::warning('Midtrans server key is empty. Payment URL falls back to invoice page.', [
            'order_id' => $order->order_id,
        ]);

        return [
            'payment_reference' => $order->order_id,
            'payment_url' => route('invoice.show', $order->invoice_number),
            'qr_code' => null,
        ];
    }

    protected function createMidtransSnapPayment(Order $order): array
    {
        $order->loadMissing(['game', 'product', 'paymentMethod']);

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => [
                [
                    'id' => (string) $order->product_id,
                    'price' => (int) $order->subtotal,
                    'quantity' => 1,
                    'name' => str($order->game->name.' - '.$order->product->name)->limit(50, '')->toString(),
                ],
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?: 'Customer YouxTopUp',
                'phone' => $this->normalizePhone($order->whatsapp),
            ],
            'callbacks' => [
                'finish' => route('payment.midtrans.finish'),
            ],
            'custom_field1' => $order->invoice_number,
            'custom_field2' => $order->whatsapp,
        ];

        if ($order->fee > 0) {
            $payload['item_details'][] = [
                'id' => 'FEE',
                'price' => (int) $order->fee,
                'quantity' => 1,
                'name' => 'Biaya layanan',
            ];
        }

        if ($order->discount > 0) {
            $payload['item_details'][] = [
                'id' => 'DISCOUNT',
                'price' => -1 * (int) $order->discount,
                'quantity' => 1,
                'name' => 'Diskon',
            ];
        }

        $response = Http::withBasicAuth(config('services.midtrans.server_key'), '')
            ->acceptJson()
            ->asJson()
            ->post($this->snapEndpoint(), $payload);

        if (! $response->successful()) {
            Log::error('Midtrans create Snap transaction failed', [
                'order_id' => $order->order_id,
                'status' => $response->status(),
                'body' => $response->json() ?: $response->body(),
            ]);

            throw new RuntimeException('Gagal membuat pembayaran Midtrans. Cek Server Key dan environment Midtrans kamu.');
        }

        $body = $response->json();

        return [
            'payment_reference' => $body['token'] ?? $order->order_id,
            'payment_url' => $body['redirect_url'] ?? route('invoice.show', $order->invoice_number),
            'qr_code' => null,
        ];
    }

    public function verifySignature(array $payload): bool
    {
        $serverKey = config('services.midtrans.server_key');

        if (! $serverKey) {
            return false;
        }

        foreach (['order_id', 'status_code', 'gross_amount', 'signature_key'] as $key) {
            if (! isset($payload[$key])) {
                return false;
            }
        }

        $signature = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$serverKey);

        return hash_equals($signature, $payload['signature_key']);
    }

    protected function snapEndpoint(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    protected function normalizePhone(?string $phone): string
    {
        $phone = preg_replace('/\D+/', '', (string) $phone);

        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        return $phone;
    }
}
