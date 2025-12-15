<div x-data="{
        confirmComplete: null,
        confirmReject: null,
        rejectionReason: '',
        otherRejectionReason: ''
    }">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Redemption Requests</h1>
            </div>
        </header>
    </x-slot>

    @push('styles')
        <style>
            .modal-overlay {
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(1px);
            }
        </style>
    @endpush

    <div class="p-6">
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-600/20 border border-green-600 rounded-lg">
                <p class="text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4"><i data-lucide="list-checks"
                                                                                   class="w-6 h-6"></i></div>
                <div><p class="text-sm text-gray-400">Total Requests</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($this->stats['total']) }}</p></div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4"><i data-lucide="clock"
                                                                                       class="w-6 h-6"></i></div>
                <div><p class="text-sm text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($this->stats['pending']) }}</p></div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-green-500/20 text-green-400 rounded-full mr-4"><i data-lucide="check-circle"
                                                                                     class="w-6 h-6"></i></div>
                <div><p class="text-sm text-gray-400">Completed</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($this->stats['completed']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-red-500/20 text-red-400 rounded-full mr-4"><i data-lucide="x-circle"
                                                                                 class="w-6 h-6"></i></div>
                <div><p class="text-sm text-gray-400">Rejected</p>
                    <p class="text-2xl font-bold text-red-400">{{ number_format($this->stats['rejected']) }}</p></div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-purple-500/20 text-purple-400 rounded-full mr-4"><i data-lucide="coins"
                                                                                       class="w-6 h-6"></i></div>
                <div><p class="text-sm text-gray-400">Total Redeemed</p>
                    <p class="text-2xl font-bold text-purple-400">{{ number_format($this->stats['total_amount']) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <select wire:model.live="statusFilter"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="rejected">Rejected</option>
            </select>
            <select wire:model.live="currencyFilter"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <option value="">All Currencies</option>
                <option value="USDT">USDT</option>
                <option value="NGN">NGN</option>
                <option value="USD">USD</option>
            </select>
            <input wire:model.live="dateFilter" type="date"
                   class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500"/>
            <button wire:click="resetFilters"
                    class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-700 transition-colors"
                    title="Reset Filters">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search by reference, user name, or email..."
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500"/>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Reference</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Currency</th>
                    <th class="px-6 py-3">Exchange Rate</th>
                    <th class="px-6 py-3">Details</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($this->redemptionRequests as $redemption)
                    <tr class="hover:bg-gray-700/30">
                        <td class="px-6 py-4">
                            <div
                                class="font-medium text-white">{{ $redemption->user->fname }} {{ $redemption->user->lname }}</div>
                            <div class="text-xs text-gray-400">{{ $redemption->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs text-blue-400">#{{ $redemption->reference }}</div>
                            <div
                                class="text-xs text-gray-400">{{ $redemption->created_at->format('Y-m-d H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ number_format($redemption->amount) }} DCoins</div>
                            <div class="text-xs text-yellow-400">
                                ≈ {{ $redemption->currency }} {{ number_format($redemption->equivalent_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($redemption->currency === 'USDT')
                                    <i data-lucide="wallet" class="w-4 h-4 text-blue-400"></i>
                                @else
                                    <i data-lucide="building-2" class="w-4 h-4 text-green-400"></i>
                                @endif
                                <span class="font-semibold text-white">{{ $redemption->currency }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="font-mono text-xs text-gray-300">{{ number_format($redemption->exchange_rate, 6) }}</div>
                            <div class="text-xs text-gray-400">1 DCoin
                                = {{ number_format($redemption->exchange_rate, 6) }} {{ $redemption->currency }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($redemption->currency === 'USDT' && $redemption->wallet_details)
                                <div class="text-xs space-y-1">
                                    <div class="text-gray-400">Network: <span
                                            class="text-white">{{ $redemption->wallet_details['network'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="text-gray-400">Address:</div>
                                    <div class="flex items-center gap-2 bg-gray-700/50 rounded px-2 py-1">
                                        <span
                                            class="font-mono text-blue-400 text-xs truncate max-w-[120px]">{{ $redemption->wallet_details['address'] ?? 'N/A' }}</span>
                                        <!-- For wallet address copy button -->
                                        <button
                                            x-data="{ copied: false }"
                                            @click="navigator.clipboard.writeText('{{ $redemption->wallet_details['address'] ?? '' }}').then(() => {
        copied = true;
        setTimeout(() => copied = false, 1500);
    })"
                                            class="text-gray-400 hover:text-blue-400 transition-colors"
                                            title="Copy wallet address">
                                            <i :data-lucide="copied ? 'check' : 'copy'" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </div>
                            @elseif($redemption->bank_details)
                                <div class="text-xs space-y-1">
                                    <div class="text-gray-400">Bank: <span
                                            class="text-white">{{ $redemption->bank_details['bank_name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="text-gray-400">Account: <span
                                            class="text-white">{{ $redemption->bank_details['account_name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="text-gray-400">Number:</div>
                                    <div class="flex items-center gap-2 bg-gray-700/50 rounded px-2 py-1">
                                        <span
                                            class="font-mono text-green-400 text-xs">{{ $redemption->bank_details['account_number'] ?? 'N/A' }}</span>
                                        <!-- For account number copy button -->
                                        <button
                                            x-data="{ copied: false }"
                                            @click="navigator.clipboard.writeText('{{ $redemption->bank_details['account_number'] ?? '' }}').then(() => {
        copied = true;
        setTimeout(() => copied = false, 1500);
    })"
                                            class="text-gray-400 hover:text-green-400 transition-colors"
                                            title="Copy account number">
                                            <i :data-lucide="copied ? 'check' : 'copy'" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'yellow', 'icon' => 'clock', 'text' => 'Pending'],
                                    'completed' => ['bg' => 'green', 'icon' => 'check-circle', 'text' => 'Completed'],
                                    'rejected' => ['bg' => 'red', 'icon' => 'x-circle', 'text' => 'Rejected']
                                ][$redemption->status] ?? ['bg' => 'gray', 'icon' => 'help-circle', 'text' => 'Unknown'];
                            @endphp
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusConfig['bg'] }}-500/10 text-{{ $statusConfig['bg'] }}-400 border border-{{ $statusConfig['bg'] }}-500/30">
                                <i data-lucide="{{ $statusConfig['icon'] }}" class="w-4 h-4"></i>
                                {{ $statusConfig['text'] }}
                            </div>
                            @if($redemption->notes)
                                <div class="text-xs text-gray-400 mt-1">{{ $redemption->notes }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-300">{{ $redemption->created_at->format('M d, Y') }}</div>
                            @if($redemption->processed_at)
                                <div class="text-xs text-gray-400">
                                    Processed: {{ $redemption->processed_at->format('M d') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($redemption->status === 'pending')
                                <div class="flex items-center gap-2 justify-end">
                                    <button @click="confirmComplete = {{ $redemption->id }}"
                                            class="p-2 text-green-400 hover:text-green-300 hover:bg-green-500/10 rounded-lg transition-colors"
                                            title="Mark as Completed">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="confirmReject = {{ $redemption->id }}"
                                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors"
                                            title="Reject Request">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-500 text-xs">No actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-gray-700/50 rounded-full flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-8 h-8 text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-lg">No redemption requests found</p>
                                    <p class="text-gray-500 text-sm">Redemption requests will appear here when users
                                        submit them</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if($this->redemptionRequests->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $this->redemptionRequests->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Complete Confirmation Modal -->
    <div x-show="confirmComplete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center modal-overlay p-4">
        <div @click.away="confirmComplete = null"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Mark as Completed</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to mark this redemption request as completed? This
                action will finalize the request.</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmComplete = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <button @click="$wire.markCompleted(confirmComplete); confirmComplete = null"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    Mark Completed
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div x-show="confirmReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center modal-overlay p-4">
        <div @click.away="confirmReject = null"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Reject Request</h3>
            <p class="text-gray-300 mb-6">Please provide a reason for rejecting this redemption request.</p>
            <div class="space-y-4">
                <select x-model="rejectionReason"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <option value="" disabled>Select a reason...</option>
                    <option value="Insufficient DCoins balance">Insufficient DCoins balance</option>
                    <option value="Invalid payment details">Invalid payment details</option>
                    <option value="Suspected fraudulent activity">Suspected fraudulent activity</option>
                    <option value="Technical issues">Technical issues</option>
                    <option value="other">Other (Please specify)</option>
                </select>
                <div x-show="rejectionReason === 'other'">
                    <input type="text" x-model="otherRejectionReason" placeholder="Specify other reason"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button @click="confirmReject = null; rejectionReason = ''; otherRejectionReason = ''"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <button @click="() => {
                    const reason = rejectionReason === 'other' ? otherRejectionReason : rejectionReason;
                    $wire.markRejected(confirmReject, reason);
                    confirmReject = null;
                    rejectionReason = '';
                    otherRejectionReason = '';
                }" :disabled="!rejectionReason || (rejectionReason === 'other' && !otherRejectionReason)"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg disabled:bg-red-800 disabled:cursor-not-allowed transition-colors">
                    Reject Request
                </button>
            </div>
        </div>
    </div>
</div>
