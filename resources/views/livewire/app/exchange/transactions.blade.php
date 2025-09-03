<div>
    @push('styles')
        <style>
            .status-pill {
                padding: 4px 12px;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .status-completed {
                background-color: rgba(16, 185, 129, 0.1);
                color: #10B981;
            }

            .status-pending_payment, .status-pending_confirmation {
                background-color: rgba(245, 158, 11, 0.1);
                color: #F59E0B;
            }

            .status-processing {
                background-color: rgba(59, 130, 246, 0.1);
                color: #3B82F6;
            }

            .status-failed {
                background-color: rgba(239, 68, 68, 0.1);
                color: #EF4444;
            }

            .status-cancelled {
                background-color: rgba(156, 163, 175, 0.1);
                color: #9CA3AF;
            }
        </style>
    @endpush


    <!-- The status display logic -->
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
            'cancelled' => [
                'icon' => 'slash',
                'label' => 'Cancelled'
            ]
        ];
    @endphp

    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
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
    <div class="p-1 lg:p-0 lg:py-8">
        @if(count($transactions) > 0)

            <!-- Transaction Statistics -->
            <div class="grid grid-cols-1 gap-4 md:gap-6 mb-5">
                <!-- First row on mobile - hidden on desktop -->
                <div class="grid grid-cols-3 gap-4 md:hidden">
                    <div class="bg-gray-800/2 border border-gray-700 p-4 rounded-lg">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="bg-blue-500/20 text-blue-400 p-2 rounded-full">
                                    <i data-lucide="list" class="w-5 h-5"></i>
                                </div>
                                <p class="text-xl font-bold text-white">{{ $stats['total'] }}</p>
                            </div>
                            <p class="text-sm text-gray-400">Total</p>
                        </div>
                    </div>
                    <div class="bg-gray-800/2 border border-gray-700 p-4 rounded-lg">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="bg-green-500/20 text-green-400 p-2 rounded-full">
                                    <i data-lucide="check-check" class="w-5 h-5"></i>
                                </div>
                                <p class="text-xl font-bold text-white">{{ $stats['successful'] }}</p>
                            </div>
                            <p class="text-sm text-gray-400">Successful</p>
                        </div>
                    </div>
                    <div class="bg-gray-800/2 border border-gray-700 p-4 rounded-lg">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="bg-orange-500/20 text-orange-400 p-2 rounded-full">
                                    <i data-lucide="loader" class="w-5 h-5"></i>
                                </div>
                                <p class="text-xl font-bold text-white">{{ $stats['pending'] }}</p>
                            </div>
                            <p class="text-sm text-gray-400">Pending</p>
                        </div>
                    </div>
                </div>
                <!-- Desktop view - hidden on mobile -->
                <div class="hidden md:grid md:grid-cols-4 gap-4">
                    <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-500/20 text-blue-400 p-2 rounded-full">
                                <i data-lucide="list" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Total</p>
                                <p class="text-xl font-bold text-white">{{ $stats['total'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="bg-yellow-500/20 text-yellow-400 p-2 rounded-full">
                                <i data-lucide="trending-up" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Volume (30d)</p>
                                <div class="space-y-1">
                                    @foreach($stats['volumes'] as $volume)
                                        <p class="text-lg font-bold text-white">
                                            {{ $volume['currency']->symbol }} {{ number_format($volume['amount'], 2) }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-500/20 text-green-400 p-2 rounded-full">
                                <i data-lucide="check-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Successful</p>
                                <p class="text-xl font-bold text-white">{{ $stats['successful'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="bg-orange-500/20 text-orange-400 p-2 rounded-full">
                                <i data-lucide="loader" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Pending</p>
                                <p class="text-xl font-bold text-white">{{ $stats['pending'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-950 border border-gray-900 rounded-lg p-4 md:p-6">
                <!-- Filters and Search -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div class="relative w-full md:w-auto md:flex-grow hidden md:block">
                        <i data-lucide="search"
                           class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" placeholder="Search by asset or ID..."
                               class="w-full bg-gray-800 border border-gray-800 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    </div>
                    <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto text-xs">
                        <select
                            class="bg-gray-800 border border-gray-800 rounded px-2 py-1 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362] flex-shrink-0 text-xs">
                            <option>All Status</option>
                            <option>Completed</option>
                            <option>Pending</option>
                            <option>Failed</option>
                        </select>
                        <input type="date"
                               class="bg-gray-900 border border-gray-600 rounded px-2 py-1 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362] flex-shrink-0 text-xs">
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="lg:hidden">
                    <!-- Mobile View -->
                    @foreach($transactions as $transaction)
                        <a href="{{ route('exchange.receipt', ['ref' => $transaction->reference]) }}"
                           class="block bg-gray-900/80 border border-gray-700 rounded-lg p-2 mb-3 hover:bg-gray-700/50 transition-all">
                            <div class="flex justify-between items-center mb-1">
                                                <span class="status-pill status-{{ $transaction->status }}">
                                                    <i data-lucide="{{ $statusConfig[$transaction->status]['icon'] }}"
                                                       class="w-3 h-3"></i>
                                                    {{ $statusConfig[$transaction->status]['label'] }}
                                                </span>
                                <span
                                    class="text-xs text-gray-400">{{$transaction->created_at->format('M d, Y')}}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-medium text-white">{{$transaction->fromCurrency->code}}</span>
                                    <i data-lucide="arrow-right" class="w-3 h-3 text-gray-400"></i>
                                    <span class="text-gray-400">{{$transaction->toCurrency->code}}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-white">
                                        {{$transaction->fromCurrency->symbol}} {{number_format($transaction->amount_from, 2)}}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{$transaction->toCurrency->symbol}} {{number_format($transaction->amount_to, 2)}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <!-- desktop view -->
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b border-gray-600 text-xs text-gray-400 uppercase">
                            <tr>
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Currency</th>
                                <th class="py-3 px-4 text-right">Amount</th>
                                <th class="py-3 px-4 text-center hidden md:table-cell">Status</th>
                                <th class="py-3 px-4 text-right hidden md:table-cell">Date</th>
                            </tr>
                            </thead>
                            <tbody class="text-sm">
                            <!-- display transactions from transactions component -->
                            @foreach($transactions as $transaction)
                                <tr class="border-b border-gray-700 hover:bg-gray-700/50 cursor-pointer transition-all"
                                    onclick="window.location.href='{{ route('exchange.receipt', ['ref' => $transaction->reference]) }}'">
                                    <td class="py-2 px-3">
                                        <div class="text-sm font-medium text-white">{{$transaction->reference}}</div>
                                    </td>
                                    <td class="py-2 px-3">
                                        <div class="flex items-center gap-1 text-sm">
                                            <span
                                                class="font-medium text-white">{{$transaction->fromCurrency->code}}</span>
                                            <i data-lucide="arrow-right" class="w-3 h-3 text-gray-400"></i>
                                            <span class="text-gray-400">{{$transaction->toCurrency->code}}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3 text-right">
                                        <div
                                            class="text-sm font-medium text-white">{{$transaction->fromCurrency->symbol}} {{number_format($transaction->amount_from, 2)}}</div>
                                        <div
                                            class="text-xs text-gray-400">{{$transaction->toCurrency->symbol}} {{number_format($transaction->amount_to, 2)}}</div>
                                    </td>
                                    <td class="py-2 px-3 text-center hidden md:table-cell">
                                                <span class="status-pill status-{{ $transaction->status }}">
                                                    <i data-lucide="{{ $statusConfig[$transaction->status]['icon'] }}"
                                                       class="w-3 h-3"></i>
                                                    {{ $statusConfig[$transaction->status]['label'] }}
                                                </span>
                                    </td>
                                    <td class="py-2 px-3 text-right hidden md:table-cell">
                                        <div
                                            class="text-xs text-gray-400">{{$transaction->created_at->format('M d, Y')}}</div>
                                        <div
                                            class="text-xs text-gray-500">{{$transaction->created_at->format('g:i A')}}</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                {{--                <div class="flex justify-between items-center mt-6 text-sm">--}}
                {{--                    <p class="text-gray-400">Showing 1 to 4 of 28</p>--}}
                {{--                    <div class="flex items-center gap-2">--}}
                {{--                        <button--}}
                {{--                            class="px-3 py-1 bg-gray-700 rounded-md hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"--}}
                {{--                            disabled>--}}
                {{--                            <i data-lucide="chevron-left" class="w-4 h-4"></i>--}}
                {{--                        </button>--}}
                {{--                        <button class="px-3 py-1 bg-gray-700 rounded-md hover:bg-gray-600">--}}
                {{--                            <i data-lucide="chevron-right" class="w-4 h-4"></i>--}}
                {{--                        </button>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>

        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="bg-gray-800/50 rounded-full p-4 mb-4">
                    <i data-lucide="history" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No Transactions Yet</h3>
                <p class="text-gray-400 mb-6">Start your journey by making your first exchange</p>
                <a href="{{ route('dashboard') }}"
                   class="brand-gradient text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-all inline-flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Make Your First Exchange
                </a>
            </div>
        @endif

    </div>
</div>
