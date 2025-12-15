<div>

    <header class="bg-black backdrop-blur-sm top-0 z-50 border-b border-gray-700/80 relative">
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
                    <i data-lucide="headset"></i>
                </button>

                <!-- Mobile bell button that triggers the same dropdown -->
                <button @click="$refs.notificationButton.click()"
                        class="relative p-1 rounded-full bg-gray-800 hover:bg-gray-700 hover:text-[#E1B362] transition-all">
                    <i data-lucide="bell"></i>
                    <span x-show="$store.notifications.unreadCount > 0"
                          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-medium text-[10px]"
                          x-text="$store.notifications.unreadCount > 99 ? '99+' : $store.notifications.unreadCount"></span>
                </button>
            </div>

            <div class="hidden lg:block">
                <h1 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->username }}!</h1>
                <p class="text-gray-400 text-sm mt-1">Transact at the speed of light..</p>

            </div>

            <div class="hidden lg:flex items-center space-x-4">
                <div class="relative"
                     x-data="{ open: false }"
                     x-init="$store.notifications = { unreadCount: @entangle('unreadNotifications') }">

                    <button
                        class="relative p-1 rounded-full bg-gray-800 hover:bg-gray-700 hover:text-[#E1B362] transition-all">
                        <i data-lucide="bell"></i>
                        <span x-show="$store.notifications.unreadCount > 0"
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium"
                              x-text="$store.notifications.unreadCount > 99 ? '99+' : $store.notifications.unreadCount"></span>
                    </button>


                </div>
            </div>


        </div>
    </header>

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


        @if($activeTab === 'wallet')

            <!-- Service Maintenance Notice -->

            <div class="bg-red-500 text-white px-6 py-3 flex items-center justify-between shadow-lg mb-5 rounded-lg">
                <div class="flex items-center gap-2 overflow-hidden w-full relative" style="height: 28px;">
                    <div class="w-full overflow-hidden relative">
                        <div class="inline-block whitespace-nowrap animate-marquee">
                            Notice: Your virtual deposit account details will soon change from Sterling Bank to
                            SafeHaven
                            Bank.
                            Please note that your funds remain safe with DCASH, only the virtual deposit account details
                            will change.
                        </div>
                    </div>
                </div>
                <button @click="show = false" class="ml-4 text-whte hover:text-gray-700 flex-shrink-0">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <style>
                    @keyframes marquee {
                        0% {
                            transform: translateX(0%);
                        }
                        100% {
                            transform: translateX(-100%);
                        }
                    }

                    .animate-marquee {
                        animation: marquee 15s linear infinite;
                    }
                </style>
            </div>
        @endif

        {{--        @if($activeTab === 'wallet')--}}
        {{--            <div--}}
        {{--                x-data="{ showNotice: true }"--}}
        {{--                x-show="showNotice"--}}
        {{--                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"--}}
        {{--                style="backdrop-filter: blur(2px);"--}}
        {{--            >--}}
        {{--                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 text-center relative">--}}
        {{--                    <button--}}
        {{--                        @click="showNotice = false"--}}
        {{--                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 transition"--}}
        {{--                    >--}}
        {{--                        <i data-lucide="x" class="w-6 h-6"></i>--}}
        {{--                    </button>--}}
        {{--                    <div class="flex justify-center mb-4">--}}
        {{--                        <div class="bg-red-500 rounded-full w-14 h-14 flex items-center justify-center shadow-lg">--}}
        {{--                            <i data-lucide="alert-triangle" class="w-8 h-8 text-white"></i>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <h2 class="text-xl font-bold mb-2 text-gray-900">Important Notice</h2>--}}
        {{--                    <p class="text-gray-700 mb-2">--}}
        {{--                        Your virtual deposit account details will soon change from <span class="font-semibold">Sterling Bank</span>--}}
        {{--                        to <span class="font-semibold">SafeHaven Bank</span>.--}}
        {{--                    </p>--}}
        {{--                    <p class="text-gray-500 text-sm">Please note that your funds remain safe with DCASH, only the--}}
        {{--                        virtual deposit account details will change.</p>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        @endif--}}

        {{-- exchange content --}}
        @if($activeTab === 'exchange')

            <div class="mx-auto p-3 md:p-8 rounded-2xl text-white glassmorphism mb-3">

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
                        <div x-data="{ showBalance: true }">
                            <p class="text-sm text-white">Total Balance</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <h1
                                    id="balance-amount"
                                    class="text-3xl md:text-4xl font-bold"
                                    x-show="showBalance">
                                    {{ $walletCurrencySymbol }}{{ number_format($walletBalance, 2) }}
                                </h1>
                                <h1
                                    class="text-3xl md:text-4xl font-bold text-gray-500"
                                    x-show="!showBalance">
                                    ••••••
                                </h1>
                                <button
                                    @click="showBalance = !showBalance"
                                    class="text-white hover:text-[#E1B362] transition-colors">
                                    <i data-lucide="eye" class="w-5 h-5" x-show="showBalance"></i>
                                    <i data-lucide="eye-off" class="w-5 h-5" x-show="!showBalance"></i>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 mb-1">

                {{-- slideshow on mobile --}}
                <div class="lg:hidden mb-2">
                    @if($sliderAnnouncements->isNotEmpty())
                        <div class=" p-2 rounded-lg  mb-0 mt-0">
                            @foreach($sliderAnnouncements as $announcement)
                                @if($announcement->content && isset($announcement->content['paths']) && !empty($announcement->content['paths']))
                                    <div x-data="{
                                            currentSlide: 0,
                                            slides: {{ count($announcement->content['paths']) }},
                                            images: @js($announcement->content['paths'])
                                         }"
                                         x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 6000)"
                                         class="relative overflow-hidden mb-0">


                                        {{-- Image Slider --}}
                                        <div class="relative">
                                            <template x-for="(image, index) in images" :key="index">
                                                <div x-show="currentSlide === index"
                                                     x-transition:enter="transition ease-out duration-500"
                                                     x-transition:enter-start="opacity-0 transform translate-x-full"
                                                     x-transition:enter-end="opacity-100 transform translate-x-0"
                                                     x-transition:leave="transition ease-in duration-500"
                                                     x-transition:leave-start="opacity-100 transform translate-x-0"
                                                     x-transition:leave-end="opacity-0 transform -translate-x-full"
                                                     class="absolute inset-0">
                                                    <img :src="`{{ Storage::url('') }}${image}`"
                                                         class="w-full h-full object-contain rounded-lg"
                                                         :alt="`{{ $announcement->title }} - Image ${index + 1}`">
                                                </div>
                                            </template>
                                            {{-- Placeholder for sizing --}}
                                            <div class="h-32 opacity-0"></div>
                                        </div>


                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- recent transactions  --}}
                <div class="lg:col-span-2 lg:block">
                    <div class="">
                        @if($activeTab === 'exchange')
                            <div>
                                @if(!empty($exchangeTransactions))
                                    <div class="flex justify-between items-center mb-4">
                                        <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
                                        <a
                                            href="{{route('exchange.transactions')}}"
                                            class="text-sm font-medium text-[#E1B362] hover:text-[#d4a55a]"
                                        >View All</a>
                                    </div>
                                @endif
                                <div class="space-y-3">

                                    @if(empty($exchangeTransactions))
                                        <div class="flex flex-col items-center justify-center p-8 text-center">
                                            <div class="w-16 h-16 mb-4 text-gray-600 animate-pulse">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.5"
                                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-300 mb-2">No Transactions
                                                Yet</h3>
                                            <p class="text-gray-500 text-sm max-w-xs">Start your journey by making your
                                                first transaction. It's quick and easy!</p>
                                            <button
                                                class="mt-4 px-4 py-2 bg-gray-800 text-[#E1B362] rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                <span>Make First Transaction</span>
                                            </button>
                                        </div>
                                    @else
                                        @foreach($exchangeTransactions as $transaction)
                                            @if($transaction['transaction_type'] === 'exchange')
                                                <a href="{{ route('exchange.receipt', ['ref' => $transaction['reference']]) }}"
                                                   class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-800 cursor-pointer transition-colors">
                                                    <div
                                                        class="{{ $transaction['status'] === 'rejected' ? 'w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center' : 'w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center' }}">
                                                        <i data-lucide="{{ $transaction['status'] === 'rejected' ? 'x' : 'repeat' }}"
                                                           class="w-5 h-5 {{ $transaction['status'] === 'rejected' ? 'text-red-500' : 'text-blue-500' }}"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="font-medium text-sm {{ $transaction['status'] === 'rejected' ? 'text-red-400' : 'text-white' }}">
                                                            Exchanged {{ $transaction['from_currency']->code }}
                                                            to {{ $transaction['to_currency']->code }}</p>
                                                        <p class="{{ $transaction['status'] === 'rejected' ? 'text-red-300 text-xs' : 'text-xs text-gray-400' }}">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</p>
                                                    </div>
                                                    <p class="font-semibold {{ $transaction['status'] === 'rejected' ? 'text-red-500' : 'text-green-500' }}">
                                                        {{ $transaction['from_currency']->symbol }} {{ number_format($transaction['amount_from'], 2) }}</p>
                                                </a>
                                            @elseif($transaction['transaction_type'] === 'bonus')
                                                <a href="{{ route('rewards.transactions.receipt', ['ref' => $transaction['id']]) }}"
                                                   class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-800 cursor-pointer transition-colors">
                                                    <div
                                                        class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                                                        <div
                                                            class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                                            <i data-lucide="gift" class="w-5 h-5 text-yellow-500"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="font-medium text-sm text-white">{{ ucfirst($transaction['type']) }}
                                                                Bonus</p>
                                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</p>
                                                        </div>
                                                        <p class="font-semibold text-yellow-500">
                                                            {{ number_format($transaction['bonus_amount'], 2) }}
                                                            DCOINS</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        @endif

                        @if($activeTab === 'wallet')
                            <div>
                                @if(!empty($walletTransactions))
                                    <div class="flex justify-between items-center mb-4">
                                        <h2 class="text-lg font-semibold text-white">Recent Transactions</h2>
                                        <a href="{{route('exchange.transactions')}}"
                                           class="text-sm font-medium text-[#E1B362] hover:text-[#d4a55a]">View All</a>
                                    </div>
                                @endif

                                <div class="space-y-3">

                                    @if(empty($walletTransactions))
                                        <div class="flex flex-col items-center justify-center p-8 text-center">
                                            <div class="w-16 h-16 mb-4 text-gray-600 animate-pulse">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.5"
                                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-300 mb-2">No Transactions
                                                Yet</h3>
                                            <p class="text-gray-500 text-sm max-w-xs">Start your journey by making your
                                                first transaction. It's quick and easy!</p>
                                            <button
                                                onclick="window.location.href='{{ route('wallet.deposit.create') }}'"
                                                class="mt-4 px-4 py-2 bg-gray-800 text-[#E1B362] rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                <span>Make First Transaction</span>
                                            </button>
                                        </div>
                                    @else
                                        @foreach($walletTransactions as $transaction)
                                            @if($transaction['transaction_type'] === 'wallet')
                                                <div
                                                    class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-900">
                                                    <div
                                                        class="w-10 h-10 {{ $transaction['direction'] === 'credit' ? 'bg-green-500/20' : 'bg-red-500/20' }} rounded-full flex items-center justify-center">
                                                        <i data-lucide="{{ $transaction['direction'] === 'credit' ? 'arrow-down' : 'arrow-up' }}"
                                                           class="w-5 h-5 {{ $transaction['direction'] === 'credit' ? 'text-green-500' : 'text-red-500' }}"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="font-medium text-sm text-white">{{ strtoupper($transaction['type'] ?? 'TRANSACTION') }}</p>
                                                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</p>
                                                    </div>
                                                    <p class="font-semibold {{ $transaction['direction'] === 'credit' ? 'text-green-500' : 'text-red-500' }}">
                                                        {{ $transaction['direction'] === 'credit' ? '+' : '-' }}
                                                        ₦{{ number_format($transaction['amount'], 2) }}
                                                    </p>
                                                </div>
                                            @elseif($transaction['transaction_type'] === 'bonus')
                                                <a href="{{ route('rewards.transactions.receipt', ['ref' => $transaction['id']]) }}"
                                                   class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-800 cursor-pointer transition-colors">
                                                    <div
                                                        class="flex items-center space-x-4 p-3 bg-gray-900 rounded-lg hover:bg-gray-900">
                                                        <div
                                                            class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                                            <i data-lucide="gift" class="w-5 h-5 text-yellow-500"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="font-medium text-sm text-white">{{ ucfirst($transaction['type']) }}
                                                                Bonus</p>
                                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</p>
                                                        </div>
                                                        <p class="font-semibold text-yellow-500">
                                                            {{ number_format($transaction['bonus_amount'], 2) }}
                                                            DCOINS</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- market stats and announcements --}}

                <div class="hidden lg:block">
                    {{-- slideshow on desktop --}}
                    @if($sliderAnnouncements->isNotEmpty())
                        <div class=" p-8 rounded-lg  mb-2 mt-8">
                            @foreach($sliderAnnouncements as $announcement)
                                @if($announcement->content && isset($announcement->content['paths']) && !empty($announcement->content['paths']))
                                    <div x-data="{
                                            currentSlide: 0,
                                            slides: {{ count($announcement->content['paths']) }},
                                            images: @js($announcement->content['paths'])
                                         }"
                                         x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 6000)"
                                         class="relative overflow-hidden mb-0">


                                        {{-- Image Slider --}}
                                        <div class="relative">
                                            <template x-for="(image, index) in images" :key="index">
                                                <div x-show="currentSlide === index"
                                                     x-transition:enter="transition ease-out duration-500"
                                                     x-transition:enter-start="opacity-0 transform translate-x-full"
                                                     x-transition:enter-end="opacity-100 transform translate-x-0"
                                                     x-transition:leave="transition ease-in duration-500"
                                                     x-transition:leave-start="opacity-100 transform translate-x-0"
                                                     x-transition:leave-end="opacity-0 transform -translate-x-full"
                                                     class="absolute inset-0">
                                                    <img :src="`{{ Storage::url('') }}${image}`"
                                                         class="w-full h-full object-contain rounded-lg"
                                                         :alt="`{{ $announcement->title }} - Image ${index + 1}`">
                                                </div>
                                            </template>
                                            {{-- Placeholder for sizing --}}
                                            <div class="h-32 opacity-0"></div>
                                        </div>

                                        <div class="flex justify-center space-x-2 mt-3" x-show="slides > 1">
                                            <template x-for="(image, index) in images" :key="index">
                                                <button @click="currentSlide = index"
                                                        :class="currentSlide === index ? 'bg-[#E1B362]' : 'bg-gray-600'"
                                                        class="w-2 h-2 rounded-full transition-colors"></button>
                                            </template>
                                        </div>


                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
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
                                    class="flex items-center justify-between p-4 bg-gray-900 rounded-lg hover:bg-gray-900 transition-all hover-scale"
                                    x-data="{
                                        buyRate: {{ $pair->buyRate }},
                                        sellRate: {{ $pair->sellRate }},
                                        originalBuyRate: {{ $pair->buyRate }},
                                        originalSellRate: {{ $pair->sellRate }},
                                        buyDirection: 'up',
                                        sellDirection: 'up',

                                        animateRates() {
                                            setInterval(() => {
                                                // Random chance to change direction (20% chance)
                                                if (Math.random() < 0.2) {
                                                    this.buyDirection = Math.random() < 0.05 ? 'up' : 'down';
                                                    this.sellDirection = Math.random() < 0.05 ? 'up' : 'down';
                                                }

                                                // Update buy rate
                                                const buyChange = this.buyDirection === 'up' ? 0.01 : -0.01;
                                                this.buyRate = Math.max(0.01, this.buyRate + buyChange);

                                                // Update sell rate
                                                const sellChange = this.sellDirection === 'up' ? 0.01 : -0.01;
                                                this.sellRate = Math.max(0.01, this.sellRate + sellChange);

                                                // Keep rates within reasonable bounds (±0.04 of original)
                                                const buyMin = this.originalBuyRate - 0.04;
                                                const buyMax = this.originalBuyRate + 0.04;
                                                const sellMin = this.originalSellRate - 0.04;
                                                const sellMax = this.originalSellRate + 0.04;

                                                if (this.buyRate <= buyMin) this.buyDirection = 'up';
                                                if (this.buyRate >= buyMax) this.buyDirection = 'down';
                                                if (this.sellRate <= sellMin) this.sellDirection = 'up';
                                                if (this.sellRate >= sellMax) this.sellDirection = 'down';

                                                this.buyRate = Math.min(buyMax, Math.max(buyMin, this.buyRate));
                                                this.sellRate = Math.min(sellMax, Math.max(sellMin, this.sellRate));
                                            }, 1000);
                                        }
                                    }"
                                    x-init="animateRates()">

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
                                                class="text-sm font-medium transition-colors duration-300 text-green-500"
                                                x-text="`{{ $pair->secondCurrency->symbol }}${buyRate.toFixed(2)}`"></span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="text-xs text-gray-400 w-10 text-left">Sell:</span>
                                            <span
                                                class="text-sm font-medium transition-colors duration-300 text-red-500"
                                                x-text="`{{ $pair->secondCurrency->symbol }}${sellRate.toFixed(2)}`"></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="space-y-4">
                        <div class="hidden lg:block">
                            <div
                                class="bg-gray-900 text-white p-6 rounded-lg brand-border flex flex-col items-center justify-center text-center">
                                <p class="text-sm font-semibold text-[#E1B362]">Mobile App</p>
                                <h4 class="text-lg font-bold mt-2">Coming Soon!!</h4>
                                <img src="{{asset('storage/images/new-app.png')}}"
                                     class="w-32 h-auto mx-auto my-4">
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            @if($activeTab !== 'exchange')
                @if(!empty($walletTransactions))
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
                                    <a href="#" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">This
                                        Week</a>
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
                @endif
            @endif
            <div class="mb-10 hidden">
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
            <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center"
                 x-data="{ currentStep: 1 }">
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"
                     x-show="true"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                </div>

                {{-- Modal Panel --}}
                <div class="relative w-full sm:max-w-2xl transform transition-all
                            sm:rounded-2xl bg-gray-900 shadow-2xl border border-gray-800
                            h-[85vh] sm:h-auto overflow-hidden"
                     x-show="true"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                     x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
                     x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0">

                    {{-- Header --}}
                    <div class="sticky top-0 z-10 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800">
                        <div class="flex items-center gap-4 p-4 sm:p-5">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg">
                                <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-white truncate"
                                    x-text="currentStep === 1 ? 'Welcome to Your Wallet' : (currentStep === 2 ? 'Terms & Conditions' : 'Complete Your Profile')">
                                </h3>
                                <p class="text-sm text-gray-400"
                                   x-text="currentStep === 1 ? 'Discover what you can do' : (currentStep === 2 ? 'Please review our terms' : 'Fill in your details to continue')">
                                </p>
                            </div>
                            <button wire:click="$set('showTermsModal', false)"
                                    class="rounded-full p-2 text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        {{-- Progress Steps --}}
                        <div class="flex px-4 sm:px-5 pb-4">
                            <div class="w-full bg-gray-800 rounded-full h-2">
                                <div
                                    class="bg-gradient-to-r from-yellow-500 to-amber-600 h-2 rounded-full transition-all duration-500"
                                    :style="{ width: (currentStep / 3) * 100 + '%' }">
                                </div>
                            </div>
                        </div>

                        {{-- Step Indicators --}}
                        <div class="flex justify-between px-4 sm:px-5 pb-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium transition-all"
                                    :class="currentStep >= 1 ? 'bg-yellow-500 text-white' : 'bg-gray-700 text-gray-400'">
                                    1
                                </div>
                                <span class="text-xs text-gray-400">Welcome</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium transition-all"
                                    :class="currentStep >= 2 ? 'bg-yellow-500 text-white' : 'bg-gray-700 text-gray-400'">
                                    2
                                </div>
                                <span class="text-xs text-gray-400">Terms</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium transition-all"
                                    :class="currentStep >= 3 ? 'bg-yellow-500 text-white' : 'bg-gray-700 text-gray-400'">
                                    3
                                </div>
                                <span class="text-xs text-gray-400">Profile</span>
                            </div>
                        </div>
                    </div>

                    {{-- Scrollable Content --}}
                    <div class="overflow-y-auto h-[calc(100%-16rem)]">
                        {{-- Step 1: Welcome --}}
                        <div x-show="currentStep === 1"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-4"
                             class="h-full flex flex-col p-4 overflow-hidden">

                            {{-- Compact Animated Welcome Section --}}
                            <div class="text-center mb-4">
                                {{-- Naira Wallet Animation --}}
                                <div class="relative w-20 h-20 mx-auto mb-4">
                                    <!-- Background circles with pulse animation -->
                                    <div
                                        class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/20 to-green-600/20 animate-ping"></div>
                                    <div
                                        class="absolute inset-1 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-pulse"></div>

                                    <!-- Main wallet container -->
                                    <div
                                        class="absolute inset-2 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-xl transform transition-transform duration-1000 hover:scale-110">
                                        <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
                                    </div>

                                    <!-- Floating Naira symbol -->
                                    <div
                                        class="absolute -top-1 -right-1 w-8 h-8 bg-green-700 rounded-full flex items-center justify-center animate-bounce border-2 border-white">
                                        <span class="text-white text-sm font-bold">₦</span>
                                    </div>

                                    <!-- Nigerian flag colors accent -->
                                    <div
                                        class="absolute -bottom-1 -left-1 w-6 h-6 bg-green-600 rounded-full flex items-center justify-center animate-bounce"
                                        style="animation-delay: 0.5s;">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>

                                <h2 class="text-xl font-bold text-white mb-2">Your Naira Wallet Awaits</h2>
                                <p class="text-sm text-gray-400">Get your virtual Nigerian bank account instantly</p>
                            </div>

                            {{-- Feature Cards Grid --}}
                            <div class="flex-1 grid grid-cols-2 gap-2 h-full max-h-64">
                                <div
                                    class="bg-gradient-to-r from-green-500/10 to-green-600/10 border border-green-500/20 rounded-lg p-3">
                                    <div class="flex flex-col items-center text-center">
                                        <div
                                            class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center mb-2">
                                            <i data-lucide="credit-card" class="w-4 h-4 text-green-400"></i>
                                        </div>
                                        <h4 class="font-semibold text-white text-sm">Virtual Account</h4>
                                        <p class="text-xs text-gray-400">Get account number</p>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-r from-blue-500/10 to-blue-600/10 border border-blue-500/20 rounded-lg p-3">
                                    <div class="flex flex-col items-center text-center">
                                        <div
                                            class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center mb-2">
                                            <i data-lucide="building-2" class="w-4 h-4 text-blue-400"></i>
                                        </div>
                                        <h4 class="font-semibold text-white text-sm">Bank Transfers</h4>
                                        <p class="text-xs text-gray-400">To any Nigerian bank</p>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-r from-purple-500/10 to-purple-600/10 border border-purple-500/20 rounded-lg p-3">
                                    <div class="flex flex-col items-center text-center">
                                        <div
                                            class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center mb-2">
                                            <i data-lucide="smartphone" class="w-4 h-4 text-purple-400"></i>
                                        </div>
                                        <h4 class="font-semibold text-white text-sm">Bills Payment</h4>
                                        <p class="text-xs text-gray-400">Airtime, data & utilities</p>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-r from-yellow-500/10 to-amber-600/10 border border-yellow-500/20 rounded-lg p-3">
                                    <div class="flex flex-col items-center text-center">
                                        <div
                                            class="w-8 h-8 bg-yellow-500/20 rounded-full flex items-center justify-center mb-2">
                                            <i data-lucide="shield-check" class="w-4 h-4 text-yellow-400"></i>
                                        </div>
                                        <h4 class="font-semibold text-white text-sm">Secure</h4>
                                        <p class="text-xs text-gray-400">Bank-level protection</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Terms & Conditions --}}
                        <div x-show="currentStep === 2"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-4"
                             class="p-4 sm:p-5 space-y-4">

                            <div class="bg-gray-800/50 rounded-xl p-4">
                                <details class="group" open>
                                    <summary class="flex items-center justify-between cursor-pointer list-none">
                                        <div class="flex items-center gap-3">
                                            <i data-lucide="scroll-text" class="w-5 h-5 text-yellow-500"></i>
                                            <span class="font-medium text-white">Terms & Conditions</span>
                                        </div>
                                        <i data-lucide="chevron-down"
                                           class="w-5 h-5 text-gray-400 transition-transform group-open:rotate-180"></i>
                                    </summary>
                                    <div class="mt-4 text-sm text-gray-300 space-y-4">
                                        <p>Before proceeding, please review our service terms:</p>

                                        <div class="space-y-3">
                                            <div class="flex gap-3">
                                                <i data-lucide="shield"
                                                   class="w-5 h-5 text-yellow-500 flex-shrink-0"></i>
                                                <div>
                                                    <h6 class="font-medium text-white">Security & Privacy</h6>
                                                    <p class="text-sm text-gray-400">Your personal information and BVN
                                                        details are encrypted and protected with bank-level security</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <i data-lucide="wallet"
                                                   class="w-5 h-5 text-yellow-500 flex-shrink-0"></i>
                                                <div>
                                                    <h6 class="font-medium text-white">Virtual Account Services</h6>
                                                    <p class="text-sm text-gray-400">Access to Nigerian Naira virtual
                                                        account for deposits, transfers, and bill payments</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <i data-lucide="check-circle"
                                                   class="w-5 h-5 text-yellow-500 flex-shrink-0"></i>
                                                <div>
                                                    <h6 class="font-medium text-white">Transaction Finality</h6>
                                                    <p class="text-sm text-gray-400">All transactions are final - verify
                                                        recipient details and amounts before confirming</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </details>
                            </div>
                        </div>

                        {{-- Step 3: Profile Form OR Progress Loading --}}
                        <div x-show="currentStep === 3"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-4"
                             class="p-4 sm:p-5 space-y-4">

                            {{-- Profile Form (shown when not loading) --}}
                            <div wire:loading.remove wire:target="acceptTermsAndCreateWallet">
                                <div class="bg-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-6">
                                        <i data-lucide="user-circle" class="w-5 h-5 text-yellow-500"></i>
                                        <h6 class="font-medium text-white">Complete Your Profile</h6>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1.5">Full Name</label>
                                            <input type="text" wire:model.live="fullName"
                                                   placeholder="Enter your full name"
                                                   class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 transition-colors">
                                            @error('fullName')
                                            <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
                                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                                {{ $message }}
                                            </p>
                                            @enderror
                                        </div>

                                        <div
                                            class="flex items-start gap-2 text-sm text-blue-400 bg-blue-500/10 border border-blue-500/20 p-3 rounded-lg">
                                            <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                            <p>Please enter your full name exactly as it appears on your submitted KYC
                                                documents for verification purposes.</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1.5">Date of Birth</label>
                                            <input type="date" wire:model.live="dateOfBirth"
                                                   class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1.5">Bank Verification Number
                                                (BVN)</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="bvn" inputmode="numeric"
                                                       maxlength="11"
                                                       placeholder="Enter your 11-digit BVN"
                                                       class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 transition-colors"
                                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11)">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <i data-lucide="check" class="w-5 h-5 text-green-500"
                                                       x-show="$wire.bvn?.length === 11"></i>
                                                </div>
                                            </div>
                                            @error('bvn')
                                            <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
                                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                                {{ $message }}
                                            </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <div
                                                class="flex items-start gap-2 text-sm text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 p-3 rounded-lg">
                                                <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                                <p>BVN is required by CBN (Central Bank of Nigeria) policy for all
                                                    virtual account number issuance and compliance with banking
                                                    regulations.</p>
                                            </div>

                                            <div
                                                class="flex items-start gap-2 text-sm text-gray-400 bg-gray-900/30 p-3 rounded-lg">
                                                <i data-lucide="shield" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                                <p>Your information is encrypted and stored securely in compliance with
                                                    banking regulations.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Progress Loading Content (shown when loading) --}}
                            <div wire:loading wire:target="acceptTermsAndCreateWallet"
                                 class="flex items-center justify-center min-h-[400px] py-8">
                                <div class="text-center w-full max-w-md mx-auto px-4">
                                    {{-- Animated Wallet Icon --}}
                                    <div class="relative w-16 h-16 mx-auto mb-6">
                                        <div
                                            class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-pulse"></div>
                                        <div
                                            class="absolute inset-2 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-xl animate-bounce">
                                            <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
                                        </div>
                                    </div>

                                    {{-- Loading Text --}}
                                    <h3 class="text-xl font-bold text-white mb-2">Creating Your Wallet</h3>
                                    <p class="text-gray-400 mb-6">Please wait while we set up your virtual
                                        account...</p>

                                    {{-- Progress Bar --}}
                                    <div class="relative w-full max-w-sm mx-auto">
                                        <div class="w-full bg-gray-800 rounded-full h-2 mb-4">
                                            <div
                                                class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full animate-pulse"
                                                style="width: 100%; animation: progress 3s ease-in-out infinite;">
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500">This may take a few moments...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            @keyframes progress {
                                0% {
                                    width: 0%;
                                }
                                50% {
                                    width: 75%;
                                }
                                100% {
                                    width: 100%;
                                }
                            }
                        </style>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="sticky bottom-0 bg-gray-900/95 backdrop-blur-sm border-t border-gray-800 p-4 sm:p-5">
                        <div class="flex justify-between items-center gap-2">
                            <div class="flex gap-2">
                                <button x-show="currentStep > 1" @click="currentStep--"
                                        class="px-3 py-2.5 sm:px-4 rounded-lg text-gray-300 hover:text-white hover:bg-gray-800 border border-gray-700 hover:border-gray-600 transition-colors text-sm">
                                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1 sm:mr-2"></i>
                                    <span class="hidden sm:inline">Back</span>
                                </button>
                                <button wire:click="$set('showTermsModal', false)"
                                        class="px-3 py-2.5 sm:px-4 rounded-lg text-gray-300 hover:text-white hover:bg-gray-800 border border-gray-700 hover:border-gray-600 transition-colors text-sm">
                                    <span class="sm:hidden">✕</span>
                                    <span class="hidden sm:inline">Cancel</span>
                                </button>
                            </div>

                            <div class="flex gap-2">
                                <button x-show="currentStep < 3" @click="currentStep++"
                                        class="px-4 py-2.5 sm:px-6 rounded-lg font-medium bg-gradient-to-r from-yellow-500 to-amber-600 text-white hover:from-yellow-600 hover:to-amber-700 transition-all text-sm">
                                    <span class="sm:hidden">Next</span>
                                    <span class="hidden sm:inline">Next</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1 sm:ml-2"></i>
                                </button>

                                <button x-show="currentStep === 3" wire:click="acceptTermsAndCreateWallet"
                                        wire:loading.attr="disabled"
                                        class="px-4 py-2.5 sm:px-6 rounded-lg font-medium bg-gradient-to-r from-yellow-500 to-amber-600 text-white hover:from-yellow-600 hover:to-amber-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all text-sm">
                                    <span wire:loading.remove wire:target="acceptTermsAndCreateWallet">
                                        <span class="sm:hidden">Create</span>
                                        <span class="hidden sm:inline">Create Wallet</span>
                                    </span>
                                    <span wire:loading wire:target="acceptTermsAndCreateWallet"
                                          class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" fill="none"/>
                                            <path class="opacity-75" fill="currentColor"
                                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        <span class="sm:hidden">Creating...</span>
                                        <span class="hidden sm:inline">Creating Wallet...</span>
                                    </span>
                                </button>


                            </div>
                        </div>
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


</div>
