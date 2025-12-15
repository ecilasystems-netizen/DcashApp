<div x-data="{
                    confirmDelete: false,
                    confirmApprove: false,
                    confirmReject: false,
                    rejectionReason: '',
                    otherRejectionReason: ''
                }">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Wallet Transaction Details</h1>
                    <p class="text-sm text-gray-400">#{{ $transaction->reference }}</p>
                </div>
                <a href="{{ route('admin.wallet-transactions') }}"
                   class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to List
                </a>
            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-600/20 border border-green-600 rounded-lg">
                <p class="text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 bg-gray-800 border border-gray-700 rounded-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white">Transaction #{{ $transaction->reference }}</h2>
                        <p class="text-sm text-gray-400">
                            {{ $transaction->created_at->format('F j, Y, g:i a') }}
                        </p>
                    </div>
                    @php
                        $statusConfig = [
                            'pending' => ['bg' => 'yellow', 'icon' => 'loader-2', 'text' => 'Pending'],
                            'completed' => ['bg' => 'green', 'icon' => 'check-circle', 'text' => 'Completed'],
                            'failed' => ['bg' => 'red', 'icon' => 'x-circle', 'text' => 'Failed'],
                            'rejected' => ['bg' => 'red', 'icon' => 'x-octagon', 'text' => 'Rejected']
                        ][$transaction->status] ?? ['bg' => 'gray', 'icon' => 'help-circle', 'text' => 'Unknown'];
                    @endphp
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-{{ $statusConfig['bg'] }}-500/10 text-{{ $statusConfig['bg'] }}-400">
                        <i data-lucide="{{ $statusConfig['icon'] }}" class="w-4 h-4"></i>
                        <span>{{ $statusConfig['text'] }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-400">Amount</p>
                        <p class="text-lg font-semibold text-white">{{ $transaction->wallet->currency->code ?? '' }} {{ number_format($transaction->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Charge</p>
                        <p class="text-lg font-semibold text-white">{{ $transaction->wallet->currency->code ?? '' }} {{ number_format($transaction->charge, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Type</p>
                        <p class="text-white capitalize">{{ $transaction->type }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Direction</p>
                        <p class="{{ $transaction->direction === 'credit' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $transaction->direction === 'credit' ? 'Credit (In)' : 'Debit (Out)' }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-gray-400">Description</p>
                        <p class="text-white">{{ $transaction->description }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Balance Before</p>
                        <p class="text-white">{{ $transaction->wallet->currency->code ?? '' }} {{ number_format($transaction->balance_before, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Balance After</p>
                        <p class="text-white">{{ $transaction->wallet->currency->code ?? '' }} {{ number_format($transaction->balance_after, 2) }}</p>
                    </div>
                </div>


                @if( $transaction->metadata)
                    <div class="mt-8 pt-6 border-t border-gray-700">
                        <h3 class="text-lg font-bold text-white mb-4">Transaction Metadata</h3>
                        <div class="bg-gray-700/30 border border-gray-600   rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                @foreach($transaction->metadata as $key => $value)
                                    <div>
                                        <p class="text-gray-400 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                                        @if(is_array($value))
                                            <pre
                                                class="bg-gray-800 rounded p-2 overflow-auto text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            <p class="text-white font-medium break-words">
                                                {{ $value }}
                                            </p>

                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($transaction->status === 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-700 flex gap-4">
                        <button @click="confirmApprove = true"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Approve
                        </button>
                        <button @click="confirmReject = true"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                            <i data-lucide="x-circle" class="w-4 h-4"></i> Reject
                        </button>
                    </div>
                @endif
            </div>

            <!-- User & Wallet Details -->
            <div class="space-y-6">
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">User Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Name:</span>
                            <span
                                class="text-white">{{ $transaction->user->fname }} {{ $transaction->user->lname }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Email:</span>
                            <span class="text-white">{{ $transaction->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Phone:</span>
                            <span class="text-white">{{ $transaction->user->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Wallet Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Wallet ID:</span>
                            <span class="text-white font-mono text-xs">#{{ $transaction->wallet->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Currency:</span>
                            <span class="text-white">{{ $transaction->wallet->currency->name }} ({{ $transaction->wallet->currency->code }})</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Current Balance:</span>
                            <span
                                class="text-white font-semibold">{{ number_format($transaction->wallet->balance, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Actions</h3>
                    <button @click="confirmDelete = true"
                            class="w-full bg-red-600/20 hover:bg-red-600/40 text-red-400 font-semibold py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div x-show="confirmApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="confirmApprove = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Approval</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to approve this transaction?</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmApprove = false"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="$wire.approveTransaction(); confirmApprove = false"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">Approve
                </button>
            </div>
        </div>
    </div>

    <div x-show="confirmReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="confirmReject = false"
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
                <button @click="confirmReject = false; rejectionReason = ''; otherRejectionReason = ''"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="() => {
                                const reason = rejectionReason === 'other' ? otherRejectionReason : rejectionReason;
                                $wire.rejectTransaction(reason);
                                confirmReject = false;
                                rejectionReason = '';
                                otherRejectionReason = '';
                            }" :disabled="!rejectionReason || (rejectionReason === 'other' && !otherRejectionReason)"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg disabled:bg-red-800 disabled:cursor-not-allowed">
                    Reject
                </button>
            </div>
        </div>
    </div>

    <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="confirmDelete = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Delete</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to delete this transaction? This action cannot be
                undone.</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmDelete = false"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                </button>
                <button @click="$wire.deleteTransaction(); confirmDelete = false"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Delete
                </button>
            </div>
        </div>
    </div>
</div>
