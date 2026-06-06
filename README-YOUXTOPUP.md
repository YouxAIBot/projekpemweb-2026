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
