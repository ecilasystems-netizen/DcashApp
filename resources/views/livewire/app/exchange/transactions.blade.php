<div>
    @push('styles')
        <style>
            .status-pill {
                padding: 6px 12px;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                backdrop-filter: blur(8px);
                border: 1px solid transparent;
            }

            .status-completed {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.08));
                color: #10B981;
                border-color: rgba(16, 185, 129, 0.2);
                box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
            }

            .status-pending_payment, .status-pending_confirmation {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.08));
                color: #F59E0B;
                border-color: rgba(245, 158, 11, 0.2);
                box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1);
            }

            .status-processing {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.08));
                color: #3B82F6;
                border-color: rgba(59, 130, 246, 0.2);
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
            }

            .status-failed {
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
                color: #EF4444;
                border-color: rgba(239, 68, 68, 0.2);
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
            }

            .status-rejected {
                background: linear-gradient(135deg, rgba(156, 163, 175, 0.15), rgba(156, 163, 175, 0.08));
                color: #9CA3AF;
                border-color: rgba(156, 163, 175, 0.2);
                box-shadow: 0 2px 8px rgba(156, 163, 175, 0.1);
            }

            .stat-card {
                background: linear-gradient(135deg, rgba(31, 41, 55, 0.8), rgba(17, 24, 39, 0.6));
                backdrop-filter: blur(12px);
                border: 1px solid rgba(75, 85, 99, 0.3);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                border-color: rgba(225, 179, 98, 0.4);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .transaction-card {
                background: linear-gradient(135deg, rgba(31, 41, 55, 0.9), rgba(17, 24, 39, 0.7));
                backdrop-filter: blur(12px);
                border: 1px solid rgba(75, 85, 99, 0.3);
                transition: all 0.3s ease;
            }

            .transaction-card:hover {
                transform: translateY(-1px);
                border-color: rgba(225, 179, 98, 0.3);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            }

            .gradient-border {
                position: relative;
                overflow: hidden;
            }

            .gradient-border::before {
                content: '';
                position: absolute;
                inset: 0;
                padding: 1px;
                background: linear-gradient(135deg, rgba(225, 179, 98, 0.4), rgba(225, 179, 98, 0.1));
                border-radius: inherit;
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask-composite: xor;
            }
        </style>
    @endpush

    @php
        $statusConfig = [
            'pending_payment' => [
                'icon' => 'credit-card',
                'label' => 'Pending Payment'
            ],
            'pending_confirmation' => [
                'icon' => 'clock',
                'label' => 'Pending Confirmation'
            ],
            'processing' => [
                'icon' => 'loader',
                'label' => 'Processing'
            ],
            'completed' => [
                'icon' => 'check-circle',
                'label' => 'Completed'
            ],
            'failed' => [
                'icon' => 'x-circle',
                'label' => 'Failed'
            ],
            'rejected' => [
                'icon' => 'x-octagon',
                'label' => 'Rejected'
            ]
        ];
    @endphp

    <x-slot name="header">
        <header class="bg-gray-950 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center space-x-4">
                    <a type="button" class="p-1 rounded-full bg-gray-800" href="{{ route('dashboard') }}">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Transaction</p>
                        <h2 class="font-bold text-xl text-white">History</h2>
                    </div>
                </div>
                <!-- Desktop Header -->
                <div class="hidden lg:block">
                    <h1 class="text-2xl font-bold text-white">Transaction History</h1>
                    <p class="text-gray-400 text-sm mt-1">Review your recent account activity.</p>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="p-0 lg:p-8 space-y-6">
        @if(count($transactions) > 0)

            @if(session('exchange_active_tab', 'exchange') === 'exchange' && isset($stats))
                <!-- Transaction Statistics -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Mobile Stats Grid -->
                    <div class="grid grid-cols-3 gap-3 md:hidden">
                        <div class="stat-card rounded-xl p-4">
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div
                                    class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 text-blue-400 p-3 rounded-full">
                                    <i data-lucide="list" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-white">{{ $stats['total'] }}</p>
                                    <p class="text-xs text-gray-400">Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-4">
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div
                                    class="bg-gradient-to-br from-green-500/20 to-green-600/20 text-green-400 p-3 rounded-full">
                                    <i data-lucide="check-check" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-white">{{ $stats['successful'] }}</p>
                                    <p class="text-xs text-gray-400">Successful</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-4">
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div
                                    class="bg-gradient-to-br from-orange-500/20 to-orange-600/20 text-orange-400 p-3 rounded-full">
                                    <i data-lucide="loader" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-white">{{ $stats['pending'] }}</p>
                                    <p class="text-xs text-gray-400">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Stats Grid -->
                    <div class="hidden md:grid md:grid-cols-4 gap-6">
                        <div class="stat-card rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 text-blue-400 p-3 rounded-xl">
                                    <i data-lucide="list" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 font-medium">Total</p>
                                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 text-yellow-400 p-3 rounded-xl">
                                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 font-medium">Volume (30d)</p>
                                    <div class="space-y-1">
                                        @forelse($stats['volumes'] as $volume)
                                            <p class="text-lg font-bold text-white">
                                                {{ $volume['currency']->symbol }} {{ number_format($volume['amount'], 2) }}
                                            </p>
                                        @empty
                                            <p class="text-lg font-bold text-gray-500">-</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="bg-gradient-to-br from-green-500/20 to-green-600/20 text-green-400 p-3 rounded-xl">
                                    <i data-lucide="check-check" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 font-medium">Successful</p>
                                    <p class="text-2xl font-bold text-white">{{ $stats['successful'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="bg-gradient-to-br from-orange-500/20 to-orange-600/20 text-orange-400 p-3 rounded-xl">
                                    <i data-lucide="loader" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 font-medium">Pending</p>
                                    <p class="text-2xl font-bold text-white">{{ $stats['pending'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Transactions Container -->
            <div class=" rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-br from-gray-900/90 to-gray-950/90 backdrop-blur-md">
                    <!-- Filters and Search -->
                    <div class="p-6 border-b border-gray-700/50">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="relative w-full md:flex-1 max-w-md hidden md:block">
                                <i data-lucide="search"
                                   class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" placeholder="Search by asset or ID..."
                                       class="w-full bg-gray-800/80 border border-gray-700/50 rounded-xl pl-12 pr-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#E1B362]/50 focus:border-[#E1B362]/50 transition-all">
                            </div>
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                <select
                                    class="bg-gray-800/80 border border-gray-700/50 rounded-xl px-4 py-3 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]/50 focus:border-[#E1B362]/50 transition-all text-sm font-medium min-w-0 flex-shrink-0">
                                    <option>All Status</option>
                                    <option>Completed</option>
                                    <option>Pending</option>
                                    <option>Failed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Transaction List -->
                    <div class="lg:hidden p-4 space-y-4">
                        @if(session('exchange_active_tab', 'exchange') === 'wallet')
                            @foreach($transactions as $transaction)
                                <a href="{{ route('wallet.transactions.receipt', ['ref' => $transaction->reference]) }}"
                                   class="block transaction-card rounded-xl p-4 hover:scale-[1.02] transition-all duration-300">
                                    <div class="flex justify-between items-center mb-3">
                                                        <span class="status-pill status-{{ $transaction->status }}">
                                                            <i data-lucide="{{ $statusConfig[$transaction->status]['icon'] ?? 'circle' }}"
                                                               class="w-3 h-3"></i>
                                                            {{ ucfirst($transaction->status) }}
                                                        </span>
                                        <span
                                            class="text-xs text-gray-400 font-medium">{{ $transaction->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                                            <span
                                                                class="text-sm font-semibold text-white">{{ ucfirst($transaction->type) }}</span>
                                            <span
                                                class="text-xs px-3 py-1 rounded-full font-medium {{ $transaction->direction === 'credit' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                                                {{ ucfirst($transaction->direction) }}
                                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-base font-bold text-white">
                                                {{ $transaction->wallet->currency->symbol }} {{ number_format($transaction->amount, 2) }}
                                            </div>
                                            @if($transaction->charge > 0)
                                                <div class="text-xs text-gray-400">
                                                    Fee: {{ $transaction->wallet->currency->symbol }} {{ number_format($transaction->charge, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($transaction->description)
                                        <div class="mt-3 pt-3 border-t border-gray-700/50 text-sm text-gray-400">
                                            {{ $transaction->description }}
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        @else
                            @foreach($transactions as $transaction)
                                <a href="{{ route('exchange.receipt', ['ref' => $transaction->reference]) }}"
                                   class="block transaction-card rounded-xl p-4 hover:scale-[1.02] transition-all duration-300">
                                    <div class="flex justify-between items-center mb-3">
                                                        <span class="status-pill status-{{ $transaction->status }}">
                                                            <i data-lucide="{{ $statusConfig[$transaction->status]['icon'] }}"
                                                               class="w-3 h-3"></i>
                                                            {{ $statusConfig[$transaction->status]['label'] }}
                                                        </span>
                                        <span
                                            class="text-xs text-gray-400 font-medium">{{ $transaction->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                                            <span
                                                                class="text-sm font-semibold text-white">{{ $transaction->fromCurrency->code }}</span>
                                            <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400"></i>
                                            <span
                                                class="text-sm font-medium text-gray-300">{{ $transaction->toCurrency->code }}</span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-base font-bold text-white">
                                                {{ $transaction->fromCurrency->symbol }} {{ number_format($transaction->amount_from, 2) }}
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ $transaction->toCurrency->symbol }} {{ number_format($transaction->amount_to, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-800/50 border-b border-gray-700/50">
                            <tr class="text-xs text-gray-400 uppercase tracking-wider">
                                @if(session('exchange_active_tab', 'exchange') === 'wallet')
                                    <th class="py-4 px-6 text-left font-semibold">Reference</th>
                                    <th class="py-4 px-6 text-left font-semibold">Type</th>
                                    <th class="py-4 px-6 text-left font-semibold">Direction</th>
                                    <th class="py-4 px-6 text-right font-semibold">Amount</th>
                                    <th class="py-4 px-6 text-center font-semibold">Status</th>
                                    <th class="py-4 px-6 text-right font-semibold">Date</th>
                                @else
                                    <th class="py-4 px-6 text-left font-semibold">Transaction ID</th>
                                    <th class="py-4 px-6 text-left font-semibold">Exchange</th>
                                    <th class="py-4 px-6 text-right font-semibold">Amount</th>
                                    <th class="py-4 px-6 text-center font-semibold">Status</th>
                                    <th class="py-4 px-6 text-right font-semibold">Date</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="text-sm">
                            @foreach($transactions as $transaction)
                                @if(session('exchange_active_tab', 'exchange') === 'wallet')
                                    <tr class="border-b border-gray-700/30 hover:bg-gray-800/30 cursor-pointer transition-all duration-200"
                                        onclick="window.location.href='{{ route('wallet.transactions.receipt', ['ref' => $transaction->reference]) }}'">
                                        <td class="py-4 px-6">
                                                            <span
                                                                class="font-mono text-gray-300 text-sm">{{ $transaction->reference }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                                            <span
                                                                class="text-white font-medium">{{ ucfirst($transaction->type) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                                            <span
                                                                class="text-xs px-3 py-1 rounded-full font-semibold {{ $transaction->direction === 'credit' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                                                {{ ucfirst($transaction->direction) }}
                                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div
                                                class="font-bold text-white">{{ $transaction->wallet->currency->symbol }} {{ number_format($transaction->amount, 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                                            <span
                                                                class="status-pill status-{{ $transaction->status }}">
                                                                {{ ucfirst($transaction->status) }}
                                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div
                                                class="text-gray-300">{{ $transaction->created_at->format('M d, Y') }}</div>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="border-b border-gray-700/30 hover:bg-gray-800/30 cursor-pointer transition-all duration-200"
                                        onclick="window.location.href='{{ route('exchange.receipt', ['ref' => $transaction->reference]) }}'">
                                        <td class="py-4 px-6">
                                                            <span
                                                                class="font-mono text-gray-300 text-sm">{{ $transaction->reference }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                                <span
                                                                    class="font-bold text-white">{{ $transaction->fromCurrency->code }}</span>
                                                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-500"></i>
                                                <span
                                                    class="font-medium text-gray-300">{{ $transaction->toCurrency->code }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div
                                                class="font-bold text-white">{{ $transaction->fromCurrency->symbol }} {{ number_format($transaction->amount_from, 2) }}</div>
                                            <div
                                                class="text-sm text-gray-400">{{ $transaction->toCurrency->symbol }} {{ number_format($transaction->amount_to, 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                                            <span
                                                                class="status-pill status-{{ $transaction->status }}">
                                                                <i data-lucide="{{ $statusConfig[$transaction->status]['icon'] }}"
                                                                   class="w-3 h-3"></i>
                                                                {{ $statusConfig[$transaction->status]['label'] }}
                                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div
                                                class="text-gray-300">{{ $transaction->created_at->format('M d, Y') }}</div>
                                            <div
                                                class="text-sm text-gray-500">{{ $transaction->created_at->format('g:i A') }}</div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div
                    class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-md rounded-full p-8 mb-8 border border-gray-700/50">
                    <i data-lucide="history" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-3">No Transactions Yet</h3>
                <p class="text-gray-400 mb-8 text-lg max-w-md">Start your journey by making your first exchange and
                    building your transaction history.</p>
                <a href="{{ route('dashboard') }}"
                   class="bg-gradient-to-r from-[#E1B362] to-[#D4A853] text-gray-900 px-8 py-4 rounded-xl font-bold hover:from-[#D4A853] hover:to-[#C69946] transition-all duration-300 inline-flex items-center gap-3 shadow-lg hover:shadow-xl hover:scale-105">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Make Your First Exchange
                </a>
            </div>
        @endif
    </div>
</div>
