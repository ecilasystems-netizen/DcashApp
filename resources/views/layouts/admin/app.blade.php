<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dcash APP Admin' }} - Dcash Wallet</title>

    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
        }

        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1f2937; /* bg-gray-800 */
        }

        ::-webkit-scrollbar-thumb {
            background: #4b5563; /* bg-gray-600 */
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280; /* bg-gray-500 */
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-verified,
        .status-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .status-suspended,
        .status-failed {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-blocked {
            background-color: rgba(55, 65, 81, 0.2);
            color: #9ca3af;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-gray-900 text-gray-300">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.admin.partials.sidebar')

    {{-- Main Content --}}

    <main class="flex-1">
        {{-- Header --}}
        @if (isset($header))
            {{ $header }}
        @endif

        {{-- Page Content --}}
        <div>
            {{$slot}}
        </div>

    </main>

    @if (isset($footer))
        {{ $footer }}
    @endif

    @if (isset($modals))
        {{ $modals }}
    @endif

</div>


@if (isset($scripts))
    {{ $scripts }}
@endif
@stack('scripts')
@livewireScripts

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
