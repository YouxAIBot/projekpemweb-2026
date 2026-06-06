<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Services\WhatsAppService;
class InvoiceController extends Controller
{
    public function show(string $invoice) { $order=Order::with(['game','product','paymentMethod'])->where('invoice_number',$invoice)->orWhere('order_id',$invoice)->firstOrFail(); return view('store.invoice', compact('order')); }
    public function simulateSuccess(Order $order, WhatsAppService $wa) { $order->update(['status'=>Order::STATUS_SUCCESS,'paid_at'=>now(),'completed_at'=>now()]); $wa->send($order->whatsapp, "Pesanan YouxTopUp berhasil!\nOrder: {$order->order_id}\nProduk: {$order->game->name} - {$order->product->name}\nTotal: Rp ".number_format($order->total,0,',','.')); return back()->with('success','Status diubah menjadi sukses.'); }
}
