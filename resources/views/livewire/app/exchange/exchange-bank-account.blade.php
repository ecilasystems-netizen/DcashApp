<div>
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="lg:hidden flex items-center space-x-4">
                    <a type="button" class="p-1 rounded-full bg-gray-800" href="{{ route('dashboard') }}">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Receiving</p>
                        <h2 class="font-bold text-xl text-white">Account</h2>
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-4">
                    <a type="button" class="p-2 rounded-full bg-gray-800 inline-block" href="{{ route('dashboard') }}">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Confirm Your Exchange</h1>
                        <p class="text-gray-400 text-sm mt-1">Please review the details and provide your account
                            information.</p>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Exchange Summary -->
        <div class="bg-gray-950 border border-gray-800 rounded-lg p-6 mb-8">
            <h4 class="font-bold text-white mb-4">Transaction Summary</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">You Send</span>
                    <span class="font-semibold text-white flex items-center space-x-2">
                                                    <span>{{ number_format($baseAmount, 2) }} {{ $baseCurrencyCode }}</span>
                                                    <img id="selected-flag-base"
                                                         src="{{ asset('storage/'.$baseCurrencyFlag)}}"
                                                         class="w-6 h-6 rounded-full" alt="">
                                                </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Exchange Rate</span>
                    <span class="font-semibold text-white">{{ number_format($exchangeRate, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Processing Fee</span>
                    <span
                        class="font-semibold text-white">{{ number_format($processingFee, 2) }} {{ $baseCurrencyCode }}</span>
                </div>
                <hr class="border-gray-700">
                <div class="flex justify-between items-center text-base">
                    <span class="text-gray-300">You Receive</span>
                    <span class="font-bold text-lg text-[#E1B362] flex items-center space-x-2">
                                                    <span>{{ number_format($quoteAmount, 2) }} {{ $quoteCurrencyCode }}</span>
                                                    <img id="selected-flag-base"
                                                         src="{{ asset('storage/' .$quoteCurrencyFlag)}}"
                                                         class="w-6 h-6 rounded-full" alt="">
                                                </span>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4 text-center">Rates are updated every 30 seconds. Complete your
                transaction promptly to lock in this rate.</p>
        </div>

        <!-- Account Details Form -->
        <div class="bg-gray-950 border border-gray-800 rounded-lg p-6">
            @if($quoteCurrencyType === 'fiat')
                <h4 class="font-bold text-white mb-6">Recipient <span
                        class="text-[#E1B362]">{{$quoteCurrencyCode}}</span> Bank Details</h4>

                <form wire:submit.prevent="submit" class="space-y-6 text-sm">
                    <div x-data="{ bankSelected: @entangle('bank').live }" class="space-y-6 text-sm">
                        @if($quoteCurrencyCode === 'NGN')
                            @if(auth()->user()->wallet && auth()->user()->wallet->balance > 0)
                                <div class="mb-8">
                                    <div class="flex items-center space-x-2 mb-4">
                                        <i data-lucide="credit-card" class="w-5 h-5 text-[#E1B362]"></i>
                                        <label class="text-gray-200 font-medium">Choose Recipient Account</label>
                                    </div>

                                    <div class="grid gap-4">
                                        <!-- Wallet Bank Details Option -->
                                        <label class="group relative cursor-pointer">
                                            <input type="radio" wire:model.live="useWalletBankDetails" value="true"
                                                   class="peer sr-only">

                                            <div
                                                class="relative flex items-start p-4 bg-gradient-to-r from-gray-900 to-gray-800 border-2 rounded-xl transition-all duration-300 hover:border-[#E1B362]/50 hover:shadow-lg hover:shadow-[#E1B362]/10"
                                                :class="$wire.useWalletBankDetails === 'true' || $wire.useWalletBankDetails === true ? 'border-[#E1B362] bg-gradient-to-r from-[#E1B362]/10 to-[#E1B362]/5 shadow-lg shadow-[#E1B362]/20' : 'border-gray-700'">

                                                <!-- Checkmark Icon -->
                                                <div
                                                    class="absolute top-3 right-3 w-6 h-6 bg-[#E1B362] rounded-full flex items-center justify-center transition-all duration-200"
                                                    x-show="$wire.useWalletBankDetails === 'true' || $wire.useWalletBankDetails === true"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 scale-75"
                                                    x-transition:enter-end="opacity-100 scale-100">
                                                    <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <i data-lucide="wallet" class="w-4 h-4 text-[#E1B362]"></i>
                                                        <h3 class="text-white font-semibold">My DCASH Wallet</h3>
                                                        <span
                                                            class="px-2 py-1 bg-[#E1B362]/20 text-[#E1B362] text-xs font-medium rounded-full">Recommended</span>
                                                    </div>

                                                    <p class="text-gray-400 text-sm leading-relaxed">
                                                        @if(auth()->user()->virtualBankAccount->bank_name ?? false)
                                                            <span class="inline-flex items-center space-x-2">
                                                                                            <span>{{ auth()->user()->virtualBankAccount->bank_name }}</span>
                                                                                            <span class="text-gray-500">•</span>
                                                                                            <span
                                                                                                class="font-mono">{{ auth()->user()->virtualBankAccount->account_number }}</span>
                                                                                        </span>
                                                        @else
                                                            <span class="inline-flex items-center space-x-2">
                                                                                            <i data-lucide="shield-check"
                                                                                               class="w-3 h-3"></i>
                                                                                            <span>Your verified linked bank account</span>
                                                                                        </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Manual Entry Option -->
                                        <label class="group relative cursor-pointer">
                                            <input type="radio" wire:model.live="useWalletBankDetails" value="false"
                                                   class="peer sr-only">

                                            <div
                                                class="relative flex items-start p-4 bg-gradient-to-r from-gray-900 to-gray-800 border-2 rounded-xl transition-all duration-300 hover:border-[#E1B362]/50 hover:shadow-lg hover:shadow-[#E1B362]/10"
                                                :class="$wire.useWalletBankDetails === 'false' || $wire.useWalletBankDetails === false ? 'border-[#E1B362] bg-gradient-to-r from-[#E1B362]/10 to-[#E1B362]/5 shadow-lg shadow-[#E1B362]/20' : 'border-gray-700'">

                                                <!-- Checkmark Icon -->
                                                <div
                                                    class="absolute top-3 right-3 w-6 h-6 bg-[#E1B362] rounded-full flex items-center justify-center transition-all duration-200"
                                                    x-show="$wire.useWalletBankDetails === 'false' || $wire.useWalletBankDetails === false"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 scale-75"
                                                    x-transition:enter-end="opacity-100 scale-100">
                                                    <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <i data-lucide="edit-3" class="w-4 h-4 text-[#E1B362]"></i>
                                                        <h3 class="text-white font-semibold">Enter Different Bank
                                                            Account</h3>
                                                    </div>

                                                    <p class="text-gray-400 text-sm leading-relaxed">
                                                                                    <span
                                                                                        class="inline-flex items-center space-x-2">
                                                                                        <i data-lucide="plus-circle"
                                                                                           class="w-3 h-3"></i>
                                                                                        <span>Manually enter recipient bank details</span>
                                                                                    </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Bank Selection Fields - Hidden when wallet bank details is selected -->
                            <div
                                x-show="$wire.useWalletBankDetails === 'false' || $wire.useWalletBankDetails === false || !$wire.useWalletBankDetails"
                                x-transition>
                                <div x-data="{ open: false, search: '', selectedBank: '' }" class="relative">
                                    <label for="bank" class="block text-gray-400 mb-2">Select Bank</label>
                                    <div class="relative">
                                        <input type="text" x-model="search" @click="open = true"
                                               @click.away="open = false" placeholder="Select bank..."
                                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                        <div x-show="open"
                                             class="absolute z-50 w-full mt-1 bg-gray-900 border border-gray-800 rounded-lg max-h-60 overflow-y-auto">
                                            @foreach($banks as $bank)
                                                <div
                                                    @click="selectedBank = '{{ $bank->id }}'; $wire.bank = '{{ $bank->id }}'; search = '{{ $bank->name }}'; open = false"
                                                    class="px-4 py-2 cursor-pointer hover:bg-gray-600 text-white"
                                                    x-show="'{{ strtolower($bank->name) }}'.includes(search.toLowerCase())">
                                                    {{ $bank->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" wire:model.live="bank" :value="selectedBank">
                                    @error('bank') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Account Number Field -->
                                <div x-show="$wire.bank" x-transition class="mt-6">
                                    <label for="accountNumber" class="block text-gray-400 mb-2">Account Number</label>
                                    <input type="text" id="accountNumber" wire:model.live="accountNumber"
                                           placeholder="Enter your 10-digit account number"
                                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                           maxlength="10"
                                           oninput="if(this.value.length > 10) this.value = this.value.slice(0,10)"
                                           onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46"
                                           @keydown="if(['e','+','-'].includes($event.key)) $event.preventDefault()">
                                    @error('accountNumber') <p
                                        class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                                    @if ($isVerifying)
                                        <p class="text-gray-400 text-xs mt-1 text-center">
                                            <i data-lucide="loader-2"
                                               class="animate-spin w-4 h-4 inline-block mr-1"></i>
                                            Verifying account...
                                        </p>
                                    @endif

                                    <div class="bg-gray-900 p-3 rounded-lg text-center mt-4">
                                        <p class="font-semibold {{
                                                                        $accountName === 'Account name will appear here' ? 'text-gray-400' :
                                                                        ($accountName === 'Invalid Account Number' || $accountName === 'Error fetching account name' ? 'text-red-400' : 'text-green-400')
                                                                    }}">
                                            {{ $accountName ?: 'Account name will appear here' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($quoteCurrencyCode === 'PHP')
                            <div x-data="{ open: false, search: '', selectedBank: '' }" class="relative">
                                <label for="bank" class="block text-gray-400 mb-2">Select Bank</label>
                                <div class="relative">
                                    <input type="text" x-model="search" @click="open = true" @click.away="open = false"
                                           placeholder="Select bank..."
                                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                    <div x-show="open"
                                         class="absolute z-50 w-full mt-1 bg-gray-900 border border-gray-800 rounded-lg max-h-60 overflow-y-auto">
                                        @foreach($phpBanks as $bank)
                                            <div
                                                @click="selectedBank = '{{ $bank['name'] }}'; $wire.bankName = '{{ $bank['name'] }}'; search = '{{ $bank['name'] }}'; open = false"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-600 text-white"
                                                x-show="'{{ strtolower($bank['name']) }}'.includes(search.toLowerCase())">
                                                {{ $bank['name'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" wire:model.live="bankName" :value="selectedBank">
                                @error('bankName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div x-show="$wire.bankName" x-transition class="mt-6">
                                <label for="accountNumber" class="block text-gray-400 mb-2">Account Number</label>
                                <input type="text" id="accountNumber" wire:model.live="accountNumber"
                                       placeholder="Enter your account number"
                                       class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                       onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46"
                                       @keydown="if(['e','+','-'].includes($event.key)) $event.preventDefault()">
                                @error('accountNumber') <p
                                    class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                                <div class="mt-4">
                                    <label for="accountName" class="block text-gray-400 mb-2">Account Name</label>
                                    <input type="text" id="accountName" wire:model.live="accountName" placeholder=""
                                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                    @error('accountName') <p
                                        class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @else
                            <div>
                                <label for="bankName" class="block text-gray-400 mb-2">Bank Name</label>
                                <input type="text" id="bankName" wire:model.live="bankName"
                                       placeholder="Enter bank name"
                                       class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('bankName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6">
                                <label for="accountNumber" class="block text-gray-400 mb-2">Account Number</label>
                                <input type="text" id="accountNumber" wire:model.live="accountNumber"
                                       placeholder="Enter your account number"
                                       class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                       onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46"
                                       @keydown="if(['e','+','-'].includes($event.key)) $event.preventDefault()">
                                @error('accountNumber') <p
                                    class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                                <div class="mt-4">
                                    <label for="accountName" class="block text-gray-400 mb-2">Account Name</label>
                                    <input type="text" id="accountName" wire:model.live="accountName" placeholder=""
                                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                    @error('accountName') <p
                                        class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Narration Field with Clickable Options -->
                        <div class="mt-6" x-data="{
                            selectedNarration: @entangle('narration').live,
                            showCustom: @entangle('showCustomNarration').live,
                            customValue: @entangle('customNarration').live
                        }">
                            <label class="block text-gray-400 mb-3 flex items-center justify-between">
                                <span class="flex items-center space-x-2">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    <span>Purpose of Transfer</span>
                                </span>
                                <button type="button"
                                        @click="showCustom = !showCustom; if(showCustom) { selectedNarration = ''; customValue = ''; }"
                                        class="text-xs text-[#E1B362] hover:text-[#d4a555] transition-colors flex items-center space-x-1">
                                    <i :data-lucide="showCustom ? 'list' : 'edit-3'" class="w-3 h-3"></i>
                                    <span x-text="showCustom ? 'Select from list' : 'Enter custom'"></span>
                                </button>
                            </label>

                            <!-- Predefined Options Grid -->
                            <div x-show="!showCustom" x-transition class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-2">
                                @foreach($this->narrationOptions as $key => $label)
                                    <button type="button"
                                            @click="selectedNarration = '{{ $key }}'"
                                            :class="selectedNarration === '{{ $key }}'
                                                ? 'bg-[#E1B362] text-gray-900 border-[#E1B362]'
                                                : 'bg-gray-900 text-gray-300 border-gray-800 hover:border-[#E1B362]/50'"
                                            class="px-3 py-2.5 rounded-lg border transition-all text-xs font-medium flex items-center justify-center space-x-2">
                                        <i data-lucide="check-circle"
                                           x-show="selectedNarration === '{{ $key }}'"
                                           class="w-3.5 h-3.5"></i>
                                        <span>{{ $label }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Custom Narration Input -->
                            <div x-show="showCustom" x-transition>
                                <input type="text"
                                       x-model="customValue"
                                       wire:model.live="customNarration"
                                       placeholder="Enter custom purpose (max 20 characters)"
                                       maxlength="20"
                                       class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] text-sm">
                                <p class="text-xs text-gray-500 mt-1" x-show="customValue.length > 0">
                                    <span x-text="customValue.length"></span>/20 characters
                                </p>
                            </div>

                            @error('narration')
                            <p class="text-red-500 text-xs mt-2 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                            @error('customNarration')
                            <p class="text-red-500 text-xs mt-2 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>

                        <!-- Submit Button - Always show when any option is selected -->
                        <div class="pt-6" x-transition>
                            <button type="submit"
                                    class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if($quoteCurrencyCode === 'NGN')
                                        @if($useWalletBankDetails === 'true')
                                            @if((!$showCustomNarration && !$narration) || ($showCustomNarration && !$customNarration)) disabled
                                    @endif
                                    @elseif($useWalletBankDetails === 'false')
                                        @if($accountName === 'Account name will appear here' ||
                                            $accountName === 'Invalid Account Number' ||
                                            $accountName === 'Error fetching account name' ||
                                            empty($accountName) ||
                                            !$bank || !$accountNumber ||
                                            ((!$showCustomNarration && !$narration) || ($showCustomNarration && !$customNarration)))
                                            disabled
                                    @endif
                                    @else
                                        disabled
                                    @endif
                                    @else
                                        @if(!$accountNumber || !$accountName || !$bankName ||
                                            ((!$showCustomNarration && !$narration) || ($showCustomNarration && !$customNarration)))
                                            disabled
                                @endif
                                @endif>
                                Confirm and Proceed
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <h4 class="font-bold text-white mb-6"><span class="text-[#E1B362]">{{$quoteCurrencyCode}}</span> Crypto
                    Wallet Details</h4>

                <form wire:submit.prevent="submit" class="space-y-6 text-sm">
                    <div>
                        <label for="walletAddress" class="block text-gray-400 mb-2">{{ $quoteCurrencyCode }} Wallet
                            Address</label>
                        <input type="text" id="walletAddress" wire:model.live="walletAddress"
                               placeholder="Enter your {{ $quoteCurrencyCode }} wallet address"
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] font-mono text-xs">
                        @error('walletAddress') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($quoteCurrencyCode !== 'BTC')
                        <div>
                            <label for="network" class="block text-gray-400 mb-2">Network</label>
                            <select wire:model.live="network" id="network"
                                    class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] appearance-none cursor-pointer">
                                <option value="">Select network...</option>
                                @foreach($networks as $networkOption)
                                    <option value="{{ $networkOption }}">{{ $networkOption }}</option>
                                @endforeach
                            </select>
                            @error('network') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <!-- Narration Field with Clickable Options -->
                    <div class="mt-6" x-data="{
                            selectedNarration: @entangle('narration').live,
                            showCustom: @entangle('showCustomNarration').live,
                            customValue: @entangle('customNarration').live
                        }">
                        <label class="block text-gray-400 mb-3 flex items-center justify-between">
                                <span class="flex items-center space-x-2">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    <span>Purpose of Transfer</span>
                                </span>
                            <button type="button"
                                    @click="showCustom = !showCustom; if(showCustom) { selectedNarration = ''; customValue = ''; }"
                                    class="text-xs text-[#E1B362] hover:text-[#d4a555] transition-colors flex items-center space-x-1">
                                <i :data-lucide="showCustom ? 'list' : 'edit-3'" class="w-3 h-3"></i>
                                <span x-text="showCustom ? 'Select from list' : 'Enter custom'"></span>
                            </button>
                        </label>

                        <!-- Predefined Options Grid -->
                        <div x-show="!showCustom" x-transition class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-2">
                            @foreach($this->narrationOptions as $key => $label)
                                <button type="button"
                                        @click="selectedNarration = '{{ $key }}'"
                                        :class="selectedNarration === '{{ $key }}'
                                                ? 'bg-[#E1B362] text-gray-900 border-[#E1B362]'
                                                : 'bg-gray-900 text-gray-300 border-gray-800 hover:border-[#E1B362]/50'"
                                        class="px-3 py-2.5 rounded-lg border transition-all text-xs font-medium flex items-center justify-center space-x-2">
                                    <i data-lucide="check-circle"
                                       x-show="selectedNarration === '{{ $key }}'"
                                       class="w-3.5 h-3.5"></i>
                                    <span>{{ $label }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Custom Narration Input -->
                        <div x-show="showCustom" x-transition>
                            <input type="text"
                                   x-model="customValue"
                                   wire:model.live="customNarration"
                                   placeholder="Enter custom purpose (max 20 characters)"
                                   maxlength="20"
                                   class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] text-sm">
                            <p class="text-xs text-gray-500 mt-1" x-show="customValue.length > 0">
                                <span x-text="customValue.length"></span>/20 characters
                            </p>
                        </div>

                        @error('narration')
                        <p class="text-red-500 text-xs mt-2 flex items-center space-x-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                        @error('customNarration')
                        <p class="text-red-500 text-xs mt-2 flex items-center space-x-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <div class="bg-yellow-900/30 border border-yellow-700/50 rounded-lg p-4">
                        <div class="flex items-start space-x-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                            <div class="text-xs">
                                <p class="text-yellow-400 font-semibold mb-1">Important:</p>
                                <p class="text-yellow-200">Double-check your wallet address and network. Cryptocurrency
                                    transactions are irreversible.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                @if($quoteCurrencyCode !== 'BTC' && (!$walletAddress || !$network || ((!$showCustomNarration && !$narration) || ($showCustomNarration && !$customNarration))))
                                    disabled
                                @elseif($quoteCurrencyCode === 'BTC' && (!$walletAddress || ((!$showCustomNarration && !$narration) || ($showCustomNarration && !$customNarration))))
                                    disabled
                            @endif>
                            Confirm and Proceed
                        </button>
                    </div>
                </form>
            @endif

            <div class="mt-6 bg-yellow-900/20 rounded-lg p-0">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                    <h3 class="text-sm font-semibold text-red-400">Important notice</h3>
                </div>

                <ul class="text-xs space-y-2 text-yellow-100">
                    <li class="flex items-start space-x-2">
                        <i data-lucide="user-check" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Verify recipient details; transfers are non-refundable.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="clock" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Check your daily transfer limit before proceeding.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Review transfer fees in advance.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="refresh-ccw" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Know the expected transfer processing time.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Wait for confirmation before exiting the app.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Save the order number for future reference.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Be wary of unexpected money requests.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="link-2" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Avoid phishing scams and suspicious links.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
