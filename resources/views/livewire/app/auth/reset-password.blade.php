<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="/imgs/logo-only.png" alt="Dcash Logo" class="w-16 h-16 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white">Reset Your Password</h1>
                <p class="text-gray-400">Enter your email to make a password reset.</p>
            </div>

            <!-- Reset Form -->
            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                <form class="space-y-6" method="POST" action="{{ route('reset-password.check-email') }}">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input type="email" id="email" placeholder="you@example.com"
                                   class="w-full bg-gray-800/2 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                            Send Reset Link
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-400">
                    Remember your password?
                    <a href="{{route('login')}}" class="font-semibold text-[#E1B362] hover:underline">Return to
                        Login</a>
                </p>
            </div>
        </div>
    </div>

</div>
