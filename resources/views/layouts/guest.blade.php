<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Andriani - Promomedia</title>
    <link rel="icon" sizes="57x57" href="https://www.andrianispa.com/wp-content/uploads/2022/04/favicon-150x150.webp" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            font-family: 'Figtree', sans-serif;
            background-color: #004750; /* sfondo verde andriani */
            color: #1a202c;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            background-color: white !important;
            color: #1a202c;
        }

        /* Container for bubbles */
        .bubble-container {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }

        /* Bubble style */
        .bubble {
            position: absolute;
            bottom: -100px;
            background: #71A850; /* verde più intenso */
            border-radius: 50%;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
            filter: drop-shadow(0 0 5px #71A850);
        }

        /* Different bubble sizes and animation durations */
        .bubble.small {
            width: 20px;
            height: 20px;
            animation-name: rise1;
            animation-duration: 10s;
        }

        .bubble.medium {
            width: 35px;
            height: 35px;
            animation-name: rise2;
            animation-duration: 20s;
        }

        .bubble.large {
            width: 50px;
            height: 50px;
            animation-name: rise3;
            animation-duration: 30s;
        }

        @keyframes rise1 {
            0% {
                transform: translateX(0) translateY(0) scale(1);
                opacity: 0.5;
            }
            50% {
                transform: translateX(30px) translateY(-50vh) scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: translateX(-30px) translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        @keyframes rise2 {
            0% {
                transform: translateX(0) translateY(0) scale(1);
                opacity: 0.4;
            }
            50% {
                transform: translateX(-40px) translateY(-60vh) scale(1.05);
                opacity: 0.7;
            }
            100% {
                transform: translateX(40px) translateY(-120vh) scale(1);
                opacity: 0;
            }
        }

        @keyframes rise3 {
            0% {
                transform: translateX(0) translateY(0) scale(1);
                opacity: 0.6;
            }
            50% {
                transform: translateX(20px) translateY(-40vh) scale(1.15);
                opacity: 0.9;
            }
            100% {
                transform: translateX(-20px) translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        /* Slogan */
        .slogan {
    position: absolute;
    top: 30px;
    width: 100%;
    text-align: center;
    font-style: italic;
    font-weight: 700;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1;

    font-size: clamp(2.5rem, 6vw, 6rem);
}


        :root {
            --bs-primary: #255459 !important;
            --bs-primary-rgb: 37, 84, 89 !important;
        }

        .bg-primary,
        .btn-primary {
            background-color: #255459 !important;
            border-color: #255459 !important;
        }

        .pagination .page-item.active .page-link {
            background-color: #255459;
            border-color: #255459;
            color: #fff;
        }

        .pagination .page-link {
            color: #255459;
            border-radius: 0.25rem;
        }

        .pagination .page-link:hover {
            background-color: #1e4448;
            color: #fff;
        }
    </style>

    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="text-dark">

    <div class="bubble-container" aria-hidden="true">
        <div class="bubble small" style="left: 10%; animation-delay: 0s;"></div>
        <div class="bubble medium" style="left: 25%; animation-delay: 5s;"></div>
        <div class="bubble large" style="left: 40%; animation-delay: 2s;"></div>
        <div class="bubble small" style="left: 55%; animation-delay: 7s;"></div>
        <div class="bubble medium" style="left: 70%; animation-delay: 3s;"></div>
        <div class="bubble large" style="left: 85%; animation-delay: 6s;"></div>
        <div class="bubble small" style="left: 15%; animation-delay: 4s;"></div>
        <div class="bubble medium" style="left: 35%; animation-delay: 1s;"></div>
        <div class="bubble large" style="left: 60%; animation-delay: 8s;"></div>
        <div class="bubble small" style="left: 80%; animation-delay: 9s;"></div>
    </div>

    <div class="slogan">Leading the Food Transition</div>

    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5">
        <div class="w-100 px-3" style="max-width: 400px;">
            <div class="mt-4 px-4 py-4 bg-white shadow rounded">
                <div class="text-center mb-3">
                    <img src="{{ asset('adminlte/dist/assets/img/favicon-150x150.webp') }}" alt="Logo" class="img-fluid" style="width: 80px; height: 80px;" />
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
