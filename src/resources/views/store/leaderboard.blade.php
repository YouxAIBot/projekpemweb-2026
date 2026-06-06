@extends('store.layout')
@section('content')<section class="page-head trophy"><h1>Top 10 Pembelian Terbanyak</h1><p>Leaderboard hanya muncul setelah login.</p></section><div class="leaderboard">@foreach($orders as $i=>$row)<div class="rank"><b>#{{ $i+1 }}</b><span>{{ $row->buyer }}</span><strong>Rp {{ number_format($row->total,0,',','.') }}</strong></div>@endforeach</div>@endsection
