<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Default Title' }} - Dcash Wallet</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #E1B362 0%, #D4A55A 100%);
        }

        .success-animation {
            animation: scale-in 0.5s ease-out forwards;
        }

        @keyframes scale-in {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>

<body class="bg-gray-950 text-gray-300">

{{$slot}}
@stack('scripts')
@livewireScripts
</body>
</html>
