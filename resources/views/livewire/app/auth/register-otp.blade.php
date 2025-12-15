<div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <x-logo class="w-[220px] h-[100px] mx-auto mb-4"/>
                <h1 class="text-3xl font-bold text-white">Verify Your Email</h1>
                <p class="text-gray-400">Enter the OTP sent to {{ $email }}</p>
            </div>

            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                <form wire:submit="verifyOtp" class="space-y-6">
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-400 mb-2">OTP Code</label>
                        <input wire:model="otp" type="text" id="otp" maxlength="6"
                               class="w-full bg-gray-800/2 border border-gray-700 rounded-lg px-4 py-3 text-white text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                               placeholder="000000">
                        @error('otp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="verifyOtp">Verify Email</span>
                        <span wire:loading wire:target="verifyOtp" class="inline-flex items-center justify-center">
                            <i data-lucide="loader-2" class="w-5 h-5 animate-spin mr-2"></i>
                            Verifying...
                        </span>
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <button wire:click="resendOtp" wire:loading.attr="disabled"
                            class="text-sm text-[#E1B362] hover:underline disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="resendOtp">Resend OTP</span>
                        <span wire:loading wire:target="resendOtp" class="inline-flex items-center">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i>
                            Sending...
                        </span>
                    </button>
                </div>

                <div x-data="{ show: false }"
                     x-show="show"
                     x-init="@this.on('otp-resent', () => { show = true; setTimeout(() => show = false, 3000) })"
                     x-transition
                     class="mt-3 text-center text-sm text-green-400"
                     style="display: none;">
                    OTP has been resent to your email
                </div>
            </div>
        </div>
    </div>
</div>
