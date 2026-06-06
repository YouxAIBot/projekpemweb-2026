# YouxTopUp

Project ini sudah ditambahkan modul web top up dinamis:

- Frontend dark-blue modern: home, popup iklan, slider, game list, checkout, invoice, cek transaksi, FAQ, support, leaderboard login-only.
- Backend/admin Filament: Game, Product/Nominal, Banner, Popup, Payment Method, Order, FAQ, Support Ticket.
- Database migration dan seeder sample.
- WhatsApp notification service via Fonnte fallback log.
- Payment gateway service placeholder yang siap diganti Tripay/Midtrans/Xendit.

## Cara jalanin

```bash
cd src
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```

Atau kalau pakai Docker dari root project:

```bash
docker compose up -d
```

Lalu masuk container PHP dan jalankan migrate/seed sesuai setup boilerplate.

## URL penting

- `/` halaman utama YouxTopUp
- `/games` semua game
- `/transactions` cek transaksi
- `/support` form dukungan
- `/faq` FAQ
- `/leaderboard` wajib login
- `/admin` panel admin Filament

## API

Untuk sekarang payment masih mode simulasi supaya flow bisa dites tanpa API key. File yang nanti diganti:

- `app/Services/PaymentGatewayService.php`
- `app/Services/WhatsAppService.php`

Isi `.env` untuk WhatsApp:

```env
FONNTE_TOKEN=token_kamu
```

## Midtrans Real Payment Flow

Simulasi pembayaran sudah dihapus. Alur sekarang:

1. User checkout dan klik Bayar Sekarang.
2. User diarahkan ke halaman pembayaran Midtrans Snap.
3. Midtrans mengirim webhook ke aplikasi.
4. Status order berubah otomatis:
   - `unpaid` = belum bayar / masih pending
   - `processing` = pembayaran diterima
   - `success` = pesanan selesai
5. WhatsApp dikirim otomatis saat status processing dan success.

Tambahkan ke `src/.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_AUTO_COMPLETE_ORDER=true
FONNTE_TOKEN=token_fonnte_kamu
```

Set Payment Notification URL di dashboard Midtrans:

```txt
https://projekpemweb.test/payment/midtrans/notification
```

Untuk local testing webhook dari Midtrans, domain lokal harus bisa diakses publik. Pakai ngrok/cloudflared, lalu set URL notifikasi ke URL publik tersebut, contoh:

```txt
https://xxxx.ngrok-free.app/payment/midtrans/notification
```
