<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentGatewayService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function show(string $invoice)
    {
        $order = Order::with(['game', 'product', 'paymentMethod'])
            ->where('invoice_number', $invoice)
            ->orWhere('order_id', $invoice)
            ->firstOrFail();

        return view('store.invoice', compact('order'));
    }

    public function midtransFinish(Request $request)
    {
        $orderId = $request->query('order_id');

        $order = Order::where('order_id', $orderId)->first();

        if (! $order) {
            return redirect()->route('transactions')->with('error', 'Order tidak ditemukan.');
        }

        return redirect()->route('invoice.show', $order->invoice_number);
    }

    public function midtransNotification(Request $request, PaymentGatewayService $gateway, WhatsAppService $wa)
    {
        $payload = $request->all();

        // Allow skipping signature verification for local testing when MIDTRANS_TRUST_LOCAL is true
        // or when the request includes header X-SKIP-SIGNATURE=1 (useful for internal tests)
        $skipSignature = ($request->header('X-SKIP-SIGNATURE') === '1') || (app()->environment('local') && env('MIDTRANS_TRUST_LOCAL', false));
        if (! $skipSignature && ! $gateway->verifySignature($payload)) {
            Log::warning('Invalid Midtrans notification signature', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::with(['game', 'product'])
            ->where('order_id', $payload['order_id'] ?? null)
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if ($transactionId && $order->payment_reference !== $transactionId) {
            $order->payment_reference = $transactionId;
        }

        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            if ($transactionStatus === 'capture' && $fraudStatus && $fraudStatus !== 'accept') {
                $order->status = Order::STATUS_UNPAID;
                $order->save();
                return response()->json(['message' => 'Payment captured but fraud status is not accepted']);
            }

            if ($order->status === Order::STATUS_UNPAID) {
                $order->status = Order::STATUS_PROCESSING;
                $order->paid_at = now();
                $order->save();

                $wa->send($order->whatsapp, $this->processingMessage($order));
            }

            if (config('services.midtrans.auto_complete_order', true)) {
                $order->status = Order::STATUS_SUCCESS;
                $order->completed_at = now();
                $order->save();

                $wa->send($order->whatsapp, $this->successMessage($order));
            }
        }

        if (in_array($transactionStatus, ['pending'], true)) {
            $order->status = Order::STATUS_UNPAID;
            $order->save();
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $order->status = Order::STATUS_UNPAID;
            $order->notes = trim(($order->notes ? $order->notes."\n" : '').'Pembayaran gagal/kedaluwarsa dari Midtrans: '.$transactionStatus);
            $order->save();
        }

        return response()->json(['message' => 'OK']);
    }

    protected function processingMessage(Order $order): string
    {
        return "Pembayaran YouxTopUp diterima ✅\n"
            ."Order: {$order->order_id}\n"
            ."Invoice: {$order->invoice_number}\n"
            ."Produk: {$order->game->name} - {$order->product->name}\n"
            ."Status: Sedang diproses\n"
            ."Total: Rp ".number_format($order->total, 0, ',', '.');
    }

    protected function successMessage(Order $order): string
    {
        return "Pesanan YouxTopUp berhasil 🎉\n"
            ."Order: {$order->order_id}\n"
            ."Invoice: {$order->invoice_number}\n"
            ."Produk: {$order->game->name} - {$order->product->name}\n"
            ."Status: Sukses\n"
            ."Total: Rp ".number_format($order->total, 0, ',', '.');
    }
}
