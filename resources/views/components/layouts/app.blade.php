<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Ecommerce Store' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-slate-800">

<livewire:navbar />

<main class="py-10">
    {{ $slot }}
</main>

<footer class="bg-gray-800 text-white py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4 text-center">
        &copy; {{ date('Y') }} MyStore. All rights reserved.
    </div>
</footer>

</body>
</html>
