<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md text-center">
            <div
                class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 success-animation">
                <i data-lucide="check-check" class="w-12 h-12 text-green-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
            <p class="text-gray-400 mt-2">{{ $message }}</p>
            @if($redirectTo && $redirectAfter)
                <p class="text-gray-400 mt-4">You are now being redirected...</p>
                <script>
                    setTimeout(function () {
                        window.location.href = @js($redirectTo);
                    }, {{ $redirectAfter * 1000 }});
                </script>
            @endif
            <div class="mt-8">
                <a href="{{ $redirectTo ?? route('dashboard') }}"
                   class="brand-gradient w-full inline-block text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                    Continue
                </a>
            </div>
        </div>
    </div>
</div>
