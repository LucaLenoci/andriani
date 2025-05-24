<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Andriani - Promomedia</title>
    <link rel="icon" sizes="57x57" href="https://www.andrianispa.com/wp-content/uploads/2022/04/favicon-150x150.webp" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            background-color: white !important;
            color: #1a202c; /* testo scuro per leggibilità */
        }

        body {
            background-image: url('{{ asset('adminlte/dist/assets/img/background.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Optional Bootstrap JS (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="text-dark">

    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5">
        <div class="w-100" style="max-width: 400px;">
            <div class="mt-4 px-4 py-4 bg-white shadow rounded">
                <div class="text-center mb-3">
                    <img src="{{ asset('adminlte/dist/assets/img/favicon-150x150.webp') }}" alt="Logo" class="img-fluid" style="width: 80px; height: 80px;">
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
