<?php
namespace App\Http\Controllers;
use App\Models\{Banner,Faq,Game,Order,PopupAd,SupportTicket};
use Illuminate\Http\Request;
class StorefrontController extends Controller
{
    public function home() { return view('store.home', ['banners'=>Banner::where('is_active',true)->orderBy('sort_order')->get(), 'popup'=>PopupAd::where('is_active',true)->latest()->first(), 'popularGames'=>Game::where('is_active',true)->where('is_popular',true)->orderBy('sort_order')->take(8)->get(), 'games'=>Game::where('is_active',true)->orderBy('sort_order')->take(18)->get()]); }
    public function games(Request $request) { $games=Game::where('is_active',true)->when($request->type, fn($q,$type)=>$q->where('type',$type))->orderBy('sort_order')->paginate(18); return view('store.games', compact('games')); }
    public function leaderboard() { $orders=Order::selectRaw('COALESCE(customer_name, whatsapp) as buyer, SUM(total) as total')->where('status','success')->groupBy('buyer')->orderByDesc('total')->take(10)->get(); return view('store.leaderboard', compact('orders')); }
    public function faq() { $faqs=Faq::where('is_active',true)->orderBy('sort_order')->get()->groupBy('category'); return view('store.faq', compact('faqs')); }
    public function support() { return view('store.support'); }
    public function submitSupport(Request $request) { $data=$request->validate(['topic'=>'required','type'=>'required','name'=>'required','whatsapp'=>'required','email'=>'nullable|email','message'=>'required']); $ticket=SupportTicket::create($data); return back()->with('success','Laporan berhasil dikirim. Nomor tiket: '.$ticket->ticket_number); }
    public function transactions(Request $request) { $orders=collect(); if($request->invoice){ $orders=Order::with(['game','product'])->where('invoice_number','like','%'.$request->invoice.'%')->orWhere('order_id','like','%'.$request->invoice.'%')->latest()->get(); } else { $orders=Order::with(['game','product'])->latest()->take(10)->get(); } return view('store.transactions', compact('orders')); }
}
