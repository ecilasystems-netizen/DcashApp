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
                        <img id="selected-flag-base" src="{{ asset('storage/'.$baseCurrencyFlag)}}"
                             class="w-6 h-6 rounded-full" alt="">
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Exchange Rate</span>
                    <span
                        class="font-semibold text-white">1 {{ $baseCurrencyCode }} = {{ number_format($exchangeRate, 2) }} {{ $quoteCurrencyCode }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Processing Fee</span>
                    <span
                        class="font-semibold text-white">{{ number_format($processingFee, 2) }} {{ $baseCurrencyCode }}</span>
                </div>
                <hr class="border-gray-700">
                <div class="flex justify-between items-center text-base">
                    <span class="text-gray-300">You Receive</span>
                    <span
                        class="font-bold text-lg text-[#E1B362] flex items-center space-x-2">
                        <span>{{ number_format($quoteAmount, 2) }} {{ $quoteCurrencyCode }}</span>
                        <img id="selected-flag-base" src="{{ asset('storage/' .$quoteCurrencyFlag)}}"
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
                            <div x-data="{ open: false, search: '', selectedBank: '' }" class="relative">
                                <label for="bank" class="block text-gray-400 mb-2">Select Bank</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="search"
                                        @click="open = true"
                                        @click.away="open = false"
                                        placeholder="Banks..."
                                        class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                    >
                                    <div x-show="open"
                                         class="absolute z-50 w-full mt-1 bg-gray-900 border border-gray-800 rounded-lg max-h-60 overflow-y-auto">
                                        @foreach($banks as $bank)
                                            <div
                                                @click="selectedBank = '{{ $bank->id }}'; $wire.bank = '{{ $bank->id }}'; search = '{{ $bank->name }}'; open = false"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-600 text-white"
                                                x-show="'{{ strtolower($bank->name) }}'.includes(search.toLowerCase())"
                                            >
                                                {{ $bank->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" wire:model.live="bank" :value="selectedBank">
                                @error('bank') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @elseif($quoteCurrencyCode === 'PHP')
                            <div x-data="{ open: false, search: '', selectedBank: '' }" class="relative">
                                <label for="bank" class="block text-gray-400 mb-2">Select Bank</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="search"
                                        @click="open = true"
                                        @click.away="open = false"
                                        placeholder="Banks..."
                                        class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                    >
                                    <div x-show="open"
                                         class="absolute z-50 w-full mt-1 bg-gray-900 border border-gray-800 rounded-lg max-h-60 overflow-y-auto">
                                        @foreach($phpBanks as $bank)
                                            <div
                                                @click="selectedBank = '{{ $bank['name'] }}'; $wire.bankName = '{{ $bank['name'] }}'; search = '{{ $bank['name'] }}'; open = false"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-600 text-white"
                                                x-show="'{{ strtolower($bank['name']) }}'.includes(search.toLowerCase())"
                                            >
                                                {{ $bank['name'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" wire:model.live="bankName" :value="selectedBank">
                                @error('bankName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <div>
                                <label for="bankName" class="block text-gray-400 mb-2">Bank Name</label>
                                <input type="text" id="bankName" wire:model.live="bankName"
                                       placeholder="Enter bank name"
                                       class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('bankName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div x-show="@if($quoteCurrencyCode === 'NGN') bankSelected @else $wire.bankName @endif"
                             x-transition>
                            <label for="accountNumber" class="block text-gray-400 mb-2">Account Number</label>
                            <input type="text" id="accountNumber" wire:model.live="accountNumber"
                                   placeholder="Enter your {{ $quoteCurrencyCode === 'NGN' ? '10-digit' : '' }} account number"
                                   class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                                   @if($quoteCurrencyCode === 'NGN')
                                       maxlength="10"
                                   oninput="if(this.value.length > 10) this.value = this.value.slice(0,10)"

                                   @endif onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46"
                                   @keydown="if(['e','+','-'].includes($event.key)) $event.preventDefault()">
                            @error('accountNumber') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                            @if($quoteCurrencyCode === 'NGN')
                                @if ($isVerifying)
                                    <p class="text-gray-400 text-xs mt-1 text-center">
                                        <i data-lucide="loader-2" class="animate-spin w-4 h-4 inline-block mr-1"></i>
                                        Verifying account...
                                    </p>
                                @endif

                                <div class="bg-gray-900 p-3 rounded-lg text-center mt-4">
                                    <p class="font-semibold {{
                                        $accountName === 'Account name will appear here' ? 'text-gray-400' :
                                        ($accountName === 'Invalid Account Number' || $accountName === 'Error fetching account name' ? 'text-red-400' : 'text-green-400')
                                    }}">
                                        {{ $accountName }}
                                    </p>
                                </div>
                            @else
                                <div class="mt-4">
                                    <label for="accountName" class="block text-gray-400 mb-2">Account Name</label>
                                    <input type="text" id="accountName" wire:model.live="accountName"
                                           placeholder=""
                                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                    @error('accountName') <p
                                        class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endif

                            <div class="pt-4">
                                <button type="submit"
                                        class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                        @if($quoteCurrencyCode === 'NGN')
                                            @if($accountName === 'Account name will appear here' ||
                                                $accountName === 'Invalid Account Number' ||
                                                $accountName === 'Error fetching account name')
                                                disabled
                                        @endif
                                        @else
                                            @if(!$accountNumber || !$accountName || !$bankName)
                                                disabled
                                    @endif
                                    @endif>
                                    Confirm and Proceed
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <h4 class="font-bold text-white mb-6"> <span
                        class="text-[#E1B362]">{{$quoteCurrencyCode}}</span> Crypto Wallet Details</h4>

                <form wire:submit.prevent="submit" class="space-y-6 text-sm">
                    <div>
                        <label for="walletAddress" class="block text-gray-400 mb-2">{{ $quoteCurrencyCode }} Wallet
                            Address</label>
                        <input type="text" id="walletAddress" wire:model.live="walletAddress"
                               placeholder="Enter your {{ $quoteCurrencyCode }} wallet address"
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        @error('walletAddress') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($quoteCurrencyCode !== 'BTC')
                        <div>
                            <label for="network" class="block text-gray-400 mb-2">Network</label>
                            <select wire:model.live="network" id="network"
                                    class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                <option value="">Select Network</option>
                                @if(isset($networks))
                                    @foreach($networks as $network)
                                        <option value="{{ $network }}">{{ $network }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('network') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

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
                                @if(!$walletAddress || ($baseCurrencyCode !== 'BTC' && !$network))
                                    disabled
                            @endif>
                            Confirm and Proceed
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

</div>
