<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <x-logo class="w-[220px] h-[100px] mx-auto mb-4"/>
                <h1 class="text-3xl font-bold text-white">Set New Password</h1>
                <p class="text-gray-400">Create a new, strong password for your account.</p>
            </div>

            <div class="bg-gray-800/2 border border-gray-800 rounded-lg p-5 shadow-lg">
                @if (session()->has('error'))
                    <div class="bg-red-500/10 text-red-400 text-sm p-3 rounded-lg mb-4">{{ session('error') }}</div>
                @endif

                <form wire:submit.prevent="resetPassword" class="space-y-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">New Password</label>
                        <input type="password" id="password" wire:model.defer="password"
                               placeholder="Enter new password"
                               class="w-full bg-gray-800/2 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] @error('password') border-red-500 @enderror">
                        @error('password') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-2">Confirm
                            New Password</label>
                        <input type="password" id="password_confirmation" wire:model.defer="password_confirmation"
                               placeholder="Confirm new password"
                               class="w-full bg-gray-800/2 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all flex items-center justify-center">
                        <svg wire:loading wire:target="resetPassword" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                        <span wire:loading wire:target="resetPassword">Resetting...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
