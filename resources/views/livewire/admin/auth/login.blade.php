<div>
    <div class="flex min-h-screen">
        <!-- Left Side: Branding -->
        <div class="hidden lg:flex flex-1 bg-gray-800 items-center justify-center p-12">
            <div class="max-w-md text-center">
                <img src="/imgs/logo-only.png" alt="Dcash Logo" class="w-24 h-24 mx-auto mb-6"/>
                <h1 class="text-4xl font-bold text-white">Admin Control Panel</h1>
                <p class="text-gray-400 mt-4">
                    Secure access for authorized personnel only. Please manage the platform with
                    care and responsibility.
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
            <div class="w-full max-w-sm">
                <div class="lg:hidden text-center mb-8">
                    <img src="/imgs/logo-only.png" alt="Dcash Logo" class="w-16 h-16 mx-auto mb-4"/>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Admin Login</h2>
                <p class="text-gray-400 mb-8">
                    Enter your credentials to access the dashboard.
                </p>

                @if($loginError)
                    <div class="bg-red-500 bg-opacity-20 text-white px-4 py-3 rounded mb-6">
                        {{ $loginError }}
                    </div>
                @endif

                <form class="space-y-6" wire:submit="login">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                placeholder="admin@example.com"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                        </div>
                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <i data-lucide="lock"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input
                                type="password"
                                id="password"
                                wire:model="password"
                                placeholder="••••••••"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                        </div>
                        @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-400">
                            <input
                                type="checkbox"
                                wire:model="remember"
                                class="h-4 w-4 rounded border-gray-600 bg-gray-700 text-[#E1B362] focus:ring-[#E1B362]"/>
                            <span class="ml-2">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-[#E1B362] hover:underline">Forgot Password?</a>
                    </div>
                    <div>
                        <button
                            type="submit"
                            class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                            Login Securely
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
