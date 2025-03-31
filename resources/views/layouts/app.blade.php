<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen flex flex-col" data-logged-in="{{ auth()->check() ? 'true' : 'false' }}">
    <div class="flex flex-col flex-grow bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation') <!-- Header -->

        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow h-16 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main class="flex-grow">
            {{ $slot }}
        </main>

        @include('layouts.footer') <!-- Footer -->
    </div>
</body>

</html>