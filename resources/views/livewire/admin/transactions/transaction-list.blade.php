<div x-data="{
            isModalOpen: false,
            proofImage: '',
            proofUser: '',
            proofId: '',
            confirmDelete: null,
            confirmApprove: null,
            confirmReject: null,
            rejectionReason: '',
            otherRejectionReason: '',
            exportDropdown: false
        }">
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Transaction Management</h1>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="bg-[#E1B362] hover:bg-[#E1B362]/80 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Export
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-lg z-20"
                         x-transition>
                        <button wire:click="exportCsv" @click="open = false"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 w-full text-left rounded-t-lg">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Export as CSV
                        </button>
                        <button wire:click="exportExcel" @click="open = false"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 w-full text-left rounded-b-lg">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                            Export as Excel
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

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

            .status-pending {
                background-color: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .status-failed, .status-rejected {
                background-color: rgba(239, 68, 68, 0.1);
                color: #ef4444;
            }

            /* Ensure dropdowns are above other elements */
            .dropdown-menu {
                z-index: 1000 !important;
                position: absolute !important;
            }

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

        <!-- Statistics -->
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                    <i data-lucide="list-checks" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Transactions</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($this->stats['total']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
                    <i data-lucide="loader-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($this->stats['pending']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-green-500/20 text-green-400 rounded-full mr-4">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Completed</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($this->stats['completed']) }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-red-500/20 text-red-400 rounded-full mr-4">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Failed / Rejected</p>
                    <p class="text-2xl font-bold text-red-400">{{ number_format($this->stats['failed']) }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex items-center gap-2">
                <select wire:model.live="statusFilter"
                        class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    <option value="">All Statuses</option>
                    <option value="pending_confirmation">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="rejected">Rejected</option>
                </select>

                <button wire:click="resetFilters"
                        class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-700 transition-colors"
                        title="Reset Filters">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <input wire:model.live="dateFilter" type="date"
                   class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>

            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by ID or user..."
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-6 py-3">S/N</th>
                    <th class="px-6 py-3">POP</th>
                    <th class="px-6 py-3">ORDER</th>
                    <th class="px-6 py-3">AMOUNT</th>
                    <th class="px-6 py-3">DETAILS</th>
                    <th class="px-6 py-3">STATUS</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($this->transactions as $index => $transaction)
                    <tr class="hover:bg-gray-700/30">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            @if($transaction->payment_proof)
                                <div class="flex gap-2">
                                    @foreach(json_decode($transaction->payment_proof, true) ?? [] as $image)
                                        <img src="{{ Storage::url($image) }}"
                                             class="w-16 h-12 object-cover rounded-md cursor-pointer"
                                             @click="isModalOpen = true; proofImage = '{{ asset(Storage::url($image)) }}'"/>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs">#{{ $transaction->reference }}</div>
                            <div
                                class="text-xs text-gray-400">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</div>
                            <div class="text-sm">{{ $transaction->fromCurrency->code }}
                                -{{ $transaction->toCurrency->code }}</div>
                            <div class="text-xs text-gray-400">Rate: {{ number_format($transaction->rate, 4) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                Send: {{ $transaction->fromCurrency->code }} {{ number_format($transaction->amount_from, 2) }}</div>
                            <div>
                                Receive: {{ $transaction->toCurrency->code }} {{ number_format($transaction->amount_to, 2) }}</div>
                            <div class="text-xs text-gray-400">Method: {{ $transaction->payment_method }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm">
                                Name: {{ $transaction->user->fname }} {{ $transaction->user->lname }}</div>
                            <div class="text-xs text-gray-400">Email: {{ $transaction->user->email }}</div>
                            <div class="mt-2">
                                <div class="text-sm">Receiver Details</div>
                                <div class="text-sm">Name: {{ $transaction->recipient_account_name }}</div>
                                <div class="text-sm">Bank: {{ $transaction->recipient_bank_name }}</div>
                                <div class="text-xs text-gray-400">
                                    Account: {{ $transaction->recipient_account_number }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">

                            @if(!empty($transaction->note))
                                <div
                                    class="mb-2 p-2 bg-gray-700 border border-gray-600 rounded-lg text-xs text-gray-300">
                                    Note: {{ $transaction->note }}
                                </div>
                            @endif
                            @php
                                $statusConfig = [
                                    'pending_confirmation' => ['bg' => 'yellow', 'icon' => 'loader-2', 'text' => 'Pending'],
                                    'completed' => ['bg' => 'green', 'icon' => 'check-circle', 'text' => 'Completed'],
                                    'failed' => ['bg' => 'red', 'icon' => 'x-circle', 'text' => 'Failed'],
                                    'rejected' => ['bg' => 'red', 'icon' => 'x-octagon', 'text' => 'Rejected']
                                ][$transaction->status] ?? ['bg' => 'gray', 'icon' => 'help-circle', 'text' => 'Unknown'];
                            @endphp
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusConfig['bg'] }}-500/10 text-{{ $statusConfig['bg'] }}-400">
                                <i data-lucide="{{ $statusConfig['icon'] }}" class="w-4 h-4"></i>
                                {{ $statusConfig['text'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($transaction->status === 'pending_confirmation')
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700 transition-colors">
                                        <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                    </button>
                                    <div x-show="open"
                                         @click.away="open = false"
                                         class="dropdown-menu absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-xl z-50"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         style="display: none;">
                                        <div class="py-1">
                                            <button @click="confirmApprove = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-green-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Approve Transaction
                                            </button>
                                            <button @click="confirmReject = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-yellow-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                Reject Transaction
                                            </button>
                                            <hr class="border-gray-600 my-1">
                                            <button @click="confirmDelete = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                Delete Transaction
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700 transition-colors">
                                        <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                    </button>
                                    <div x-show="open"
                                         @click.away="open = false"
                                         class="dropdown-menu absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-xl z-50"
                                         x-transition
                                         style="display: none;">
                                        <div class="py-1">
                                            <button @click="confirmApprove = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-green-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Approve Transaction
                                            </button>
                                            <button @click="confirmReject = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-yellow-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                Reject Transaction
                                            </button>
                                            <hr class="border-gray-600 my-1">
                                            <button @click="confirmDelete = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                Delete Transaction
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                            No transactions found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $this->transactions->links() }}
            </div>
        </div>
    </div>

    <!-- Proof Modal -->
    <div x-show="isModalOpen" x-transition wire:cloak
         class="fixed inset-0 z-50 flex items-center justify-center modal-overlay" style="display: none">
        <div @click.away="isModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-lg p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-bold text-white" x-text="`Proof for ${proofId}`"></h3>
                    <p class="text-sm text-gray-400" x-text="`Submitted by ${proofUser}`"></p>
                </div>
                <button @click="isModalOpen = false"
                        class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="bg-gray-900 rounded-lg overflow-hidden">
                <img :src="proofImage"
                     alt="Proof of Payment"
                     class="w-full h-auto object-contain max-h-[60vh]"
                />
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div x-show="confirmApprove" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Approval</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to approve this transaction?</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmApprove = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Cancel
                </button>
                <button @click="$wire.approveTransaction(confirmApprove); confirmApprove = null"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Approve
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div x-show="confirmReject" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Rejection</h3>
            <p class="text-gray-300 mb-6">Please select a reason for rejecting this transaction.</p>
            <div class="space-y-4">
                <select x-model="rejectionReason"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    <option value="" disabled>Select a reason...</option>
                    <option value="Invalid Proof of Payment">Invalid Proof of Payment</option>
                    <option value="Incorrect Amount">Incorrect Amount</option>
                    <option value="Fraudulent Activity Suspected">Fraudulent Activity Suspected</option>
                    <option value="other">Other (Please specify)</option>
                </select>
                <div x-show="rejectionReason === 'other'">
                    <input type="text" x-model="otherRejectionReason" placeholder="Specify other reason"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button @click="confirmReject = null; rejectionReason = ''; otherRejectionReason = ''"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Cancel
                </button>
                <button
                    @click="() => {
                        const reason = rejectionReason === 'other' ? otherRejectionReason : rejectionReason;
                        $wire.rejectTransaction(confirmReject, reason);
                        confirmReject = null;
                        rejectionReason = '';
                        otherRejectionReason = '';
                    }"
                    :disabled="!rejectionReason || (rejectionReason === 'other' && !otherRejectionReason)"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg disabled:bg-red-800 disabled:cursor-not-allowed">
                    Reject
                </button>
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div x-show="confirmDelete" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Delete</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to delete this transaction? This action cannot be
                undone.</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmDelete = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Cancel
                </button>
                <button @click="$wire.deleteTransaction(confirmDelete); confirmDelete = null"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
