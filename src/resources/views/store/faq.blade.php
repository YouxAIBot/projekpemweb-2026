@extends('store.layout')
@section('content')<section class="page-head"><h1>FAQ</h1><p>Pertanyaan yang sering ditanyakan.</p></section>@foreach($faqs as $cat=>$items)<h2>{{ $cat }}</h2><div class="faq-list">@foreach($items as $faq)<details><summary>{{ $faq->question }}</summary><p>{{ $faq->answer }}</p></details>@endforeach</div>@endforeach@endsection
