<div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <x-logo class="w-[150px] h-[70px] mx-auto mb-4"/>
                <h1 class="text-3xl font-bold text-white">Welcome Back</h1>
                <p class="text-gray-400">Sign in to continue to your dashboard.</p>
            </div>

            @if(session('error'))
                <div
                    class="mb-4 flex items-center px-4 py-3 bg-red-600 text-white text-sm rounded-lg border-l-4 border-red-400 shadow font-semibold">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-white" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                <form wire:submit="login" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <span wire:ignore>
                                <i data-lucide="mail"
                                   class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            </span>
                            <input wire:model="email" type="email" id="email" placeholder="you@example.com"
                                   class="w-full bg-gray-800/2 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-400">Password</label>
                            <a href="{{route('reset-password')}}" class="text-sm text-[#E1B362] hover:underline">Forgot
                                Password?</a>
                        </div>
                        <div class="relative">
                            <span wire:ignore>
                                <i data-lucide="lock"
                                   class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            </span>
                            <input wire:model="password" type="password" id="password" placeholder="••••••••"
                                   class="w-full bg-gray-800/2 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center">
                        <input wire:model="remember" type="checkbox" id="remember"
                               class="rounded border-gray-700 text-[#E1B362] focus:ring-[#E1B362]">
                        <label for="remember" class="ml-2 text-sm text-gray-400">Remember me</label>
                    </div>
                    <div>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="login"
                                class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all flex items-center justify-center">
                            <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="login">Login</span>
                            <span wire:loading wire:target="login">Please wait...</span>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Footer Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-400">
                    Don't have an account?
                    <a href="{{route('register')}}" class="font-semibold text-[#E1B362] hover:underline">Register
                        here</a>
                </p>
            </div>
        </div>
    </div>
</div>

