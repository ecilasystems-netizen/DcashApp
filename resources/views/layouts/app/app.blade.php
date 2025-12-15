<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    {{--    PWA settings--}}
    <link rel="manifest" href="/public/manifest.json">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Update this section in resources/views/layouts/auth/app.blade.php -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="DCash">

    <!-- iOS icons -->
    <link rel="apple-touch-icon" sizes="152x152" href="/public/storage/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/public/storage/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/public/storage/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/public/storage/icons/icon-192x192.png">


    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 440px) and (device-height: 956px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_17_Pro_Max__iPhone_16_Pro_Max_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 402px) and (device-height: 874px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_17_Pro__iPhone_17__iPhone_16_Pro_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_17_Air__iPhone_16_Plus__iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_16__iPhone_15_Pro__iPhone_15__iPhone_14_Pro_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_16e__iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_11__iPhone_XR_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 1032px) and (device-height: 1376px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/13__iPad_Pro_M4_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/12.9__iPad_Pro_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 834px) and (device-height: 1210px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/11__iPad_Pro_M4_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/11__iPad_Pro__10.5__iPad_Pro_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/10.9__iPad_Air_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/10.5__iPad_Air_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/10.2__iPad_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"
          href="/public/storage/splash_screens/8.3__iPad_Mini_landscape.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 440px) and (device-height: 956px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_17_Pro_Max__iPhone_16_Pro_Max_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 402px) and (device-height: 874px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_17_Pro__iPhone_17__iPhone_16_Pro_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_17_Air__iPhone_16_Plus__iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_16__iPhone_15_Pro__iPhone_15__iPhone_14_Pro_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_16e__iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_11__iPhone_XR_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 1032px) and (device-height: 1376px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/13__iPad_Pro_M4_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/12.9__iPad_Pro_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 834px) and (device-height: 1210px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/11__iPad_Pro_M4_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/11__iPad_Pro__10.5__iPad_Pro_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/10.9__iPad_Air_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/10.5__iPad_Air_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/10.2__iPad_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_portrait.png">
    <link rel="apple-touch-startup-image"
          media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"
          href="/public/storage/splash_screens/8.3__iPad_Mini_portrait.png">


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

        .woot-widget-bubble.woot-elements--right {
            right: 20px;
            margin-bottom: 70px;
            margin-right: -20px;
        }
    </style>

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


<script type="text/javascript">
    // Zoho SalesIQ configuration
    window.$zoho = window.$zoho || {};
    $zoho.salesiq = $zoho.salesiq || {
        widgetcode: "siq51f05af08ede1ce8f9d01bd2e895d43046f039e0d2129498c89fa3fb2251ab81",
        values: {},
        ready: function () {
            // Hide the default Zoho floating chat button
            $zoho.salesiq.floatbutton.visible('hide');

            // ✅ Suppress Zoho’s cookie consent popup (you take responsibility for compliance)
            if ($zoho.salesiq.set === undefined) {
                $zoho.salesiq.set = {};
            }
            $zoho.salesiq.set.cookieconsent = "accepted";

            // Find your custom buttons
            const chatButton = document.getElementById('openZohoChat');
            const chatButtonDesktop = document.getElementById('openZohoChatDesktop');

            // Add click event listeners to your custom buttons
            [chatButton, chatButtonDesktop].forEach(btn => {
                if (btn) {
                    btn.addEventListener('click', function () {
                        // API call to open the chat window
                        $zoho.salesiq.floatwindow.visible('show');
                    });
                }
            });
        }
    };


</script>
{{-- The main Zoho SalesIQ script --}}
<script id="zsiqscript"
        src="https://salesiq.zoho.com/widget?wc=siq51f05af08ede1ce8f9d01bd2e895d43046f039e0d2129498c89fa3fb2251ab81"
        defer></script>

@if (isset($scripts))
    {{ $scripts }}
@endif
@stack('scripts')
@livewireScripts

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Add to your layout:
    document.addEventListener('livewire:load', function () {
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        Livewire.on('newNotification', notification => {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(notification.title, {
                    body: notification.message,
                    icon: '/favicon.ico'
                });
            }
        });
    });
</script>


<script>
(function (d, t) {
    var BASE_URL = "https://app.hoory.com";
    var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
    g.src = BASE_URL + "/packs/js/sdk.js";
    g.defer = true;
    g.async = true;
    s.parentNode.insertBefore(g, s);
    g.onload = function () {
        window.hoorySDK.run({
            websiteToken: 'Tndv2XnLrKXcSmrnJRRScnA6',
            baseUrl: BASE_URL
        });
    }
})(document, "script");
</script>

</body>
</html>
