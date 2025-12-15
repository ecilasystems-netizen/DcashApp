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

            .status-pending,
            .status-pending_payment,
            .status-pending_confirmation {
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

            .status-failed,
            .status-expired {
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
                color: #EF4444;
                border-color: rgba(239, 68, 68, 0.2);
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
            }

            .status-rejected,
            .status-cancelled {
                background: linear-gradient(135deg, rgba(156, 163, 175, 0.15), rgba(156, 163, 175, 0.08));
                color: #9CA3AF;
                border-color: rgba(156, 163, 175, 0.2);
                box-shadow: 0 2px 8px rgba(156, 163, 175, 0.1);
            }

            .transaction-type-badge {
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .type-wallet {
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(139, 92, 246, 0.08));
                color: #8B5CF6;
                border: 1px solid rgba(139, 92, 246, 0.2);
            }

            .type-bonus {
                background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(236, 72, 153, 0.08));
                color: #EC4899;
                border: 1px solid rgba(236, 72, 153, 0.2);
            }

            .type-exchange {
                background: linear-gradient(135deg, rgba(14, 165, 233, 0.15), rgba(14, 165, 233, 0.08));
                color: #0EA5E9;
                border: 1px solid rgba(14, 165, 233, 0.2);
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
        </style>
    @endpush

    @php
        $statusConfig = [
            'pending_payment' => ['icon' => 'credit-card', 'label' => 'Pending Payment'],
            'pending_confirmation' => ['icon' => 'clock', 'label' => 'Pending Confirmation'],
            'pending' => ['icon' => 'clock', 'label' => 'Pending'],
            'processing' => ['icon' => 'loader', 'label' => 'Processing'],
            'completed' => ['icon' => 'check-circle', 'label' => 'Completed'],
            'failed' => ['icon' => 'x-circle', 'label' => 'Failed'],
            'rejected' => ['icon' => 'x-octagon', 'label' => 'Rejected'],
            'cancelled' => ['icon' => 'x', 'label' => 'Cancelled'],
            'expired' => ['icon' => 'clock', 'label' => 'Expired'],
        ];
    @endphp

    <x-slot name="header">
        <header class="bg-gray-950 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="lg:hidden flex items-center space-x-4">
                    <a type="button" class="p-1 rounded-full bg-gray-800" href="{{ route('dashboard') }}">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Transaction</p>
                        <h2 class="font-bold text-xl text-white">History</h2>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <h1 class="text-2xl font-bold text-white">Transaction History</h1>
                    <p class="text-gray-400 text-sm mt-1">Review your recent account activity.</p>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="p-0 lg:p-8 space-y-6">
        @if (count($transactions) > 0)

            @if (session('exchange_active_tab', 'exchange') === 'exchange' && isset($stats))
                <!-- Exchange Statistics -->
                <div class="grid grid-cols-1 gap-6">
                    <div class="grid grid-cols-3 gap-3 md:hidden">
                        <div class="stat-card rounded-xl p-2 col-span-3">
                            <div class="flex items-center gap-1 mb-2">
                                <div
                                    class="bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 text-yellow-400 p-2 rounded-lg">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                                </div>
                                <p class="text-sm text-gray-400 font-medium">Volume (30d)</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                @forelse($stats['volumes'] as $volume)
                                    <div class="bg-gray-800/50 rounded-lg p-2 border border-gray-700/50">
                                        <p class="text-xs text-gray-400">{{ $volume['currency']->code }}</p>
                                        <p class="text-sm text-white">{{ $volume['currency']->symbol }}
                                            {{ number_format($volume['amount'], 2) }}</p>
                                    </div>
                                @empty
                                    <div class="bg-gray-800/50 rounded-lg p-2 border border-gray-700/50">
                                        <p class="text-base font-bold text-gray-500">-</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-1">
                            <div class="flex items-center gap-1">
                                <div
                                    class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 text-blue-400 p-2 rounded-lg">
                                    <i data-lucide="list" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-white">{{ $stats['total'] }}</p>
                                    <p class="text-xs text-gray-400">Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-1">
                            <div class="flex items-center gap-0">
                                <div
                                    class="bg-gradient-to-br from-green-500/20 to-green-600/20 text-green-400 p-2 rounded-lg">
                                    <i data-lucide="check-check" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-white">{{ $stats['successful'] }}</p>
                                    <p class="text-xs text-gray-400">Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card rounded-xl p-1">
                            <div class="flex items-center gap-1">
                                <div
                                    class="bg-gradient-to-br from-orange-500/20 to-orange-600/20 text-orange-400 p-2 rounded-lg">
                                    <i data-lucide="loader" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-white">{{ $stats['pending'] }}</p>
                                    <p class="text-xs text-gray-400">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            <p class="text-lg font-bold text-white">{{ $volume['currency']->symbol }}
                                                {{ number_format($volume['amount'], 2) }}</p>
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
            <div class="rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-br from-gray-900/90 to-gray-950/90 backdrop-blur-md">

                    <!-- Mobile Transaction List -->
                    <div class="lg:hidden p-4 space-y-4">
                        @foreach ($transactions as $transaction)
                            @if ($transaction['transaction_type'] === 'wallet')
                                <a href="{{ route('wallet.transactions.receipt', ['ref' => $transaction['reference']]) }}"
                                   class="block transaction-card rounded-xl p-4 hover:scale-[1.02] transition-all duration-300">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="transaction-type-badge type-wallet">
                                                <i data-lucide="wallet" class="w-3 h-3 inline"></i> Wallet
                                            </span>
                                            <span class="status-pill status-{{ $transaction['status'] }}">
                                                <i data-lucide="{{ $statusConfig[$transaction['status']]['icon'] ?? 'circle' }}"
                                                   class="w-3 h-3"></i>
                                                {{ $statusConfig[$transaction['status']]['label'] ?? ucfirst($transaction['status']) }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-sm font-semibold text-white">{{ ucfirst($transaction['type']) }}</span>
                                            <span
                                                class="text-xs px-3 py-1 rounded-full font-medium {{ $transaction['direction'] === 'credit' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                                {{ ucfirst($transaction['direction']) }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-base font-bold text-white">
                                                {{ $transaction['currency_symbol'] }}
                                                {{ number_format($transaction['amount'], 2) }}
                                            </div>
                                            @if ($transaction['charge'] > 0)
                                                <div class="text-xs text-gray-400">
                                                    Fee: {{ $transaction['currency_symbol'] }}
                                                    {{ number_format($transaction['charge'], 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($transaction['description'] ?? false)
                                        <div class="mt-3 pt-3 border-t border-gray-700/50 text-sm text-gray-400">
                                            {{ $transaction['description'] }}
                                        </div>
                                    @endif
                                </a>
                            @elseif($transaction['transaction_type'] === 'bonus')
                                <a href="{{ route('rewards.transactions.receipt', ['ref' => $transaction['id']]) }}"
                                   class="block transaction-card rounded-xl p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="transaction-type-badge type-bonus">
                                                <i data-lucide="gift" class="w-3 h-3 inline"></i> Bonus
                                            </span>
                                            <span class="status-pill status-{{ $transaction['status'] }}">
                                                <i data-lucide="{{ $statusConfig[$transaction['status']]['icon'] ?? 'circle' }}"
                                                   class="w-3 h-3"></i>
                                                {{ $statusConfig[$transaction['status']]['label'] ?? ucfirst($transaction['status']) }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-sm font-semibold text-white">{{ ucfirst($transaction['type']) }}</span>

                                        </div>
                                        <div class="text-right">
                                            <div class="text-base font-bold text-green-400">
                                                {{ number_format($transaction['bonus_amount'], 2) }} DCOINS
                                            </div>
                                        </div>
                                    </div>
                                    @if ($transaction['notes'] ?? false)
                                        <div class="mt-3 pt-3 border-t border-gray-700/50 text-sm text-gray-400">
                                            {{ $transaction['notes'] }}
                                        </div>
                                    @endif
                                </a>
                            @else
                                <a href="{{ route('exchange.receipt', ['ref' => $transaction['reference']]) }}"
                                   class="block transaction-card rounded-xl p-4 hover:scale-[1.02] transition-all duration-300">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="transaction-type-badge type-exchange">
                                                <i data-lucide="arrow-left-right" class="w-3 h-3 inline"></i> Exchange
                                            </span>
                                            <span class="status-pill status-{{ $transaction['status'] }}">
                                                <i data-lucide="{{ $statusConfig[$transaction['status']]['icon'] }}"
                                                   class="w-3 h-3"></i>
                                                {{ $statusConfig[$transaction['status']]['label'] }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-sm font-semibold text-white">{{ $transaction['from_currency_code'] }}</span>
                                            <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400"></i>
                                            <span
                                                class="text-sm font-medium text-gray-300">{{ $transaction['to_currency_code'] }}</span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-base font-bold text-white">
                                                {{ $transaction['from_currency_symbol'] }}
                                                {{ number_format($transaction['amount_from'], 2) }}
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ $transaction['to_currency_symbol'] }}
                                                {{ number_format($transaction['amount_to'], 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-800/50 border-b border-gray-700/50">
                            <tr class="text-xs text-gray-400 uppercase tracking-wider">
                                <th class="py-4 px-6 text-left font-semibold">Type</th>
                                <th class="py-4 px-6 text-left font-semibold">Reference/Details</th>
                                <th class="py-4 px-6 text-right font-semibold">Amount</th>
                                <th class="py-4 px-6 text-center font-semibold">Status</th>
                                <th class="py-4 px-6 text-right font-semibold">Date</th>
                            </tr>
                            </thead>
                            <tbody class="text-sm">
                            @foreach ($transactions as $transaction)
                                @if ($transaction['transaction_type'] === 'wallet')
                                    <tr class="border-b border-gray-700/30 hover:bg-gray-800/30 cursor-pointer transition-all duration-200"
                                        onclick="window.location.href='{{ route('wallet.transactions.receipt', ['ref' => $transaction['reference']]) }}'">
                                        <td class="py-4 px-6">
                                                <span class="transaction-type-badge type-wallet">
                                                    <i data-lucide="wallet" class="w-3 h-3 inline"></i> Wallet
                                                </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-mono text-gray-300 text-sm">
                                                {{ $transaction['reference'] }}</div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ ucfirst($transaction['type']) }}
                                                <span
                                                    class="ml-2 px-2 py-0.5 rounded-full {{ $transaction['direction'] === 'credit' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                        {{ ucfirst($transaction['direction']) }}
                                                    </span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="font-bold text-white">
                                                {{ $transaction['currency_symbol'] }}
                                                {{ number_format($transaction['amount'], 2) }}</div>
                                            @if ($transaction['charge'] > 0)
                                                <div class="text-xs text-gray-400">
                                                    Fee: {{ $transaction['currency_symbol'] }}
                                                    {{ number_format($transaction['charge'], 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                                <span class="status-pill status-{{ $transaction['status'] }}">
                                                    {{ $statusConfig[$transaction['status']]['label'] ?? ucfirst($transaction['status']) }}
                                                </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="text-gray-300">
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('g:i A') }}
                                            </div>
                                        </td>
                                    </tr>
                                @elseif($transaction['transaction_type'] === 'bonus')
                                    <tr class="border-b border-gray-700/30 hover:bg-gray-800/30 cursor-pointer transition-all duration-200"
                                        onclick="window.location.href='{{ route('rewards.transactions.receipt', ['ref' => $transaction['id']]) }}'">
                                        <td class="py-4 px-6">
                                                <span class="transaction-type-badge type-bonus">
                                                    <i data-lucide="gift" class="w-3 h-3 inline"></i> Bonus
                                                </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-white font-medium">
                                                {{ ucfirst($transaction['type']) }}
                                                Bonus
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="font-bold text-green-400">
                                                {{ number_format($transaction['bonus_amount'], 2) }} DCOINS
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                                <span class="status-pill status-{{ $transaction['status'] }}">
                                                    {{ $statusConfig[$transaction['status']]['label'] ?? ucfirst($transaction['status']) }}
                                                </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="text-gray-300">
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('g:i A') }}
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="border-b border-gray-700/30 hover:bg-gray-800/30 cursor-pointer transition-all duration-200"
                                        onclick="window.location.href='{{ route('exchange.receipt', ['ref' => $transaction['reference']]) }}'">
                                        <td class="py-4 px-6">
                                                <span class="transaction-type-badge type-exchange">
                                                    <i data-lucide="arrow-left-right" class="w-3 h-3 inline"></i>
                                                    Exchange
                                                </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-mono text-gray-300 text-sm">
                                                {{ $transaction['reference'] }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                    <span
                                                        class="font-bold text-white text-xs">{{ $transaction['from_currency_code'] }}</span>
                                                <i data-lucide="arrow-right" class="w-3 h-3 text-gray-500"></i>
                                                <span
                                                    class="font-medium text-gray-300 text-xs">{{ $transaction['to_currency_code'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="font-bold text-white">
                                                {{ $transaction['from_currency_symbol'] }}
                                                {{ number_format($transaction['amount_from'], 2) }}</div>
                                            <div class="text-sm text-gray-400">
                                                {{ $transaction['to_currency_symbol'] }}
                                                {{ number_format($transaction['amount_to'], 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                                <span class="status-pill status-{{ $transaction['status'] }}">
                                                    <i data-lucide="{{ $statusConfig[$transaction['status']]['icon'] }}"
                                                       class="w-3 h-3"></i>
                                                    {{ $statusConfig[$transaction['status']]['label'] }}
                                                </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="text-gray-300">
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('g:i A') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add this after the transactions loop, before closing the main container -->
            <div class="p-4">
                {{ $transactions->links() }}
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
