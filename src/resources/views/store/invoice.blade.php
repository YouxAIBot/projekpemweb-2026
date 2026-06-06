@extends('store.layout')

@section('content')
@php
    $steps = [
        ['key' => 'unpaid', 'label' => 'Belum Bayar', 'desc' => 'Menunggu pembayaran'],
        ['key' => 'processing', 'label' => 'Proses', 'desc' => 'Pembayaran diterima'],
        ['key' => 'success', 'label' => 'Sukses', 'desc' => 'Pesanan selesai'],
    ];

    $activeIndex = match ($order->status) {
        'processing' => 1,
        'success' => 2,
        default => 0,
    };
@endphp

<section class="invoice-card clean-invoice">
    <div class="invoice-top">
        <div>
            <p>Invoice Pembayaran</p>
            <h1>{{ $order->invoice_number }}</h1>
            <small>Order ID: {{ $order->order_id }}</small>
        </div>
        <span class="status {{ $order->status }}">
            {{ $order->status === 'unpaid' ? 'BELUM BAYAR' : strtoupper($order->status) }}
        </span>
    </div>

    <div class="process-flow">
        @foreach($steps as $index => $step)
            <div class="process-step {{ $index <= $activeIndex ? 'is-active' : '' }} {{ $index === $activeIndex ? 'is-current' : '' }}">
                <div class="dot">{{ $index + 1 }}</div>
                <div>
                    <b>{{ $step['label'] }}</b>
                    <small>{{ $step['desc'] }}</small>
                </div>
            </div>
        @endforeach
    </div>

    @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
    @endif

    <div class="invoice-grid">
        <div class="detail-box">
            <h2>{{ $order->product->name }}</h2>
            <p>{{ $order->game->name }}</p>
            <dl>
                <dt>Data Akun</dt>
                <dd>{{ $order->topup_type === 'uid' ? $order->user_identifier.' | '.$order->server : $order->game_email }}</dd>

                <dt>WhatsApp</dt>
                <dd>{{ $order->whatsapp }}</dd>

                <dt>Metode Pembayaran</dt>
                <dd>{{ $order->paymentMethod->name }}</dd>

                <dt>Subtotal</dt>
                <dd>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>

                <dt>Biaya Admin</dt>
                <dd>Rp {{ number_format($order->fee, 0, ',', '.') }}</dd>

                <dt>Total Bayar</dt>
                <dd><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></dd>
            </dl>
        </div>

        <div class="payment-box">
            @if($order->status === 'unpaid')
                <div class="pay-icon">💳</div>
                <h2>Selesaikan Pembayaran</h2>
                <p>Klik tombol di bawah untuk masuk ke halaman pembayaran Midtrans. Setelah pembayaran berhasil, status akan berubah otomatis via webhook.</p>
                <a class="btn wide" href="{{ $order->payment_url ?: '#' }}">Bayar Sekarang</a>
                <small class="muted">Tidak ada tombol simulasi. Status mengikuti notifikasi resmi Midtrans.</small>
            @elseif($order->status === 'processing')
                <div class="pay-icon">⚙️</div>
                <h2>Pembayaran Diterima</h2>
                <p>Pesanan kamu sedang diproses. Notifikasi WhatsApp proses akan dikirim otomatis.</p>
            @else
                <div class="pay-icon">✅</div>
                <h2>Pesanan Sukses</h2>
                <p>Top up selesai. Detail transaksi sukses dikirim ke WhatsApp customer.</p>
            @endif
        </div>
    </div>
</section>
@endsection
