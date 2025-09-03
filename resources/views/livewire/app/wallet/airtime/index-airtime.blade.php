<div>
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{route('dashboard')}}" class="p-2 rounded-full hover:bg-gray-800">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Buy</p>
                        <h2 class="font-bold text-xl text-white">Airtime</h2>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <!-- Recharge Form -->
    <div class="p-1 lg:p-0 lg:py-8 space-y-8">
        <!-- Network Selection -->
        <div>
            <h3 class="font-semibold text-white mb-4">Select Network</h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach($networks as $network)
                    <div wire:click="selectNetwork('{{ $network['code'] }}')"
                         class="cursor-pointer bg-gray-800 p-3 rounded-lg border-2 transition-all duration-200
                                                        {{ $selectedNetwork === $network['code'] ? 'border-[#E1B362] network-selected' : 'border-gray-700' }}
                                                        hover:border-[#E1B362]/50">
                        <div class="flex justify-center items-center">
                            <img src="{{ $network['logo'] }}"
                                 alt="{{ $network['name'] }}"
                                 class="h-10 object-contain rounded"/>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Phone Number -->
        <div>
            <label for="phone" class="font-semibold text-white mb-2 block">Phone Number</label>
            <input type="tel"
                   wire:model.live="phoneNumber"
                   placeholder="Enter phone number"
                   class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362] transition-colors"/>
            @error('phoneNumber') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Amount Selection -->
        <div>
            <h3 class="font-semibold text-white mb-4">Select Amount (₦)</h3>
            <div class="grid grid-cols-3 gap-4 mb-4">
                @foreach($predefinedAmounts as $amount)
                    <button wire:click="selectAmount({{ $amount }})"
                            class="bg-gray-800 p-4 rounded-lg font-bold text-lg hover:bg-gray-700 border-2 transition-all
                                                           {{ $selectedAmount === $amount ? 'border-[#E1B362] network-selected' : 'border-gray-700' }}
                                                           focus:border-[#E1B362] focus:outline-none">
                        {{ number_format($amount) }}
                    </button>
                @endforeach
            </div>
            <input type="number"
                   wire:model.live="customAmount"
                   placeholder="Or enter amount"
                   class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362] transition-colors"/>
        </div>

        <button
            wire:click="openConfirmationModal"
            class="brand-gradient w-full text-white py-4 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all mt-5
                                           {{ $this->canProceed() ? '' : 'opacity-50 cursor-not-allowed' }}"
            @if(!$this->canProceed()) disabled @endif>
            <span wire:loading.remove wire:target="openConfirmationModal">Proceed</span>
            <span wire:loading wire:target="openConfirmationModal">Processing...</span>
        </button>
    </div>

    <!-- Confirmation Modal -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop {{ $showConfirmModal ? '' : 'hidden' }}">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-6 border border-gray-700 shadow-xl">
            <h2 class="text-2xl font-bold text-center text-white mb-2">Confirm Transaction</h2>
            <div class="space-y-3 text-sm my-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Network:</span>
                    <span class="font-bold text-white flex items-center gap-2">
                        @if($selectedNetwork)
                            <img src="{{ collect($networks)->where('code', $selectedNetwork)->first()['logo'] ?? '' }}"
                                 class="h-5 w-5 object-contain rounded-full"
                                 alt="{{ collect($networks)->where('code', $selectedNetwork)->first()['short_name'] ?? '' }}">
                            {{ collect($networks)->where('code', $selectedNetwork)->first()['short_name'] ?? '' }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Phone Number:</span>
                    <span class="font-bold text-white">{{ $phoneNumber }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Amount:</span>
                    <span class="font-bold text-red-400">₦{{ number_format($this->getSelectedAmountProperty()) }}</span>
                </div>
                <hr class="border-gray-700 !my-4">
                <div class="flex justify-between">
                    <span class="text-gray-400">Service Fee:</span>
                    <span class="font-bold text-gray-400 line-through"
                          style="text-decoration: line-through !important;">₦0.00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Current Balance:</span>
                    <span class="font-bold text-white">₦{{ number_format($userBalance) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Balance After:</span>
                    <span
                        class="font-bold {{ ($userBalance - $this->getSelectedAmountProperty()) >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        ₦{{ number_format($userBalance - $this->getSelectedAmountProperty()) }}
                    </span>
                </div>
            </div>

            <!-- PIN input section -->
            <div class="mb-6">
                <label for="pin" class="font-semibold text-white mb-2 block text-center">Enter your 4-digit PIN</label>
                <input type="password" wire:model.live="pin" id="pin" maxlength="4"
                       class="w-full bg-gray-900 border-2 border-gray-700 rounded-lg px-4 py-3 text-white text-center text-2xl tracking-[1em] focus:outline-none focus:border-[#E1B362]">
                @error('pin') <span class="text-red-500 text-sm mt-1 block text-center">{{ $message }}</span> @enderror
                @if(session()->has('error'))
                    <div class="text-red-500 text-sm mt-1 block text-center">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button wire:click="cancelPurchase"
                        @click="intentionallyCancelling = true"
                        class="bg-gray-700 text-white py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button wire:click="confirmPurchase"
                        wire:loading.attr="disabled"
                        class="brand-gradient text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-colors">
                    <span wire:loading.remove wire:target="confirmPurchase">Confirm</span>
                    <span wire:loading wire:target="confirmPurchase">Processing...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop {{ $showSuccessModal ? '' : 'hidden' }}">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-8 text-center border border-gray-700 shadow-xl">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check" class="text-green-400 w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Success!</h2>
            <p class="text-gray-400 mb-6">Your airtime purchase was successful.</p>
            <button wire:click="closeSuccessModal"
                    class="brand-gradient w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all">
                Done
            </button>
        </div>
    </div>


    @push('styles')
        <style>
            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            .network-selected {
                transform: scale(1.05);
                border-color: #e1b362;
                box-shadow: 0 0 15px rgba(225, 179, 98, 0.3);
            }

            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }
        </style>
    @endpush
</div>
