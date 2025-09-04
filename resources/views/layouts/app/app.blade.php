<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dcash Wallet' }} - Dcash Wallet</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-bottom: 80px; /* Add padding to prevent footer from overlapping content on mobile */
        }

        .glassmorphism {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(75, 85, 99, 0.5);
        }

        .brand-gradient {
            background: linear-gradient(135deg, #E1B362 0%, #D4A55A 100%);
        }

        .brand-gradient-subtle-dark {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
        }

        .brand-gradient-purple {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
        }


        .brand-gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .brand-border {
            border: 1px solid rgba(225, 179, 98, 0.2);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }
    </style>
    <link rel="manifest" href="/public/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-black text-gray-300">
{{--<div id="page-loader" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50">--}}
{{--    <div class="bg-gray-900 p-4 rounded-lg shadow-lg">--}}
{{--        <div class="flex items-center space-x-2">--}}
{{--            <div class="animate-spin rounded-full h-12 w-12 "></div>--}}
{{--            <img src="{{ asset('/loading.gif') }}" alt="Loading" class="h-12 w-12">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.app.partials.sidebar')

    <main class="w-full lg:ml-20">
        <div class="lg:max-w-none lg:w-4/5 lg:mx-auto lg:px-8">
            {{-- Header --}}
            @if (isset($header))
                {{ $header }}
            @endif

            {{-- Page Content --}}
            <div class="p-4 lg:p-0 lg:py-8">
                {{$slot}}
            </div>

            {{-- Footer (for mobile) --}}
            @include('layouts.app.partials.footer')
        </div>
    </main>
</div>


@if (isset($scripts))
    {{ $scripts }}
@endif
@stack('scripts')
@livewireScripts

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/service-worker.js")
            .then(reg => console.log("Service Worker registered:", reg))
            .catch(err => console.log("SW registration failed:", err));
    }
</script>

<script>
    // Show loader for all links and form submissions
    document.addEventListener('DOMContentLoaded', function () {
        const loader = document.getElementById('page-loader');

        // Show loader on link clicks
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('#') && !link.hasAttribute('target')) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }
        });

        // Show loader on form submissions
        document.addEventListener('submit', function (e) {
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        });

        // Hide loader when page loads
        window.addEventListener('load', function () {
            loader.classList.add('hidden');
            loader.classList.remove('flex');
        });
    });
</script>

<script type="text/javascript" id="zohodeskasap">var d = document;
    s = d.createElement("script"), s.type = "text/javascript", s.id = "zohodeskasapscript", s.defer = !0, s.nonce = "{place_your_nonce_value_here}", s.src = "https://desk.zoho.com/portal/api/web/asapApp/1177161000000371017?orgId=898626392", t = d.getElementsByTagName("script")[0], t.parentNode.insertBefore(s, t), window.ZohoDeskAsapReady = function (s) {
        var e = window.ZohoDeskAsap__asyncalls = window.ZohoDeskAsap__asyncalls || [];
        window.ZohoDeskAsapReadyStatus ? (s && e.push(s), e.forEach(s => s && s()), window.ZohoDeskAsap__asyncalls = null) : s && e.push(s)
    };</script>

<script>window.$zoho = window.$zoho || {};
    $zoho.salesiq = $zoho.salesiq || {
        ready: function () {
        }
    }</script>
<script id="zsiqscript"
        src="https://salesiq.zoho.com/widget?wc=siqeaa0cc165ffea61546e760ef13ff15a5f76ff8a20c7dc8e903d6e8386b023f98"
        defer></script>

</body>
</html>
