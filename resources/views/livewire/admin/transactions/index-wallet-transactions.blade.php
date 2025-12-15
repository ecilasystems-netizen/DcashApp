<div x-data="{
        confirmDelete: null,
        confirmApprove: null,
        confirmReject: null,
        confirmRefund: null,
        rejectionReason: '',
        otherRejectionReason: ''
    }">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Wallet Transactions</h1>
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

        <!-- Simple Display -->
        @if($safeHavenAccount)
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-6">
                <h3 class="text-white font-bold mb-3">SafeHaven Account Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400">Account Number:</span>
                        <span class="text-white font-medium ml-2">{{ $safeHavenAccount['accountNumber'] }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Account Name:</span>
                        <span class="text-white font-medium ml-2">{{ $safeHavenAccount['accountName'] }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Available Balance:</span>
                        <span class="text-[#E1B362] font-bold ml-2">
                           {{ $safeHavenAccount['currencyCode'] }} {{ number_format($safeHavenAccount['accountBalance'], 2) }}
                       </span>
                    </div>
                    <div>
                        <span class="text-gray-400">Status:</span>
                        <span
                            class="ml-2 px-2 py-1 rounded text-xs {{ $safeHavenAccount['status'] === 'Active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                           {{ $safeHavenAccount['status'] }}
                       </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                    <i data-lucide="list-checks" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Transactions</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($this->stats['total']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
                    <i data-lucide="loader-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($this->stats['pending']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-green-500/20 text-green-400 rounded-full mr-4">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Completed</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($this->stats['completed']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="p-3 bg-red-500/20 text-red-400 rounded-full mr-4">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Failed / Rejected</p>
                    <p class="text-2xl font-bold text-red-400">{{ number_format($this->stats['failed']) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <select wire:model.live="statusFilter"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="rejected">Rejected</option>
            </select>
            <select wire:model.live="typeFilter"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="">All Types</option>
                <option value="deposit">Deposit</option>
                <option value="airtime">Airtime</option>
                <option value="data">Data</option>
                <option value="electricity">Electricity</option>
                <option value="tv">TV</option>
                <option value="transfer">Transfer</option>
                <option value="withdrawal">Withdrawal</option>
            </select>
            <input wire:model.live="dateFilter" type="date"
                   class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            <button wire:click="resetFilters"
                    class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-700 transition-colors"
                    title="Reset Filters">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search by reference, user, or description..."
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Reference</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Balance Before</th>
                    <th class="px-6 py-3">Balance After</th>
                    {{--                    <th class="px-6 py-3">Description</th>--}}
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($this->transactions as $transaction)
                    <tr class="cursor-pointer hover:bg-gray-700/30"
                        onclick="window.location='{{ route('admin.wallet-transactions.show', $transaction) }}'">
                        <td class="px-6 py-4">
                            <div
                                class="font-medium text-white">{{ $transaction->user->fname }} {{ $transaction->user->lname }}</div>
                            <div class="text-xs text-gray-400">{{ $transaction->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs">#{{ $transaction->reference }}</div>
                            <div
                                class="text-xs text-gray-400">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="capitalize">{{ $transaction->type }}</div>
                            <div
                                class="text-xs {{ $transaction->direction === 'credit' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $transaction->direction === 'credit' ? 'Credit' : 'Debit' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="font-medium text-white">{{ $transaction->wallet->currency->code ?? '' }} {{ number_format($transaction->amount, 2) }}</div>
                            <div class="text-xs text-gray-400">Fee: {{ number_format($transaction->charge, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="font-medium text-gray-300">{{ $transaction->wallet->currency->symbol ?? '' }} {{ number_format($transaction->balance_before, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="font-medium text-gray-300">{{ $transaction->wallet->currency->symbol ?? '' }} {{ number_format($transaction->balance_after, 2) }}</div>
                        </td>
                        {{--                        <td class="px-6 py-4 text-gray-300 max-w-xs truncate">{{ $transaction->description }}</td>--}}
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'yellow', 'icon' => 'loader-2', 'text' => 'Pending'],
                                    'completed' => ['bg' => 'green', 'icon' => 'check-circle', 'text' => 'Completed'],
                                    'failed' => ['bg' => 'red', 'icon' => 'x-circle', 'text' => 'Failed'],
                                    'rejected' => ['bg' => 'red', 'icon' => 'x-octagon', 'text' => 'Rejected'],
                                    'refunded' => ['bg' => 'blue', 'icon' => 'rotate-ccw', 'text' => 'Refunded'],
                                ][$transaction->status] ?? ['bg' => 'gray', 'icon' => 'help-circle', 'text' => 'Unknown'];
                            @endphp
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusConfig['bg'] }}-500/10 text-{{ $statusConfig['bg'] }}-400">
                                <i data-lucide="{{ $statusConfig['icon'] }}" class="w-4 h-4"></i>
                                {{ $statusConfig['text'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="relative" x-data="{ open: false }">
                                <button @click.stop="open = !open"
                                        class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700 transition-colors">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-xl z-50"
                                     x-transition style="display: none;">
                                    <div class="py-1">
                                        @if($transaction->status === 'pending')
                                            <button @click.stop="confirmApprove = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-green-400 hover:bg-gray-600 w-full text-left">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i> Approve
                                            </button>
                                            <button @click.stop="confirmReject = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-yellow-400 hover:bg-gray-600 w-full text-left">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i> Reject
                                            </button>
                                            <hr class="border-gray-600 my-1">
                                        @elseif($transaction->status === 'failed')
                                            <button @click.stop="confirmRefund = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-blue-400 hover:bg-gray-600 w-full text-left">
                                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Refund
                                            </button>
                                            <hr class="border-gray-600 my-1">
                                        @endif
                                        <button @click.stop="confirmDelete = {{ $transaction->id }}; open = false"
                                                class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-gray-600 w-full text-left">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-400">No wallet transactions found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $this->transactions->links() }}
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div x-show="confirmApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center modal-overlay p-4">
        <div @click.away="confirmApprove = null"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Approval</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to approve this transaction?</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmApprove = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="$wire.approveTransaction(confirmApprove); confirmApprove = null"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">Approve
                </button>
            </div>
        </div>
    </div>

    <!-- refund modal -->
    <div x-show="confirmRefund" x-cloak class="fixed inset-0 z-50 flex items-center justify-center modal-overlay p-4">
        <div @click.away="confirmRefund = null"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Refund</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to refund this failed transaction? The amount will be
                credited back to the user's wallet.</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmRefund = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="$wire.refundTransaction(confirmRefund); confirmRefund = null"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">Refund
                </button>
            </div>
        </div>
    </div>

    <div x-show="confirmReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center modal-overlay p-4">
        <div @click.away="confirmReject = null"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Rejection</h3>
            <p class="text-gray-300 mb-6">Please provide a reason for rejecting this transaction.</p>
            <div class="space-y-4">
                <select x-model="rejectionReason"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    <option value="" disabled>Select a reason...</option>
                    <option value="Insufficient funds">Insufficient funds</option>
                    <option value="Incorrect details">Incorrect details</option>
                    <option value="Suspected fraudulent activity">Suspected fraudulent activity</option>
                    <option value="other">Other (Please specify)</option>
                </select>
                <div x-show="rejectionReason === 'other'">
                    <input type="text" x-model="otherRejectionReason" placeholder="Specify other reason"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button @click="confirmReject = null; rejectionReason = ''; otherRejectionReason = ''"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="() => {
                    const reason = rejectionReason === 'other' ? otherRejectionReason : rejectionReason;
                    $wire.rejectTransaction(confirmReject, reason);
                    confirmReject = null;
                    rejectionReason = '';
                    otherRejectionReason = '';
                }" :disabled="!rejectionReason || (rejectionReason === 'other' && !otherRejectionReason)"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg disabled:bg-red-800 disabled:cursor-not-allowed">
                    Reject
                </button>
            </div>
        </div>
    </div>

    <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center modal-overlay p-4">
        <div @click.away="confirmDelete = null"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Delete</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to delete this transaction? This action cannot be
                undone.</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmDelete = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="$wire.deleteTransaction(confirmDelete); confirmDelete = null"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Delete
                </button>
            </div>
        </div>
    </div>
</div>
