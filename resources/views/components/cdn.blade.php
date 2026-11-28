{{-- Pustaka pihak ketiga dengan versi terkunci + SRI dari config/cdn.php (SEC-12). Contoh: <x-cdn name="swiper-js" defer /> --}}
@props(['name'])
@php
    $lib = config('cdn.'.$name) ?? throw new InvalidArgumentException("Pustaka CDN [{$name}] tidak terdaftar di config/cdn.php");
    $src = isset($lib['local']) ? asset($lib['local']) : $lib['url'];
    $sri = isset($lib['integrity']) ? ' integrity="'.e($lib['integrity']).'" crossorigin="anonymous"' : '';
@endphp
@if ($lib['type'] === 'css')
<link rel="stylesheet" href="{{ $src }}"{!! $sri !!} {{ $attributes }}>
@else
<script src="{{ $src }}"{!! $sri !!} {{ $attributes }}></script>
@endif
