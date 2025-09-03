<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8 mb-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="/imgs/logo-only.png" alt="Dcash Logo" class="w-16 h-16 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white">Create Your Account</h1>
                <p class="text-gray-400">Join us to start exchanging currency with ease.</p>
            </div>

            <!-- Registration Form -->
            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                <form wire:submit="register" class="space-y-6">
                    <div>
                        <label for="fname" class="block text-sm font-medium text-gray-400 mb-2">First Name</label>
                        <div class="relative">
                            <i data-lucide="user"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="fname" type="text" id="fname" placeholder="John"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('fname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="lname" class="block text-sm font-medium text-gray-400 mb-2">Last Name</label>
                        <div class="relative">
                            <i data-lucide="user"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="lname" type="text" id="lname" placeholder="Doe"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('lname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Phone</label>
                        <div class="relative">
                            <i data-lucide="phone"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="phone" type="tel" id="phone" placeholder="034 801 234 223"
                                   pattern="[0-9\s]*" inputmode="numeric"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                   oninput="this.value = this.value.replace(/[^0-9\s]/g, '')">
                        </div>
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="email" type="email" id="email" placeholder="you@example.com"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <i data-lucide="lock"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="password" type="password" id="password" placeholder="••••••••"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-2">Confirm
                            Password</label>
                        <div class="relative">
                            <i data-lucide="lock"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                   placeholder="••••••••"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                    </div>


                    <div>
                        <label for="pin" class="block text-sm font-medium text-gray-400 mb-2">4-Digit PIN</label>
                        <div class="relative">
                            <i data-lucide="key"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input wire:model="pin" type="password" id="pin" placeholder="••••" maxlength="4"
                                   class="w-full bg-gray-800/2 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                        @error('pin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="text-xs text-gray-500">
                        By registering, you agree to our <a href="#" class="text-[#E1B362] hover:underline">Terms of
                            Service</a> and <a href="#" class="text-[#E1B362] hover:underline">Privacy Policy</a>.
                    </div>

                    <div class="space-y-2">
                        <button type="submit" wire:loading.attr="disabled"
                                class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span>Create Account</span>
                        </button>

                        <div wire:loading wire:target="register"
                             class="flex items-center justify-center text-gray-400 text-sm">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i>
                            Creating Account...
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-400">
                    Already have an account?
                    <a href="{{route('login')}}" class="font-semibold text-[#E1B362] hover:underline">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
