<div>
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{route('kyc.start')}}"
                       class="text-gray-400 hover:text-white active:text-gray-500 transition-colors flex items-center gap-2"
                       title="Back to Start"
                    >
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">KYC</p>
                        <h2 class="font-bold text-xl text-white">Personnal Info</h2>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-400">Step 1 of 4</p>
            </div>
        </header>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <h4 class="font-bold text-white mb-6">
                Enter your details exactly as they appear on your ID.
            </h4>
            <form wire:submit="submit" class="space-y-6 text-sm">
                <div>
                    <label for="fullName" class="block text-gray-400 mb-2">Full Legal Name</label>
                    <input
                        wire:model="fullName"
                        type="text"
                        id="fullName"
                        placeholder="e.g., John Doe"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                    />
                    @error('fullName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dob" class="block text-gray-400 mb-2">Date of Birth</label>
                        <input
                            wire:model="dob"
                            type="date"
                            id="dob"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        />
                        @error('dob') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="nationality" class="block text-gray-400 mb-2">Nationality</label>
                        <select
                            wire:model.live="nationality"
                            id="nationality"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                            <option value="">Select Nationality</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Kenya">Kenya</option>
                        </select>
                        @error('nationality') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div>
                    <label for="address" class="block text-gray-400 mb-2">Residential Address</label>
                    <input
                        wire:model="address"
                        type="text"
                        id="address"
                        placeholder="Enter your street address"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                    />
                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="pt-4">
                    <button
                        type="submit"
                        class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all"
                    >
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
