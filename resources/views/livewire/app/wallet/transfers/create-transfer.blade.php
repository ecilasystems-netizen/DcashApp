<div x-data="{
    showBankDropdown: false,
    isVerifying: false,
    initSpinner() {
        // Listen for Livewire events for the spinner
        Livewire.on('verification-started', () => {
            this.isVerifying = true;
        });

        Livewire.on('verification-ended', () => {
            this.isVerifying = false;
        });
    }
}" x-init="initSpinner()">
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-gray-800">
                    <i data-lucide="arrow-left"></i>
                </a>
                <div>
                    <p class="text-xs text-gray-400">Send</p>
                    <h2 class="font-bold text-xl text-white">Money</h2>
                </div>
            </div>
        </header>
    </x-slot>

    <!-- Transfer Form -->
    <div class="lg:py-8 space-y-6">
        <!-- Transfer Details -->
        <div class="bg-gray-950 p-0 rounded-lg space-y-4">
            <!-- Custom Bank Dropdown -->
            <div>
                <label class="font-semibold text-sm text-gray-300 mb-2 block">Bank</label>
                <div class="relative">
                    <button type="button" wire:click="toggleBankDropdown"
                            class="w-full bg-gray-800/2 border-2 border-gray-700 rounded-lg px-4 py-3 text-white flex items-center justify-between focus:outline-none focus:border-[#E1B362]">
                                            <span class="flex items-center gap-3">
                                                @if($selectedBank)
                                                    {{--                                                    <img src="{{ $selectedBank['logo'] }}"--}}
                                                    {{--                                                         class="h-6 w-6 object-contain rounded-full bg-white p-0.5"--}}
                                                    {{--                                                         onerror="this.style.display='none'">--}}
                                                @else
                                                    <i data-lucide="landmark" class="text-gray-400"></i>
                                                @endif
                                                <span>{{ $selectedBank ? $selectedBank['name'] : 'Select a bank' }}</span>
                                            </span>
                        <i data-lucide="chevron-down" class="{{ $showBankDropdown ? 'rotate-180' : '' }}"></i>
                    </button>
                    @if($showBankDropdown)
                        <div wire:click.away="$set('showBankDropdown', false)"
                             class="absolute z-20 w-full mt-2 bg-gray-800 border border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($banks as $index => $bank)
                                <div wire:click="selectBank({{ $index }})"
                                     class="bank-item cursor-pointer hover:bg-gray-800 p-3 flex items-center gap-3">
                                    {{--                                    <img src="{{ $bank['logo'] }}" alt="{{ $bank['name'] }}"--}}
                                    {{--                                         class="h-6 w-6 object-contain rounded-full bg-white p-0.5"--}}
                                    {{--                                         onerror="this.style.display='none'">--}}
                                    <span>{{ $bank['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <label for="account-number" class="font-semibold text-sm text-gray-300 mb-2 block">Account
                    Number</label>
                <input type="number" id="account-number" wire:model.live="accountNumber"
                       placeholder="Enter account number"
                       class="w-full bg-gray-800/2 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362]">

                <div x-data="{ isVerifying: false }"
                     class="text-sm text-green-400 font-semibold mt-2 h-5 flex items-center min-h-[1.25rem]">
                    <svg x-show="isVerifying" class="animate-spin h-4 w-4 mr-2 text-green-400"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span x-text="$wire.accountNameStatus"></span>

                    <script>
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('verification-started', () => {
                                Alpine.store('accountVerification').isVerifying = true;
                            });

                            Livewire.on('verification-ended', () => {
                                Alpine.store('accountVerification').isVerifying = false;
                            });
                        });
                    </script>
                </div>
            </div>
            <div>
                <label for="amount" class="font-semibold text-sm text-gray-300 mb-2 block">Amount</label>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    @foreach([1000, 2000, 5000, 10000, 20000, 50000] as $amountOption)
                        <button wire:click="selectAmount({{ $amountOption }})"
                                class="amount-btn bg-gray-800 p-3 rounded-lg font-semibold hover:bg-gray-600 border-2 border-transparent transition-all {{ $amount == $amountOption ? 'amount-btn-selected' : '' }}">
                            ₦{{ number_format($amountOption) }}
                        </button>
                    @endforeach
                </div>
                <input type="number" id="amount" wire:model.live="amount"
                       placeholder="Or enter amount (₦)"
                       class="w-full bg-gray-800/2 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362]">
            </div>
            <div>
                <label for="narration" class="font-semibold text-sm text-gray-300 mb-2 block">Narration
                    (Optional)</label>
                <input type="text" id="narration" wire:model="narration"
                       placeholder="What is this for?"
                       class="w-full bg-gray-800/2 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362]">
            </div>
        </div>

        <!-- Proceed Button -->
        <button wire:click="openConfirmationModal"
                class="brand-gradient w-full text-white py-4 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all mt-5 disabled:opacity-50"
            {{ !$this->isFormValid() ? 'disabled' : '' }}>
            Proceed
        </button>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop"
             role="dialog" aria-hidden="true">
            <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-6 border border-gray-700 shadow-xl" role="document">
                <h2 class="text-2xl font-bold text-center text-white mb-2">Confirm Transfer</h2>
                <div class="space-y-3 text-sm my-6">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Recipient:</span>
                        <span class="font-bold text-white">{{ $verifiedAccountName }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Bank:</span>
                        <span class="font-bold text-white flex items-center gap-2">
                            @if($selectedBank)
                                {{--                                <img src="{{ $selectedBank['logo'] }}" alt="{{ $selectedBank['name'] }}"--}}
                                {{--                                     class="h-5 w-5 object-contain rounded-full bg-white p-0.5"--}}
                                {{--                                     onerror="this.style.display='none'">--}}
                                {{ $selectedBank['name'] }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Account:</span>
                        <span class="font-bold text-white">{{ $accountNumber }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Amount:</span>
                        <span class="font-bold text-red-400">₦{{ number_format((int)$amount) }}</span>
                    </div>
                    <hr class="border-gray-700 !my-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Transfer Fee:</span>
                        <span class="font-bold text-gray-400 line-through"
                              style="text-decoration: line-through !important;">₦{{ number_format($transferFee, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Current Balance:</span>
                        <span class="font-bold text-white">₦{{ number_format($userBalance) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Balance After:</span>
                        <span
                            class="font-bold {{ ($userBalance - $amount - $transferFee) >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            ₦{{ number_format($userBalance - $amount - $transferFee) }}
                        </span>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="pin" class="font-semibold text-white mb-2 block text-center">Enter your 4-digit
                        PIN</label>
                    <input type="password" id="pin" wire:model.live="pin" maxlength="4"
                           class="w-full bg-gray-900 border-2 border-gray-700 rounded-lg px-4 py-3 text-white text-center text-2xl tracking-[1em] focus:outline-none focus:border-[#E1B362]">
                </div>

                @if($userBalance >= ($amount + $transferFee))
                    <div class="grid grid-cols-2 gap-4">
                        <button wire:click="$set('showConfirmationModal', false)"
                                class="bg-gray-500 text-white py-3 rounded-lg font-semibold hover:bg-gray-600">
                            Cancel
                        </button>
                        <button wire:click="processTransfer"
                                class="brand-gradient text-white py-3 rounded-lg font-semibold hover:opacity-90">
                            Send Money
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        <button wire:click="$set('showConfirmationModal', false)"
                                class="bg-red-600 text-white py-3 rounded-lg font-semibold cursor-not-allowed opacity-70">
                            Insufficient Balance, Deposit
                        </button>
                    </div>
                @endif
            </div>
        </div>
</div>
@endif

<!-- Error Modal -->
@if($showErrorModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop"
         role="dialog" aria-hidden="true">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-8 text-center border border-gray-700 shadow-xl"
             role="document">
            <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="x" class="text-red-400 w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Error</h2>
            <p class="text-gray-400 mb-6">{{ $errorMessage }}</p>
            <button wire:click="$set('showErrorModal', false)"
                    class="bg-gray-700 w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:bg-gray-600">
                Close
            </button>
        </div>
    </div>
@endif

<!-- Success Modal -->
@if($showSuccessModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop"
         role="dialog" aria-hidden="true">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-8 text-center border border-gray-700 shadow-xl"
             role="document">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check" class="text-green-400 w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Transfer Successful</h2>
            <p class="text-gray-400 mb-6">Your transfer to {{ $verifiedAccountName }} was completed successfully.</p>
            <button wire:click="finishTransaction"
                    class="brand-gradient w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:opacity-90">
                Continue
            </button>
        </div>
    </div>
@endif

@push('styles')
    <style>
        .brand-gradient {
            background: linear-gradient(135deg, #e1b362 0, #d4a55a 100%);
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        /* Custom scrollbar for dropdown */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #2d3748; /* gray-800 */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4a5568; /* gray-600 */
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #718096; /* gray-500 */
        }

        .amount-btn-selected {
            border-color: #e1b362;
            background-color: rgba(225, 179, 98, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @endpush
    </div>
