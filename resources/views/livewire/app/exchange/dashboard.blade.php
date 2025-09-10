<div>

    <x-slot name="header">
        <header class="bg-black backdrop-blur-sm top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="lg:hidden flex items-center space-x-4">
                    <a href="{{route('profile')}}" class="p-1 rounded-full bg-gray-800">
                        <i data-lucide="user"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Welcome,</p>
                        <p class="font-bold text-sm text-white">{{ auth()->user()->username }} !</p>
                    </div>
                </div>
                <div class="lg:hidden flex items-center space-x-2">
                    <button id="openZohoChat" type="button"
                            class="p-1 rounded-full bg-gray-800 hover:bg-gray-700 hover:text-[#E1B362] transition-all">
                        <i data-lucide="headphones"></i>
                    </button>


                    {{--                    <button--}}
                    {{--                        class="p-1 rounded-full bg-gray-800 hover:bg-gray-700 hover:text-[#E1B362] transition-all">--}}
                    {{--                        <i data-lucide="bell"></i>--}}
                    {{--                    </button>--}}
                </div>

                <div class="hidden lg:block">
                    <h1 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->username }}!</h1>
                    <p class="text-gray-400 text-sm mt-1">Transact at the speed of light..</p>
                </div>


            </div>
        </header>
    </x-slot>

    <div>

        <div class="flex items-center justify-center mb-6">
            <div class="inline-flex bg-gray-800 rounded-full p-1 shadow-lg border border-gray-700">
                <button
                    wire:click="setActiveTab('exchange')"
                    class="px-6 py-2 rounded-full font-semibold text-sm transition-all duration-200
                        {{ $activeTab === 'exchange' ? 'bg-[#E1B362] text-gray-900 shadow-md' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i data-lucide="repeat" class="inline-block w-4 h-4 mr-2 align-middle"></i>
                    Exchange
                </button>
                <button
                    wire:click="setActiveTab('wallet')"
                    class="px-6 py-2 rounded-full font-semibold text-sm transition-all duration-200
                        {{ $activeTab === 'wallet' ? 'bg-[#E1B362] text-gray-900 shadow-md' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i data-lucide="landmark" class="inline-block w-4 h-4 mr-2 align-middle"></i>
                    Wallet
                </button>
            </div>
        </div>

        {{-- exchange content --}}
        @if($activeTab === 'exchange')

            <div class="mx-auto p-3 md:p-8 rounded-2xl text-white glassmorphism mb-10">

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-gray-400">Simple, fast, and secure exchange.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between md:space-x-4 space-y-4 md:space-y-0">
                    {{-- Send Section --}}
                    <div class="bg-black p-3 md:p-4 rounded-xl border border-gray-600 w-full">
                        <div class="flex justify-between items-center mb-1 md:mb-2">
                            <label class="text-xs md:text-sm font-medium text-gray-400">You Send</label>
                        </div>

                        <div class="flex items-center space-x-2 md:space-x-4">
                            <input id="baseAmountFormatted" type="text"
                                   class="w-full bg-transparent text-2xl md:text-3xl font-bold focus:outline-none placeholder-gray-500"
                                   placeholder="0.0" inputmode="decimal"
                                   oninput="(function(el){ let raw = el.value.replace(/,/g,''); if(raw===''){ document.getElementById('baseAmount').value=''; document.getElementById('baseAmount').dispatchEvent(new Event('input')); el.value=''; return;} raw = raw.replace(/[^\d.]/g,''); const parts = raw.split('.'); raw = parts.shift() + (parts.length ? '.' + parts.join('') : ''); document.getElementById('baseAmount').value = raw; document.getElementById('baseAmount').dispatchEvent(new Event('input')); const formatted = raw.includes('.') ? raw.split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g,',') + '.' + raw.split('.').slice(1).join('') : raw.replace(/\B(?=(\d{3})+(?!\d))/g,','); el.value = formatted; })(this)"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46"
                                   onkeydown="if(['e','+','-'].includes(event.key)) event.preventDefault();">

                            <input type="hidden" id="baseAmount" wire:model.live="baseAmount">

                            <div
                                class="flex-shrink-0 flex items-center space-x-1 md:space-x-2 p-2 bg-gray-800 rounded-lg border border-gray-600">
                                <img src="{{ $currencies->where('code', $baseCurrency)->first()->flag }}"
                                     class="w-5 h-5 md:w-6 md:h-6 rounded-full" alt="">
                                <select wire:model.live="baseCurrency"
                                        class="bg-transparent text-sm md:text-base font-semibold focus:outline-none appearance-none px-2 md:px-4">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}"
                                            {{ $currency->code === $quoteCurrency ? 'disabled' : '' }}>
                                            {{ $currency->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Swap Button --}}
                    <div class="flex-shrink-0 -my-2 mb-3 md:my-0">
                        <button wire:click="swapCurrencies"
                                class="p-2 md:p-3 bg-gray-800 hover:bg-[#E1B362] text-white transition-all duration-300 rounded-full border-4 border-gray-800 transform md:rotate-0 hover:rotate-180">
                            <i data-lucide="arrow-up-down" class="w-4 h-4 md:w-5 md:h-5"></i>
                        </button>
                    </div>

                    {{-- Receive Section --}}
                    <div class="bg-black p-3 md:p-4 rounded-xl border border-gray-600 w-full">
                        <div class="flex justify-between items-center mb-1 md:mb-2">
                            <label class="text-xs md:text-sm font-medium text-gray-400">You Receive</label>
                        </div>
                        <div class="flex items-center space-x-2 md:space-x-4">
                            <input type="text"
                                   x-data="{
                                              quoteAmount: @entangle('quoteAmount'),
                                              formatNumber(value) {
                                                  if (value === null || value === '' || isNaN(parseFloat(value))) {
                                                      return '0.0';
                                                  }
                                                  let number = parseFloat(value);
                                                  return number.toLocaleString('en-US', {
                                                      minimumFractionDigits: 2,
                                                      maximumFractionDigits: 2
                                                  });
                                              }
                                          }"
                                   x-effect="$el.value = formatNumber(quoteAmount)"
                                   readonly
                                   class="w-full bg-transparent text-2xl md:text-3xl font-bold focus:outline-none placeholder-gray-500"
                                   placeholder="0.0">

                            <div
                                class="flex-shrink-0 flex items-center space-x-1 md:space-x-2 p-2 bg-gray-800 rounded-lg border border-gray-600">
                                <img src="{{ $currencies->where('code', $quoteCurrency)->first()->flag }}"
                                     class="w-5 h-5 md:w-6 md:h-6 rounded-full" alt="">
                                <select wire:model.live="quoteCurrency"
                                        class="bg-transparent text-sm md:text-base font-semibold focus:outline-none appearance-none px-2 md:px-4">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}"
                                            {{ $currency->code === $baseCurrency ? 'disabled' : '' }}>
                                            {{ $currency->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button wire:click.prevent="exchangeNow"
                        class="w-full py-3 px-6 rounded-xl font-semibold text-lg text-center block mt-5
                    {{ $baseAmount < 1 ? 'bg-[#E1B362] text-white pointer-events-none' : 'brand-gradient text-white hover:opacity-90 transition-all' }}"
                    {{ $baseAmount < 1 ? 'disabled' : '' }}>
                    Exchange Now
                </button>
            </div>

        @elseif($activeTab === 'wallet')
            {{-- Wallet section --}}
            <div class="p-1 lg:p-0 lg:py-2">
                <!-- Balance Card -->
                <div
                    class="p-6 md:p-8 brand-gradient rounded-2xl text-white shadow-lg mb-8 glassmorphism brand-border">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-white">Total Balance</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <h1
                                    id="balance-amount"
                                    class="text-3xl md:text-4xl font-bold">
                                    {{ $walletCurrencySymbol }}{{ number_format($walletBalance, 2) }}
                                </h1>
                                <button
                                    id="toggle-visibility"
                                    class="text-white hover:text-white">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-right hidden lg:block">
                            <p class="text-sm text-whte">Account Number</p>
                            <p class="font-semibold text-lg">{{auth()->user()->virtualBankAccount->account_number}}</p>
                            <p class="text-sm text-whte">{{auth()->user()->virtualBankAccount->bank_name}}</p>
                        </div>
                    </div>
                    <div class="mt-8 grid grid-cols-2 gap-4">
                        <button
                            onclick="window.location.href='{{ route('wallet.deposit.create') }}'"

                            class="w-full bg-gray-700/50 hover:bg-gray-700/50 text-white py-3 px-4 rounded-xl font-semibold text-base transition-all flex items-center justify-center space-x-2">
                            <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
                            <span>Deposit</span>
                        </button>
                        <button
                            onclick="window.location.href='{{ route('wallet.transfers.create') }}'"
                            class="w-full bg-gray-700/50 hover:bg-gray-700/50 text-white py-3 px-4 rounded-xl font-semibold text-base transition-all flex items-center justify-center space-x-2">
                            <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            <span>Transfer</span>
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-1">
                    <div class="flex gap-4 text-center overflow-x-auto scrollbar-hide pb-2 justify-center">
                        <a
                            href="{{route('wallet.airtime')}}"
                            class="flex-shrink-0 flex flex-col items-center space-y-2 p-2 transition-all hover-scale min-w-[60px]">
                            <div class="p-3 bg-gray-700 rounded-full text-[#E1B362]">
                                <i data-lucide="smartphone"></i>
                            </div>
                            <span class="text-xs font-medium">Airtime</span>
                        </a>
                        <a
                            href="{{route('wallet.mobile-data')}}"
                            class="flex-shrink-0 flex flex-col items-center space-y-2 p-2 transition-all hover-scale min-w-[60px]">
                            <div class="p-3 bg-gray-700 rounded-full text-[#E1B362]">
                                <i data-lucide="send"></i>
                            </div>
                            <span class="text-xs font-medium">Data</span>
                        </a>
                        <a
                            href="{{route('wallet.buy-power')}}"
                            class="flex-shrink-0 flex flex-col items-center space-y-2 p-2 transition-all hover-scale min-w-[60px]">
                            <div class="p-3 bg-gray-700 rounded-full text-[#E1B362]">
                                <i data-lucide="lightbulb"></i>
                            </div>
                            <span class="text-xs font-medium">Power</span>
                        </a>

                        <a
                            href="{{route('wallet.cable-tv')}}"
                            class="flex-shrink-0 flex flex-col items-center space-y-2 p-2 transition-all hover-scale min-w-[60px]">
                            <div class="p-3 bg-gray-700 rounded-full text-[#E1B362]">
                                <i data-lucide="receipt"></i>
                            </div>
                            <span class="text-xs font-medium">TV</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-1 lg:p-0 lg:py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-3">

                {{-- recent transactions  --}}
                <div class="lg:col-span-2 lg:block">
                    <div class="">
                        @if($activeTab === 'exchange')
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
                                    <a
                                        href="{{route('exchange.transactions')}}"
                                        class="text-sm font-medium text-[#E1B362] hover:text-[#d4a55a]"
                                    >View All</a
                                    >
                                </div>
                                <div class="space-y-3">

                                    @if($exchangeTransactions->isEmpty())
                                        <p class="text-gray-400 text-sm">No recent exchanges found.</p>
                                    @else
                                        @foreach($exchangeTransactions as $transaction)
                                            <div
                                                class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-900">
                                                <div
                                                    class="{{ $transaction->status === 'rejected' ? 'w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center' : 'w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center' }}">
                                                    <i data-lucide="{{ $transaction->status === 'rejected' ? 'x' : 'repeat' }}"
                                                       class="w-5 h-5 {{ $transaction->status === 'rejected' ? 'text-red-500' : 'text-blue-500' }}"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-sm {{ $transaction->status === 'rejected' ? 'text-red-400' : 'text-white' }}">
                                                        Exchanged
                                                        {{ $transaction->fromCurrency->code }} to
                                                        {{ $transaction->toCurrency->code }}</p>
                                                    <p class="{{ $transaction->status === 'rejected' ? 'text-red-300 text-xs' : 'text-xs text-gray-400' }}">{{ $transaction->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <p class="font-semibold {{ $transaction->status === 'rejected' ? 'text-red-500' : 'text-green-500' }}">
                                                    {{ $transaction->fromCurrency->symbol }} {{ number_format($transaction->amount_from, 2) }}</p>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        @endif

                        @if($activeTab === 'wallet')
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
                                    <a
                                        href="{{route('exchange.transactions')}}"
                                        class="text-sm font-medium text-[#E1B362] hover:text-[#d4a55a]"
                                    >View All</a
                                    >
                                </div>
                                <div class="space-y-3">

                                    @if($walletTransactions->isEmpty())
                                        <p class="text-gray-400 text-sm">No recent transactions found.</p>
                                    @else
                                        @foreach($walletTransactions as $transaction)
                                            <div
                                                class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-900">
                                                <div
                                                    class="w-10 h-10 {{ $transaction->direction === 'credit' ? 'bg-green-500/20' : 'bg-red-500/20' }} rounded-full flex items-center justify-center">
                                                    <i data-lucide="{{ $transaction->direction === 'credit' ? 'arrow-down' : 'arrow-up' }}"
                                                       class="w-5 h-5 {{ $transaction->direction === 'credit' ? 'text-green-500' : 'text-red-500' }}"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-sm text-white"> {{  strtoupper($transaction->type) }}</p>
                                                    <p class="text-xs text-gray-400">{{ $transaction->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <p class="font-semibold {{ $transaction->direction === 'credit' ? 'text-green-500' : 'text-red-500' }}">
                                                    {{ $transaction->direction === 'credit' ? '+' : '-' }}
                                                    ₦ {{ number_format($transaction->amount, 2) }}
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- market stats--}}
                <div class=" hidden lg:block">
                    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-8">
                        <h4 class="font-bold text-white mb-4">Quick Stats</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Market Cap</span>
                                <span class="font-semibold text-white">$1.65T</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">24h Volume</span>
                                <span class="font-semibold text-white">$89.2B</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">BTC Dominance</span>
                                <span class="font-semibold text-white">52.3%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Fear & Greed</span>
                                <span class="font-semibold text-green-500">Greed (75)</span>
                            </div>
                            <div class="w-full bg-gray-600 rounded-full h-2 mt-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Exchange Rates and Resources -->

            @if($activeTab === 'exchange')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-2">
                    <div class="lg:col-span-2 bg-gray-950 p-0 ">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg text-white">Exchange Rates</h3>
                            <div class="hidden lg:flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full pulse-animation"></div>
                                <span class="text-xs text-gray-400">Live rates</span>
                            </div>
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($processedPairs as $pair)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-900 rounded-lg hover:bg-gray-900 transition-all hover-scale">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex -space-x-2">
                                            <img src="{{ $pair->firstCurrency->flag }}"
                                                 class="w-8 h-8 rounded-full border-2 border-gray-800"
                                                 alt="{{ $pair->firstCurrency->code }}">
                                            <img src="{{ $pair->secondCurrency->flag }}"
                                                 class="w-8 h-8 rounded-full border-2 border-gray-800"
                                                 alt="{{ $pair->secondCurrency->code }}">
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $pair->firstCurrency->code }}
                                                /{{ $pair->secondCurrency->code }}</p>
                                            <p class="text-xs text-gray-400">{{ $pair->firstCurrency->name }}</p>
                                        </div>
                                    </div>
                                    <div class="w-[120px] text-left justify-start">
                                        <div class="">
                                            <span class="text-xs text-gray-400 w-10 text-left">Buy:</span>
                                            <span
                                                class="text-green-500 text-sm font-medium">{{ $pair->secondCurrency->symbol }}{{ number_format($pair->buyRate, 2) }}</span>
                                        </div>
                                        <div class=" mt-1">
                                            <span class="text-xs text-gray-400 w-10 text-left">Sell:</span>
                                            <span
                                                class="text-red-500 text-sm font-medium">{{ $pair->secondCurrency->symbol }}{{ number_format($pair->sellRate, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="hidden lg:block">
                            <div class="bg-gray-900 text-white p-6 rounded-lg brand-border">
                                <p class="text-sm font-semibold text-[#E1B362]">Mobile App</p>
                                <h4 class="text-lg font-bold mt-2">Exchange On The Go</h4>
                                <img src="https://dcashwallet.com/assets/images/app/app-image-6.png"
                                     class="w-32 h-auto mx-auto my-4">
                            </div>
                        </div>
                        <div class="bg-gray-800 p-6 rounded-lg text-center border border-gray-700">
                            <h4 class="font-bold text-lg text-white">Download the Dcash app</h4>
                            <div class="flex justify-center space-x-2 mt-4">
                                <a href="#"><img
                                        src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                                        class="h-10"></a>
                                <a href="#"><img
                                        src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"
                                        class="h-10"></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeTab !== 'exchange')

                <!-- Weekly Flow Stats -->
                <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 mt-5">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="font-bold text-white">Weekly Flow</h4>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center space-x-2 text-sm font-medium text-gray-300 bg-gray-800 px-3 py-1 rounded-lg hover:bg-gray-700">
                                <span>This Week</span>
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-10"
                                 style="display: none;">
                                <a href="#" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">This Week</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-400 cursor-not-allowed">Last
                                    Week</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-400 cursor-not-allowed">This
                                    Month</a>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-sm text-gray-400">Money In</span>
                            </div>
                            <p class="text-xl font-bold text-white">{{ $walletCurrencySymbol }}{{ number_format($weeklyInflow, 2) }}</p>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-sm text-gray-400">Money Out</span>
                            </div>
                            <p class="text-xl font-bold text-white">{{ $walletCurrencySymbol }}{{ number_format($weeklyOutflow, 2) }}</p>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="relative h-40">
                        <!-- Y-Axis Labels and Grid Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between h-full">
                            <div class="flex items-center">
                                <span
                                    class="text-xs text-gray-400 w-12 text-right pr-2">{{ $this->formatNumberShort($maxWeeklyFlow) }}</span>
                                <div class="flex-1 border-t border-gray-700 border-dashed"></div>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="text-xs text-gray-400 w-12 text-right pr-2">{{ $this->formatNumberShort($maxWeeklyFlow / 2) }}</span>
                                <div class="flex-1 border-t border-gray-700 border-dashed"></div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-400 w-12 text-right pr-2">0</span>
                                <div class="flex-1 border-t border-gray-700"></div>
                            </div>
                        </div>

                        <!-- Bars -->
                        <div class="absolute inset-0 ml-12 flex justify-around items-end">
                            @foreach($weeklyChartData as $day => $data)
                                <div class="flex flex-col items-center w-full h-full pt-5">
                                    <div class="flex-grow flex items-end justify-center w-full space-x-1">
                                        @if($data['inflow'] > 0 || $data['outflow'] > 0)
                                            <div class="bg-green-500 rounded-t-md w-1/3"
                                                 style="height: {{ $maxWeeklyFlow > 0 ? ($data['inflow'] / $maxWeeklyFlow) * 100 : 0 }}%;"></div>
                                            <div class="bg-blue-500 rounded-t-md w-1/3"
                                                 style="height: {{ $maxWeeklyFlow > 0 ? ($data['outflow'] / $maxWeeklyFlow) * 100 : 0 }}%;"></div>
                                        @else
                                            {{-- Render empty space to maintain layout --}}
                                            <div class="w-1/3"></div>
                                            <div class="w-1/3"></div>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400 mt-2">{{ $day }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{--                <!-- Mobile-only Download Card -->--}}
                {{--                <div class="lg:hidden mt-1">--}}
                {{--                    <div class="bg-gray-800 p-6 rounded-lg text-center border border-gray-700">--}}
                {{--                        <h4 class="font-bold text-lg text-white">Download the Dcash app</h4>--}}
                {{--                        <div class="flex justify-center space-x-2 mt-4">--}}
                {{--                            <a href="#"><img--}}
                {{--                                    src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"--}}
                {{--                                    class="h-10" alt="App Store"></a>--}}
                {{--                            <a href="#"><img--}}
                {{--                                    src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"--}}
                {{--                                    class="h-10" alt="Google Play"></a>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            @endif
            <div class="mb-10 hidden lg:block">
                <h3 class="font-bold text-lg mb-4 text-white mt-5">Resources</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-[#E1B362] transition-all">
                        <div class="h-32 bg-gray-700 flex items-center justify-center">
                            <img src="https://bitcoin.org/img/icons/logotop.svg" class="h-16">
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-white">Dcash Wallet Blog</h4>
                            <p class="text-sm text-gray-400 mt-2">Coins announcements, company updates, and
                                industry
                                perspectives</p>
                        </div>
                    </div>
                    <div
                        class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-[#E1B362] transition-all">
                        <div class="h-32 bg-gray-700 flex items-center justify-center">
                            <img
                                src="https://ethereum.org/_next/image/?url=/_next/static/media/finance_transparent.a4abc782.png&w=1920&q=75"
                                class="h-16">
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-white">DCash Wallet Academy</h4>
                            <p class="text-sm text-gray-400 mt-2">A platform designed to provide free,
                                high-quality
                                crypto
                                education for everyone</p>
                        </div>
                    </div>

                    <div
                        class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-[#E1B362] transition-all">
                        <div class="h-32 bg-gray-700 flex items-center justify-center">
                            <img
                                src="https://ethereum.org/_next/image/?url=/_next/static/media/finance_transparent.a4abc782.png&w=1920&q=75"
                                class="h-16">
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-white">DCash Wallet Academy</h4>
                            <p class="text-sm text-gray-400 mt-2">A platform designed to provide free,
                                high-quality
                                crypto
                                education for everyone</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        @if($showTermsModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
                <div class="bg-gray-800 p-6 m-3 rounded-lg max-w-2xl w-full max-h-[90vh] flex flex-col">
                    <h2 class="text-2xl font-bold mb-1 text-[#E1B362]">WALLET CREATION</h2>
                    <h2 class="text-xl font-bold mb-4 text-white">Terms and Conditions</h2>
                    <hr class="my-2 border-t border-white border-opacity-20">
                    <div class="mb-4 text-gray-200 overflow-y-auto" style="max-height:50vh;">
                        <p>Please read these terms and conditions carefully before using Our Service.</p>

                        <h2 class="text-lg font-semibold mt-4 mb-2 text-[#E1B362]">Service Description</h2>
                        <p>Our wallet system allows you to securely store, manage, and transfer digital assets.
                            The
                            Service includes account creation, transaction processing, balance management, and
                            security features to protect your digital wallet.</p>

                        <h2 class="text-lg font-semibold mt-4 mb-2 text-[#E1B362]">Account Security</h2>
                        <p>You are solely responsible for maintaining the confidentiality of your account
                            credentials, including passwords, PINs, and recovery phrases. Any transactions made
                            using your account will be considered authorized by you. We recommend using strong
                            passwords and enabling all available security features.</p>

                        <h2 class="text-lg font-semibold mt-4 mb-2 text-[#E1B362]">Transaction
                            Responsibility</h2>
                        <p>All transactions are final and irreversible once confirmed. You must verify all
                            transaction details before confirmation. We are not responsible for transactions
                            sent to
                            incorrect addresses or amounts due to user error. Please double-check all recipient
                            information before proceeding.</p>

                        <h2 class="text-lg font-semibold mt-4 mb-2 text-[#E1B362]">Service Availability</h2>
                        <p>While we strive for continuous service availability, we cannot guarantee
                            uninterrupted
                            access. Maintenance, updates, or technical issues may temporarily affect service. We
                            will provide advance notice when possible for scheduled maintenance.</p>

                        <h2 class="text-lg font-semibold mt-4 mb-2 text-[#E1B362]">Privacy and Data
                            Protection</h2>
                        <p>We implement industry-standard security measures to protect your personal information
                            and
                            transaction data. We do not share your personal information with third parties
                            except as
                            required by law or to process your transactions.</p>

                        <h2 class="text-lg font-semibold mt-4 mb-2 text-[#E1B362]">Contact Support</h2>
                        <p>If you have any questions about these Terms and Conditions or need assistance with
                            your
                            wallet, You can contact us by email: support@walletsystem.com</p>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button wire:click="$set('showTermsModal', false)"
                                class="px-4 py-2 bg-gray-600 text-white rounded">
                            Cancel
                        </button>
                        <button wire:click="acceptTermsAndCreateWallet" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-[#E1B362] text-gray-900 font-semibold rounded flex items-center justify-center w-48">
                                    <span wire:loading.remove
                                          wire:target="acceptTermsAndCreateWallet">Create Wallet</span>
                            <span wire:loading wire:target="acceptTermsAndCreateWallet">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-900"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif



        <!-- Success Modal -->
        @if($showSuccessModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
                <div class="bg-gray-800 p-6 m-3 rounded-lg max-w-sm w-full text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-10 h-10 text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold mb-2 text-white">Success!</h2>
                    <p class="text-gray-300 mb-6">{{ $modalMessage }}</p>
                    <button wire:click="closeSuccessModal"
                            class="w-full px-4 py-2 bg-[#E1B362] text-gray-900 font-semibold rounded">
                        Continue
                    </button>
                </div>
            </div>
        @endif

        <!-- Error Modal -->
        @if($showErrorModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
                <div class="bg-gray-800 p-6 m-3 rounded-lg max-w-sm w-full text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center">
                            <i data-lucide="x" class="w-10 h-10 text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold mb-2 text-white">Error</h2>
                    <p class="text-gray-300 mb-6">{{ $modalMessage }}</p>
                    <button wire:click="closeErrorModal"
                            class="w-full px-4 py-2 bg-gray-600 text-white rounded">
                        Close
                    </button>
                </div>
            </div>
        @endif


    </div>

    @push('scripts')
        <script>
            // Zoho SalesIQ configuration
            window.$zoho = window.$zoho || {};
            $zoho.salesiq = $zoho.salesiq || {
                widgetcode: "siq51f05af08ede1ce8f9d01bd2e895d43046f039e0d2129498c89fa3fb2251ab81",
                values: {},
                ready: function () {
                    // Hide the default Zoho floating chat button
                    $zoho.salesiq.floatbutton.visible('hide');

                    // Find your custom button
                    const chatButton = document.getElementById('openZohoChat');

                    // Add a click event listener to your custom button
                    if (chatButton) {
                        chatButton.addEventListener('click', function () {
                            // API call to open the chat window
                            $zoho.salesiq.floatwindow.visible('show');
                        });
                    }
                }
            };
        </script>
        {{-- The main Zoho SalesIQ script --}}
        <script id="zsiqscript"
                src="https://salesiq.zoho.com/widget?wc=siq51f05af08ede1ce8f9d01bd2e895d43046f039e0d2129498c89fa3fb2251ab81"
                defer></script>
    @endpush
</div>
