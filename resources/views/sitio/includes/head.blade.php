<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- notificaciones --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="description" content="Guillermo Gutiérrez Salón - Servicios de belleza profesionales, agendamiento y tienda en línea.">
  {{-- NOTIFICACIONES PUSH --}}
        <meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
        <link rel="manifest" href="/manifest.json">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        {{-- NOTIFICACIONES PUSH --}}
  <title>Guillermo Gutiérrez Salón</title>

  {{-- Favicons --}}
  <link rel="icon" type="image/x-icon" href="{{ asset('sitio/img/favicons/favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('sitio/img/favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('sitio/img/favicons/favicon-16x16.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('sitio/img/favicons/apple-touch-icon.png') }}">

  <!-- Styles -->
  <link rel="stylesheet" href="{{asset('sitio/css/style.css')}}">
