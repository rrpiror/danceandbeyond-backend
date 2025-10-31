<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#000000">

    {{-- Tailwind / App CSS --}}
    @vite('resources/css/app.css')

    {{-- Icons / Fonts (optional) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          referrerpolicy="no-referrer" />

    {{-- Favicons --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">

    {{-- Extra per-page tags --}}
    @stack('head')
</head>
<body class="font-sans antialiased text-black">

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- App JS --}}
    @vite('resources/js/app.js')

    {{-- AlpineJS (remove if already in app.js) --}}
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js" defer></script>

    @stack('scripts')
</body>
</html>