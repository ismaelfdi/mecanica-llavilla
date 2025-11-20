<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/background_image.jpeg') }}');">
        <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
    </div>

    <div class="relative z-20 mb-6">
        <a href="/">
            <img src="{{ asset('images/logo_transparente.png') }}" alt="Mecánica Llavilla Logo" class="h-24 w-auto fill-current text-gray-500" />
        </a>
    </div>

    <div class="relative z-10 w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
</body>
</html>
