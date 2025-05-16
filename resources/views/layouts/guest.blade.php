<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Andriani - Promomedia</title>
        <link rel="icon" sizes="57x57" href="https://www.andrianispa.com/wp-content/uploads/2022/04/favicon-150x150.webp" type="image/x-icon" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            input[type="text"],
            input[type="email"],
            input[type="password"],
            textarea,
            select {
                background-color: white !important;
                color: #1a202c; /* testo scuro per leggibilità */
            }
        </style>
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-image: url({{ asset('adminlte/dist/assets/img/background.jpg') }}); background-size: cover; background-position: center;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">    
                <img src="{{ asset('adminlte/dist/assets/img/favicon-150x150.webp') }}" alt="Logo" class="w-20 h-20 full center mx-auto mb-4">
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
