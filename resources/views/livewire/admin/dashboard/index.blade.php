<div>
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Hello, {{Auth::user()->fname}}</h1>
                <div class="flex items-center gap-4">


                    {{-- Add this --}}
                    <div class="hidden lg:flex items-center space-x-4">
                        <div class="relative" x-data="{ showNotifications: false }">
                            <button @click="showNotifications = !showNotifications"
                                    class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 hover:text-[#E1B362] transition-all relative">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                @if($pendingCount > 0)
                                    <span
                                        class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full text-xs flex items-center justify-center text-white border-2 border-gray-800">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notifications Panel -->
                            <div x-show="showNotifications" wire:cloak
                                 @click.away="showNotifications = false"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-x-8"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform translate-x-0"
                                 x-transition:leave-end="opacity-0 transform translate-x-8"
                                 class="absolute right-0 mt-2 w-[26rem] bg-gray-800 rounded-lg shadow-lg border border-gray-700 z-50">

                                <!-- Panel Header -->
                                <div class="p-4 border-b border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-white">Notifications</h3>
                                        <span class="text-xs text-gray-400">{{ $pendingCount }} new</span>
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div class="divide-y divide-gray-700 max-h-[400px] overflow-y-auto">
                                    @if($pendingKycs->count() > 0)
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-400 mb-2">Pending KYC
                                                Verifications</h4>
                                            @foreach($pendingKycs as $kyc)
                                                <a href="{{ route('admin.kyc', $kyc) }}"
                                                   class="block p-2 hover:bg-gray-700 rounded-lg transition-colors mb-2">
                                                    <div class="flex items-center space-x-3">
                                                        <div
                                                            class="w-8 h-8 bg-yellow-500/10 rounded-full flex items-center justify-center">
                                                            <i data-lucide="user-check"
                                                               class="w-4 h-4 text-yellow-500"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm text-white">{{ $kyc->user->name }}</p>
                                                            <p class="text-xs text-gray-400">
                                                                Submitted {{ $kyc->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($pendingTransactions->count() > 0)
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-400 mb-2">Pending Transactions</h4>
                                            @foreach($pendingTransactions as $transaction)
                                                <a href="{{ route('admin.transactions', $transaction) }}"
                                                   class="block p-2 hover:bg-gray-700 rounded-lg transition-colors mb-2">
                                                    <div class="flex items-center space-x-3">
                                                        <div
                                                            class="w-8 h-8 bg-blue-500/10 rounded-full flex items-center justify-center">
                                                            <i data-lucide="credit-card"
                                                               class="w-4 h-4 text-blue-500"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm text-white">
                                                                {{ $transaction->fromCurrency->code }}
                                                                → {{ $transaction->toCurrency->code }}
                                                            </p>
                                                            <p class="text-xs text-gray-400">
                                                                {{ number_format($transaction->amount_from, 2) }} {{ $transaction->fromCurrency->code }}
                                                            </p>
                                                        </div>
                                                        <span
                                                            class="text-xs text-gray-400">{{ $transaction->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($pendingCount === 0)
                                        <div class="p-8 text-center text-gray-400">
                                            <i data-lucide="check-circle"
                                               class="w-12 h-12 mx-auto mb-3 text-gray-600"></i>
                                            <p>No pending notifications</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="p-6 space-y-8">


        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-400">Total Users</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($this->stats['total_users']) }}</p>
                    </div>
                    <div class="p-2 bg-blue-500/20 text-blue-400 rounded-full">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                </div>


                <p class="text-xs {{ $this->stats['user_growth'] >= 0 ? 'text-green-400' : 'text-red-400' }} mt-2">
                    {{ $this->stats['user_growth'] >= 0 ? '+' : '' }}{{ $this->stats['user_growth'] }}% from last month
                </p>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-400">Total Volume (30d)</p>
                        <p class="text-3xl font-bold text-white">
                            ₱{{ number_format($this->stats['total_volume'], 0) }}</p>
                    </div>
                    <div class="p-2 bg-yellow-500/20 text-yellow-400 rounded-full">
                        <i data-lucide="trending-up" class="w-6 h-6"></i>
                    </div>
                </div>
                <p class="text-xs {{ $this->stats['volume_growth'] >= 0 ? 'text-green-400' : 'text-red-400' }} mt-2">
                    {{ $this->stats['volume_growth'] >= 0 ? '+' : '' }}{{ $this->stats['volume_growth'] }}% from last
                    month
                </p>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-400">Pending KYC</p>
                        <p class="text-3xl font-bold text-white">{{ $this->stats['pending_kyc'] }}</p>
                    </div>
                    <div class="p-2 bg-orange-500/20 text-orange-400 rounded-full">
                        <i data-lucide="file-clock" class="w-6 h-6"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Awaiting review</p>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-400">Revenue (30d)</p>
                        <p class="text-3xl font-bold text-white">₱{{ number_format($this->stats['revenue'], 0) }}</p>
                    </div>
                    <div class="p-2 bg-green-500/20 text-green-400 rounded-full">
                        <i data-lucide="wallet" class="w-6 h-6"></i>
                    </div>
                </div>
                <p class="text-xs {{ $this->stats['revenue_growth'] >= 0 ? 'text-green-400' : 'text-red-400' }} mt-2">
                    {{ $this->stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $this->stats['revenue_growth'] }}% from last
                    month
                </p>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <h3 class="font-bold text-white mb-4">Recent Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-2 text-gray-400">User</th>
                            <th class="py-2 text-gray-400">Amount</th>
                            <th class="py-2 text-gray-400">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($this->recentTransactions as $transaction)
                            <tr class="border-b border-gray-700/50">
                                <td class="py-3 text-white">{{ $transaction->user->fname }} {{ $transaction->user->lname }}</td>
                                <td class="py-3 text-gray-300">
                                    {{ number_format($transaction->amount_from) }} {{ $transaction->fromCurrency->code ?? 'N/A' }}
                                    → {{ number_format($transaction->amount_to) }} {{ $transaction->toCurrency->code ?? 'N/A' }}
                                </td>
                                <td class="py-3">
                                                        <span class="status-pill status-{{ $transaction->status }}">
                                                            {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                                        </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-400">No recent transactions</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($this->recentTransactions->count() > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.transactions') }}"
                           class="text-[#E1B362] hover:text-[#E1B362]/80 text-sm">
                            View all transactions →
                        </a>
                    </div>
                @endif
            </div>
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <h3 class="font-bold text-white mb-4">Recent KYC Submissions</h3>
                <ul class="space-y-4">
                    @forelse($this->recentKyc as $kyc)
                        <li class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-white">{{ $kyc->user->fname }} {{ $kyc->user->lname }}</p>
                                <p class="text-xs text-gray-400">Submitted {{ $kyc->created_at->diffForHumans() }}</p>
                            </div>
                            <button wire:click="reviewKyc({{ $kyc->id }})"
                                    class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-1 px-3 rounded-lg text-xs transition-colors">
                                Review
                            </button>
                        </li>
                    @empty
                        <li class="text-center text-gray-400">No pending KYC submissions</li>
                    @endforelse
                </ul>
                @if($this->recentKyc->count() > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.kyc') }}" class="text-[#E1B362] hover:text-[#E1B362]/80 text-sm">
                            View all KYC submissions →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <h3 class="font-bold text-white mb-4">Transaction Volume (Last 7 Days)</h3>
                <canvas id="volumeChart"></canvas>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
                <h3 class="font-bold text-white mb-4">User Registrations</h3>
                <canvas id="userChart"></canvas>
            </div>
        </div>

    </div>


    @push('styles')
        <style>
            .status-pill {
                padding: 0.25rem 0.5rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .status-completed {
                background-color: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .status-pending, .status-pending_confirmation {
                background-color: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .status-failed, .status-rejected {
                background-color: rgba(239, 68, 68, 0.1);
                color: #ef4444;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const chartConfig = {
                    color: "#9ca3af",
                    grid: {color: "#4b5563"},
                    ticks: {color: "#d1d5db"},
                }

                const chartData = @json($this->chartData);

                // Transaction Volume Chart
                const volumeCtx = document.getElementById("volumeChart").getContext("2d");
                new Chart(volumeCtx, {
                    type: "line",
                    data: {
                        labels: chartData.days,
                        datasets: [{
                            label: "Volume (PHP)",
                            data: chartData.volumes,
                            borderColor: "#E1B362",
                            backgroundColor: "rgba(225, 179, 98, 0.1)",
                            tension: 0.4,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {legend: {display: false}},
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: chartConfig.grid,
                                ticks: {
                                    color: chartConfig.ticks.color,
                                    callback: (value) => `₱${(value / 1000).toFixed(0)}k`,
                                },
                            },
                            x: {
                                grid: {display: false},
                                ticks: {color: chartConfig.ticks.color},
                            },
                        },
                    },
                });

                // User Registrations Chart
                const userCtx = document.getElementById("userChart").getContext("2d");
                new Chart(userCtx, {
                    type: "doughnut",
                    data: {
                        labels: ["Verified", "Pending", "New"],
                        datasets: [{
                            data: [
                                chartData.user_stats.verified,
                                chartData.user_stats.pending,
                                chartData.user_stats.new
                            ],
                            backgroundColor: ["#10B981", "#F59E0B", "#3B82F6"],
                            borderColor: "#1f2937",
                            borderWidth: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "bottom",
                                labels: {color: chartConfig.color},
                            },
                        },
                    },
                });
            });
        </script>
    @endpush
</div>
