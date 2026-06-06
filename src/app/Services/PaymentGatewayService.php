<?php
namespace App\Services;
use App\Models\Order;
class PaymentGatewayService
{
    public function createPayment(Order $order): array
    {
        // Development fallback. Ganti dengan Tripay/Midtrans/Xendit production API saat sudah punya API key.
        $paymentUrl = route('invoice.show', $order->invoice_number);
        $qrSvg = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($order->invoice_number.'|'.$order->total);
        return ['reference'=>$order->order_id, 'payment_url'=>$paymentUrl, 'qr_code'=>$qrSvg];
    }
}
