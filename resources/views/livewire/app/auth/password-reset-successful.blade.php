<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 success-animation">
                <i data-lucide="check-check" class="w-12 h-12 text-green-400"></i>
            </div>

            <h1 class="text-3xl font-bold text-white">Password Reset Successful</h1>
            <p class="text-gray-400 mt-2">You can now use your new password to log in to your account.</p>

            <!-- Action Button -->
            <div class="mt-8">
                <a href="{{route('login')}}" class="brand-gradient w-full inline-block text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                    Proceed to Login
                </a>
            </div>
        </div>
    </div>
</div>
