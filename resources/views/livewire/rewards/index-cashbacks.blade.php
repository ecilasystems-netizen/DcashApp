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
                        <h2 class="font-bold text-lg text-white">Cashback</h2>
                        <p class="text-xs text-gray-400">Earn rewards</p>
                    </div>
                </div>

                {{--                <!-- Total Cashback Balance -->--}}
                {{--                <div class="flex items-center gap-3">--}}
                {{--                    <div class="text-right hidden sm:block">--}}
                {{--                        <p class="text-xs text-gray-400">Total Earned</p>--}}
                {{--                        @forelse($totalCashbackByCurrency as $code => $data)--}}
                {{--                            <p class="text-sm font-semibold text-white">{{ $data['symbol'] }}{{ number_format($data['amount'], 2) }}</p>--}}
                {{--                        @empty--}}
                {{--                            <p class="text-sm font-semibold text-white">{{ $data['symbol'] ?? '$' }}0.00</p>--}}
                {{--                        @endforelse--}}
                {{--                    </div>--}}
                {{--                    <div--}}
                {{--                        class="bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-bold px-4 py-2 rounded-xl shadow-lg">--}}
                {{--                        @forelse($totalCashbackByCurrency as $code => $data)--}}
                {{--                            <span class="sm:hidden">{{ $data['symbol'] }}{{ number_format($data['amount'], 0) }}</span>--}}
                {{--                            <span--}}
                {{--                                class="hidden sm:inline">{{ $data['symbol'] }}{{ number_format($data['amount'], 2) }}</span>--}}
                {{--                        @empty--}}
                {{--                            <span>$0.00</span>--}}
                {{--                        @endforelse--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </header>
    </x-slot>

    <div class="min-h-screen bg-gray-950">
        <div class="lg:py-8 space-y-8">

            <!-- Hero Section -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-yellow-600/20 via-gray-900 to-gray-900 rounded-3xl p-8 lg:p-12 border border-yellow-500/20">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-600/5 to-transparent"></div>
                <div class="relative z-10 grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-xl">
                                <i data-lucide="gift" class="w-8 h-8 text-yellow-400"></i>
                            </div>
                            <span
                                class="px-4 py-2 bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-semibold border border-yellow-500/30">
                                        Cashback Rewards
                                    </span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">
                            Get Money Back on<br/>
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 bg-clip-text text-transparent">
                                        Every Exchange
                                    </span>
                        </h2>
                        <p class="text-gray-400 text-lg mb-6">
                            Earn instant cashback rewards on your currency exchanges. The more you trade, the more you
                            earn back.
                        </p>
                        <div class="grid grid-cols-3 gap-2">
                            @forelse($totalCashbackByCurrency as $code => $data)
                                <div class="bg-gray-800/60 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50">
                                    <p class="text-xs text-gray-400 mb-0.5 truncate">Total Earned</p>
                                    <p class="text-sm font-bold text-white truncate"
                                       title="{{ $data['symbol'] }}{{ number_format($data['amount'], 2) }}">
                                        {{ $data['symbol'] }}{{ number_format($data['amount'], 2) }}
                                    </p>
                                </div>
                            @empty
                                <div class="bg-gray-800/60 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50">
                                    <p class="text-xs text-gray-400 mb-0.5 truncate">Total Earned</p>
                                    <p class="text-sm font-bold text-white truncate">$0.00</p>
                                </div>
                            @endforelse
                            <div class="bg-gray-800/60 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50">
                                <p class="text-xs text-gray-400 mb-0.5 truncate">Transactions</p>
                                <p class="text-sm font-bold text-white truncate">{{ $transactions->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex justify-center">
                        <div class="relative">
                            <div
                                class="w-64 h-64 bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 rounded-full blur-3xl"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div
                                    class="p-8 bg-gray-900/80 backdrop-blur-sm rounded-full border border-yellow-500/30">
                                    <i data-lucide="piggy-bank" class="w-16 h-16 text-yellow-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashback Transactions -->
            @if($transactions->isNotEmpty())
                <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl border border-gray-800/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-800/50">
                        <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-3">
                            <i data-lucide="history" class="w-6 h-6 text-yellow-400"></i>
                            Your Cashback History
                        </h3>
                        <p class="text-gray-400">Track all your cashback earnings from exchanges</p>
                    </div>

                    <!-- Mobile Transaction List -->
                    <div class="lg:hidden divide-y divide-gray-800/50">
                        @foreach($transactions as $transaction)
                            <a href="{{ route('exchange.receipt', ['ref' => $transaction->reference, 'backUrl' => route('rewards.cashbacks')]) }}"
                               class="block p-4 hover:bg-gray-800/30 transition-all duration-200 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="p-2 bg-yellow-500/20 rounded-lg group-hover:bg-yellow-500/30 transition-colors">
                                            <i data-lucide="receipt" class="w-5 h-5 text-yellow-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white text-sm">
                                                {{ $transaction->fromCurrency->code }}
                                                → {{ $transaction->toCurrency->code }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $transaction->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-yellow-400 text-sm">
                                            +{{ $transaction->fromCurrency->symbol }}{{ number_format($transaction->cashback, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            On {{ $transaction->fromCurrency->symbol }}{{ number_format($transaction->amount_from, 0) }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800/30">
                            <tr class="text-xs text-gray-400 uppercase tracking-wider">
                                <th class="py-4 px-6 text-left font-semibold">Exchange</th>
                                <th class="py-4 px-6 text-right font-semibold">Transaction Amount</th>
                                <th class="py-4 px-6 text-right font-semibold">Cashback Earned</th>
                                <th class="py-4 px-6 text-center font-semibold">Date</th>
                                <th class="py-4 px-6 text-center font-semibold">Action</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/30">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-800/20 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="p-2 bg-yellow-500/20 rounded-lg group-hover:bg-yellow-500/30 transition-colors">
                                                <i data-lucide="repeat" class="w-4 h-4 text-yellow-400"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white">
                                                    {{ $transaction->fromCurrency->code }}
                                                    → {{ $transaction->toCurrency->code }}
                                                </p>
                                                <p class="text-xs text-gray-400">Exchange
                                                    #{{ Str::limit($transaction->reference, 8, '...') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <p class="font-semibold text-white">
                                            {{ $transaction->fromCurrency->symbol }}{{ number_format($transaction->amount_from, 2) }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <p class="font-bold text-yellow-400 text-lg">
                                            +{{ $transaction->fromCurrency->symbol }}{{ number_format($transaction->cashback, 2) }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <p class="text-gray-300">{{ $transaction->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('exchange.receipt', ['ref' => $transaction->reference, 'backUrl' => route('rewards.cashbacks')]) }}"
                                           class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-700/50 hover:bg-gray-600/50 text-white rounded-lg transition-colors text-sm">
                                            <i data-lucide="receipt" class="w-4 h-4"></i>
                                            View Receipt
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl p-12 border border-gray-800/50 text-center">
                    <div class="w-20 h-20 bg-gray-800/60 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="piggy-bank" class="w-10 h-10 text-gray-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No Cashback Yet</h3>
                    <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">
                        Start making exchanges to earn cashback rewards. You'll see all your earnings here.
                    </p>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500 hover:to-yellow-400 text-black font-bold px-6 py-3 rounded-xl transition-all duration-300 shadow-lg hover:shadow-yellow-500/25">
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        Start Exchanging
                    </a>
                </div>
            @endif

            <!-- How Cashback Works -->
            <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl p-2 border border-gray-800/50">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-white mb-3">How Cashback Works</h3>
                    <p class="text-gray-400">Earn money back on every exchange automatically</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Step 1 -->
                    <div class="relative group">
                        <div
                            class="bg-gray-800/40 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 group-hover:border-yellow-500/30 transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-xl flex items-center justify-center mb-4">
                                <i data-lucide="repeat" class="w-6 h-6 text-yellow-400"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white mb-2">Make Exchange</h4>
                            <p class="text-gray-400 text-sm">Complete any currency exchange transaction on our
                                platform</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative group">
                        <div
                            class="bg-gray-800/40 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 group-hover:border-yellow-500/30 transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-xl flex items-center justify-center mb-4">
                                <i data-lucide="zap" class="w-6 h-6 text-yellow-400"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white mb-2">Instant Reward</h4>
                            <p class="text-gray-400 text-sm">Get cashback credited automatically after transaction
                                completion</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative group">
                        <div
                            class="bg-gray-800/40 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 group-hover:border-yellow-500/30 transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-xl flex items-center justify-center mb-4">
                                <i data-lucide="wallet" class="w-6 h-6 text-yellow-400"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white mb-2">Track Earnings</h4>
                            <p class="text-gray-400 text-sm">Monitor all your cashback earnings right here in your
                                account</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
