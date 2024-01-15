@extends('frontend::layouts.main')

@section('styles')
    {{-- <style>
    </style> --}}
@endsection

@section('content')
<div class="container">
    <div class="row">
        @if (session()->get('locale') == 'km')
            @include('frontend::pages.terms_km')
        @else
            @include('frontend::pages.terms_en')
        @endif
    </div>
</div>
@endsection
