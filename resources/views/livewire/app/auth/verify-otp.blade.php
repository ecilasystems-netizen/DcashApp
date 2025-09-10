<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <x-logo class="w-[150px] h-[70px] mx-auto mb-4"/>
                <h1 class="text-3xl font-bold text-white">Enter OTP</h1>
                <p class="text-gray-400">An OTP has been sent to {{ session('password-reset-email') }}.</p>
            </div>

            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                @if (session()->has('success'))
                    <div
                        class="bg-green-500/10 text-green-400 text-sm p-3 rounded-lg mb-4">{{ session('success') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-500/10 text-red-400 text-sm p-3 rounded-lg mb-4">{{ session('error') }}</div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="space-y-6">
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-400 mb-2">One-Time Password</label>
                        <input type="text" id="otp" wire:model.defer="otp" placeholder="Enter 6-digit OTP"
                               class="w-full bg-gray-800/2 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] @error('otp') border-red-500 @enderror">
                        @error('otp') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all flex items-center justify-center">
                        <svg wire:loading wire:target="verifyOtp" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="verifyOtp">Verify OTP</span>
                        <span wire:loading wire:target="verifyOtp">Verifying...</span>
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-400">
                    Didn't receive the code?
                    <button wire:click="sendOtp" wire:loading.attr="disabled"
                            class="font-semibold text-[#E1B362] hover:underline">
                        Resend OTP
                    </button>
                </p>
            </div>
        </div>
    </div>
</div>
