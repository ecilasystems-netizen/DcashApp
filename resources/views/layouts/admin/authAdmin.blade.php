<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Default Title' }} - Dcash Wallet</title>
    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>

<body class="bg-gray-900 text-gray-300">

{{$slot}}
@stack('scripts')
@livewireScripts
</body>
</html>
