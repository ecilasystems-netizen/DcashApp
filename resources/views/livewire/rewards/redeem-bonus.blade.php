<div>
    <x-slot name="header">
        <header class="bg-gray-950/95 backdrop-blur-md sticky top-0 z-50 border-b border-gray-800/50">
            <div class="px-4 lg:px-6 py-4 flex justify-between items-center max-w-7xl mx-auto">
                <!-- Mobile Header -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('rewards') }}"
                       class="p-2 rounded-xl bg-gray-800/60 hover:bg-gray-700 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5 text-white"></i>
                    </a>
                    <div>
                        <h2 class="font-bold text-lg text-white">Redeem</h2>
                        <p class="text-xs text-gray-400">Convert DCoins</p>
                    </div>
                </div>


                <!-- Available Balance -->
                <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-bold px-4 py-2 rounded-xl">
                    {{ number_format($totalRewards, 0) }} DCoins
                </div>
            </div>
        </header>
    </x-slot>

    <div class="min-h-screen bg-gray-950 p-0 lg:p-8">
        <div class="max-w-2xl mx-auto space-y-8">

            <!-- Success Message -->
            <div x-data="{ show: false, message: '', reference: '' }"
                 @redemption-success.window="show = true; message = $event.detail.message; reference = $event.detail.reference; setTimeout(() => show = false, 5000)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="bg-green-500/10 border border-green-500/30 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-green-300 font-semibold" x-text="message"></p>
                        <p class="text-green-400 text-sm mt-1">Reference: <span class="font-mono"
                                                                                x-text="reference"></span></p>
                    </div>
                    <button @click="show = false" class="ml-auto text-green-400 hover:text-green-300">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Balance Card -->
            <div
                class="bg-gradient-to-br from-yellow-600/20 via-gray-900 to-gray-900 rounded-2xl p-6 border border-yellow-500/20">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-500/20 rounded-full mb-4">
                        <i data-lucide="coins" class="w-8 h-8 text-yellow-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Available Balance</h3>
                    <p class="text-4xl font-bold text-yellow-400 mb-2">{{ number_format($totalRewards, 0) }}</p>
                    <p class="text-gray-400">DCoins ready to redeem</p>
                </div>
            </div>

            <!-- Redemption Form -->
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl border border-gray-800/50 overflow-hidden">
                <div class="p-6 border-b border-gray-800/50">
                    <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-3">
                        <i data-lucide="credit-card" class="w-6 h-6 text-yellow-400"></i>
                        Redemption Details
                    </h3>
                    <p class="text-gray-400">Choose your preferred redemption method</p>
                </div>

                <form wire:submit.prevent="submitRedemption" class="p-6 space-y-6">
                    @if ($errors->has('general'))
                        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0"></i>
                                <p class="text-red-400 text-sm">{{ $errors->first('general') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Currency Selection -->
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-white">Select Currency</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                            @foreach($supportedCurrencies as $code => $currency)
                                <label class="relative cursor-pointer">
                                    <input type="radio" wire:model.live="selectedCurrency" value="{{ $code }}"
                                           class="sr-only peer">
                                    <div class="p-2 sm:p-4 bg-gray-800/40 border border-gray-700/50 rounded-xl
                                               peer-checked:border-yellow-500/50 peer-checked:bg-yellow-500/10
                                               hover:border-gray-600/50 transition-all">
                                        <div class="text-center">
                                            @if($currency['type'] === 'crypto')
                                                <div class="mt-1 sm:mt-2">
                                                    <p class="font-bold text-white text-sm sm:text-base">{{ $code }}
                                                        <span
                                                            class="ml-1 sm:ml-3 bg-blue-500/20 text-blue-400 rounded text-[10px] sm:text-xs px-1">Crypto</span>
                                                    </p>
                                                </div>
                                            @else
                                                <div class="mt-1 sm:mt-2">
                                                    <p class="font-bold text-white text-sm sm:text-base">{{ $code }}</p>
                                                </div>
                                            @endif

                                            <p class="text-[10px] sm:text-xs text-gray-400 truncate">{{ $currency['name'] }}</p>

                                            <!-- Exchange Rate Display -->
                                            @if(isset($currency['exchange_rate']) && $currency['exchange_rate'] != 1)
                                                <div
                                                    class="mt-1 sm:mt-2 px-1 sm:px-2 py-0.5 sm:py-1 bg-green-500/20 rounded text-[10px] sm:text-xs">
                                                    <p class="text-green-400 font-medium">
                                                        1
                                                        = {{ $currency['symbol'] }}{{ number_format($currency['exchange_rate'], 0) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if(isset($currency['min_redemption']))
                                                <p class="text-[10px] sm:text-xs text-yellow-400 mt-0.5 sm:mt-1">
                                                    Min: {{ $currency['min_redemption'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedCurrency') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Amount Input with Exchange Rate Display -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-white">Redemption Amount</label>
                        <div class="relative">
                            <input type="number" wire:model.live="redeemAmount"
                                   placeholder="Enter amount in DCoins"
                                   min="{{ $currencyData ? $currencyData->min_redemption : 0 }}"
                                   class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700/50 rounded-xl
                                          text-white placeholder-gray-400 focus:border-yellow-500/50 focus:ring-2
                                          focus:ring-yellow-500/20 transition-all">
                            <div class="absolute right-3 top-3 text-gray-400 text-sm">DCoins</div>
                        </div>

                        <!-- Exchange Rate Calculation Display -->
                        @if($currencyData && $redeemAmount)
                            <div
                                class="bg-gradient-to-r from-green-500/10 to-blue-500/10 border border-green-500/30 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white font-semibold">You will receive:</p>
                                        <p class="text-green-400 text-xl font-bold">
                                            {{ $currencyData->symbol }}{{ number_format($this->equivalentAmount, 2) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-400 text-sm">Exchange Rate:</p>
                                        <p class="text-yellow-400 font-medium">
                                            1 DCoin
                                            = {{ $currencyData->symbol }}{{ number_format($currencyData->exchange_rate, 4) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <p class="text-xs text-gray-400">
                            Minimum: {{ $currencyData ? $currencyData->min_redemption : 0 }} DCoins |
                            Available: {{ number_format($totalRewards, 0) }} DCoins
                            @if($currencyData)
                                | Rate: 1 DCoin
                                = {{ $currencyData->symbol }}{{ number_format($currencyData->exchange_rate, 4) }}
                            @endif
                        </p>
                        @error('redeemAmount') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>


                    @if($currencyData && $currencyData->isFiat())
                        <!-- Bank Details for Fiat Currencies -->
                        <div class="space-y-4 bg-gray-800/30 rounded-xl p-4">
                            <h4 class="font-semibold text-white flex items-center gap-2">
                                <i data-lucide="building-2" class="w-5 h-5 text-yellow-400"></i>
                                Bank Account Details
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-300">Account Name</label>
                                    <input type="text" wire:model.live="accountName"
                                           placeholder="Full account name"
                                           class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700/50 rounded-xl
                                                                          text-white placeholder-gray-400 focus:border-yellow-500/50
                                                                          focus:ring-2 focus:ring-yellow-500/20 transition-all">
                                    @error('accountName') <p
                                        class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-300">Account Number</label>
                                    <input type="text" wire:model.live="accountNumber"
                                           placeholder="Account number"
                                           class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700/50 rounded-xl
                                                                          text-white placeholder-gray-400 focus:border-yellow-500/50
                                                                          focus:ring-2 focus:ring-yellow-500/20 transition-all">
                                    @error('accountNumber') <p
                                        class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-300">Bank Name</label>
                                @if($currencyData->hasBanks())
                                    <select wire:model.live="bankName"
                                            class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700/50 rounded-xl
                                                                           text-white focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                                        <option value="">Select Bank</option>
                                        @foreach($currencyData->banks_list as $bank)
                                            <option value="{{ $bank }}">{{ $bank }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" wire:model.live="bankName"
                                           placeholder="Enter bank name"
                                           class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700/50 rounded-xl
                                                                          text-white placeholder-gray-400 focus:border-yellow-500/50
                                                                          focus:ring-2 focus:ring-yellow-500/20 transition-all">
                                @endif
                                @error('bankName') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    @if($currencyData && $currencyData->isCrypto())
                        <!-- Crypto Wallet Details -->
                        <div class="space-y-4 bg-gray-800/30 rounded-xl p-4">
                            <h4 class="font-semibold text-white flex items-center gap-2">
                                <i data-lucide="wallet" class="w-5 h-5 text-yellow-400"></i>
                                {{ $currencyData->code }} Wallet Details
                            </h4>

                            <div class="space-y-4">
                                @if($currencyData->hasNetworks())
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-300">Network</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($currencyData->networks_list as $network => $label)
                                                <label class="relative cursor-pointer">
                                                    <input type="radio" wire:model.live="selectedNetwork"
                                                           value="{{ $network }}"
                                                           class="sr-only peer">
                                                    <div class="p-3 bg-gray-700/40 border border-gray-600/50 rounded-lg
                                                                                       peer-checked:border-yellow-500/50 peer-checked:bg-yellow-500/10
                                                                                       hover:border-gray-500/50 transition-all text-center">
                                                        <p class="text-white font-medium text-sm">{{ $network }}</p>
                                                        <p class="text-gray-400 text-xs">{{ $label }}</p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('selectedNetwork') <p
                                            class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @endif

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-300">Wallet Address</label>
                                    <input type="text" wire:model.live="walletAddress"
                                           placeholder="Enter your {{ $currencyData->code }} wallet address"
                                           class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700/50 rounded-xl
                                                                          text-white placeholder-gray-400 focus:border-yellow-500/50
                                                                          focus:ring-2 focus:ring-yellow-500/20 transition-all font-mono text-sm">
                                    @error('walletAddress') <p
                                        class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    <p class="text-xs text-gray-400">⚠️ Double-check your address. Transactions cannot
                                        be reversed.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                @if(!$this->canSubmit) disabled @endif
                                class="w-full bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500
                                                               hover:to-yellow-400 disabled:from-gray-600 disabled:to-gray-500
                                                               disabled:cursor-not-allowed text-black font-bold py-4 rounded-xl
                                                               transition-all duration-300 flex items-center justify-center gap-3">
                            <div wire:loading.remove>
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </div>
                            <div wire:loading
                                 class="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                            <span wire:loading.remove>Submit Redemption Request</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>

                    <!-- Terms -->
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-blue-300">
                                <p class="font-semibold mb-1">Important Notes:</p>
                                <ul class="space-y-1 text-blue-200">
                                    <li>• Processing time: 24-48 hours</li>
                                    <li>• Minimum redemption: {{ $currencyData ? $currencyData->min_redemption : 30 }}
                                        DCoins
                                    </li>
                                    @if($currencyData && $redeemAmount)
                                        <li>• You will
                                            receive: {{ $currencyData->symbol }}{{ number_format($this->equivalentAmount, 2) }}</li>
                                    @endif
                                    <li>• Ensure all details are correct before submitting</li>
                                    <li>• Crypto transactions cannot be reversed</li>
                                </ul>
                                @if($currencyData && $currencyData->instructions)
                                    <p class="mt-2 text-blue-200">{{ $currencyData->instructions }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
