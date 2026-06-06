<?php
namespace App\Http\Controllers;
use App\Models\{Game,Order,PaymentMethod,Product};
use App\Services\{PaymentGatewayService,WhatsAppService};
use Illuminate\Http\Request;
class CheckoutController extends Controller
{
    public function show(Game $game) { $game->load('activeProducts'); $payments=PaymentMethod::where('is_active',true)->orderBy('sort_order')->get()->groupBy('category'); return view('store.checkout', compact('game','payments')); }
    public function store(Request $request, Game $game, PaymentGatewayService $gateway, WhatsAppService $wa)
    {
        $data=$request->validate(['product_id'=>'required|exists:products,id','payment_method_id'=>'required|exists:payment_methods,id','whatsapp'=>'required|string|max:30','customer_name'=>'nullable|string|max:100','user_identifier'=>'nullable|string|max:100','server'=>'nullable|string|max:100','game_email'=>'nullable|string|max:120','game_password'=>'nullable|string|max:120']);
        $product=Product::where('game_id',$game->id)->findOrFail($data['product_id']); $payment=PaymentMethod::findOrFail($data['payment_method_id']);
        if($game->type==='uid' && empty($data['user_identifier'])) return back()->withErrors(['user_identifier'=>'UID wajib diisi.'])->withInput();
        if($game->type==='login' && (empty($data['game_email']) || empty($data['game_password']))) return back()->withErrors(['game_email'=>'Email dan password game wajib diisi.'])->withInput();
        $fee=$payment->fee_type==='percent' ? (int) ceil($product->price * $payment->fee_value / 100) : $payment->fee_value;
        $order=Order::create([...$data,'user_id'=>auth()->id(),'game_id'=>$game->id,'product_id'=>$product->id,'payment_method_id'=>$payment->id,'topup_type'=>$game->type,'subtotal'=>$product->price,'fee'=>$fee,'discount'=>0,'total'=>$product->price+$fee,'status'=>Order::STATUS_UNPAID]);
        try { $pay=$gateway->createPayment($order); } catch (\Throwable $e) { report($e); return back()->withErrors(['payment_method_id'=>$e->getMessage()])->withInput(); } $order->update($pay);
        $wa->send($order->whatsapp, "Invoice YouxTopUp\nOrder: {$order->order_id}\nInvoice: {$order->invoice_number}\nProduk: {$game->name} - {$product->name}\nTotal: Rp ".number_format($order->total,0,',','.')."\nLink: ".route('invoice.show',$order->invoice_number));
        return redirect()->route('invoice.show',$order->invoice_number);
    }
}
