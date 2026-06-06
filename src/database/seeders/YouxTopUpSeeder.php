<?php

namespace Database\Seeders;

use App\Models\{Banner,Faq,Game,PaymentMethod,PopupAd,Product};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class YouxTopUpSeeder extends Seeder
{
    public function run(): void
    {
        Banner::updateOrCreate(['title'=>'Top Up Game Favorit Tanpa Ribet'], ['subtitle'=>'Cepat, aman, dan invoice otomatis untuk semua pesanan.', 'button_text'=>'Mulai Top Up', 'url'=>'/games', 'is_active'=>true]);
        Banner::updateOrCreate(['title'=>'Promo Diamond & Robux Mingguan'], ['subtitle'=>'Nikmati harga spesial dan poin reward.', 'button_text'=>'Cek Promo', 'url'=>'/games', 'is_active'=>true, 'sort_order'=>2]);
        PopupAd::updateOrCreate(['title'=>'Welcome to YouxTopUp'], ['is_active'=>true, 'url'=>'/games']);

        $games = [
            ['Mobile Legends Indonesia','mobile-legends-indonesia','Diamond MLBB','uid',true],
            ['Roblox','roblox','Robux','uid',true],
            ['Genshin Impact','genshin-impact','Genesis Crystal & Welkin','uid',true],
            ['Honkai Star Rail','honkai-star-rail','Oneiric Shard','uid',true],
            ['Wuthering Waves','wuthering-waves','Lunite & Subscription','uid',true],
            ['Valorant Indonesia','valorant-indonesia','Valorant Points','login',true],
            ['Free Fire','free-fire','Diamond FF','uid',true],
            ['Zenless Zone Zero','zenless-zone-zero','Monochrome','uid',true],
        ];

        foreach ($games as $index => [$name,$slug,$desc,$type,$popular]) {
            $game = Game::updateOrCreate(['slug'=>$slug], ['name'=>$name,'short_description'=>$desc,'type'=>$type,'is_popular'=>$popular,'is_active'=>true,'sort_order'=>$index+1,'description'=>$name.' tersedia di YouxTopUp dengan proses cepat dan invoice otomatis.']);
            foreach ([['3 Diamonds',1056,1],['28 Diamonds',8500,2],['86 Diamonds',25000,5],['172 Diamonds',49500,10],['257 Diamonds',73500,14],['344 Diamonds',98000,18]] as $sort=>$p) {
                Product::updateOrCreate(['game_id'=>$game->id,'name'=>$p[0]], ['price'=>$p[1], 'points'=>$p[2], 'is_active'=>true, 'sort_order'=>$sort+1]);
            }
        }

        foreach ([['QRIS All Bank','QRIS','QRIS'],['DANA','DANA','E-Wallet'],['OVO','OVO','E-Wallet'],['ShopeePay','SPAY','E-Wallet'],['BCA Virtual Account','BCA','Virtual Account'],['Alfamart','ALFAMART','Retail']] as $i=>$p) {
            PaymentMethod::updateOrCreate(['code'=>$p[1]], ['name'=>$p[0], 'category'=>$p[2], 'fee_type'=>'flat','fee_value'=>0,'is_active'=>true,'sort_order'=>$i+1]);
        }

        foreach ([
            ['Tentang','Apa itu YouxTopUp?','YouxTopUp adalah platform top up game dinamis dengan invoice dan pelacakan pesanan.'],
            ['Pembayaran','Kenapa status belum bayar?','Status berubah setelah pembayaran diterima oleh gateway pembayaran.'],
            ['Pesanan','Berapa lama pesanan diproses?','Umumnya otomatis, tetapi beberapa layanan dapat diproses manual oleh admin.'],
        ] as $i=>$faq) {
            Faq::updateOrCreate(['question'=>$faq[1]], ['category'=>$faq[0], 'answer'=>$faq[2], 'is_active'=>true, 'sort_order'=>$i+1]);
        }
    }
}
