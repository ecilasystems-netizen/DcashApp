<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="mail-check" class="w-12 h-12 text-blue-400"></i>
            </div>

            <h1 class="text-3xl font-bold text-white">Check Your Email</h1>
            <p class="text-gray-400 mt-2">We've sent a password reset link to:</p>
            <p class="font-semibold text-white my-2">j••••••••••s@email.com</p>
            <p class="text-xs text-gray-500">Please check your inbox and spam folder.</p>

            <!-- Action Button -->
            <div class="mt-8">
                <a href="{{route('login')}}" class="brand-gradient w-full inline-block text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                    Return to Login
                </a>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-400">
                    Didn't receive the email?
                    <a href="#" class="font-semibold text-[#E1B362] hover:underline">Resend link</a>
                </p>
            </div>
        </div>
    </div>
</div>
