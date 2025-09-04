<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <x-logo class="w-[80px] h-[80px] mx-auto mb-4"/>
                <h1 class="text-3xl font-bold text-white">Reset Your Password</h1>
                <p class="text-gray-400">Enter your email to make a password reset.</p>
            </div>

            <!-- Reset Form -->
            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                <form wire:submit.prevent="continue" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input type="email" id="email" wire:model.defer="email" placeholder="you@example.com"
                                   class="w-full bg-gray-800/2 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] @error('email') border-red-500 @enderror">
                        </div>
                        @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="continue"
                            class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all flex items-center justify-center">
                        <svg wire:loading wire:target="continue" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="continue">Continue</span>
                        <span wire:loading wire:target="continue">Please wait...</span>
                    </button>
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
