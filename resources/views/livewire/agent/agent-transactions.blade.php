<div x-data="{
                                            isModalOpen: false,
                                            proofImage: '',
                                            proofUser: '',
                                            proofId: '',
                                            confirmApprove: null,
                                            confirmReject: null,
                                            confirmAccept: null,
                                            rejectionReason: '',
                                            otherRejectionReason: ''
                                        }">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4">


                <h1 class="text-2xl font-bold text-white">
                    {{ $viewMode === 'available_orders' ? 'Available Orders' : 'My Transactions' }}
                </h1>
            </div>
        </header>
    </x-slot>

    <!-- Loading Overlay -->
    <div wire:loading.delay
         wire:target="statusFilter,search,dateFilter,perPage,switchToMyTransactions,switchToAvailableOrders,acceptOrder"
         class="fixed inset-0 bg-gray-800/80 backdrop-blur-sm flex items-center justify-center z-50 rounded-lg">
        <div class="flex flex-col items-center gap-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#E1B362]"></div>
            <p class="text-sm text-gray-400">Loading transactions...</p>
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

        <!-- Navigation Tabs -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex space-x-1 bg-gray-700 rounded-lg p-1">
                <button wire:click="switchToMyTransactions"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'my_transactions' ? 'bg-[#E1B362] text-white' : 'text-gray-300 hover:text-white' }}">
                    My Transactions
                </button>
                <button wire:click="switchToAvailableOrders"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'available_orders' ? 'bg-[#E1B362] text-white' : 'text-gray-300 hover:text-white' }}">
                    Available Orders
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-600 rounded-full">
                        {{ number_format($this->stats['available'] ?? 0) }}
                    </span>
                </button>
            </div>


        </div>

        <!-- Statistics -->
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            @if($viewMode === 'available_orders')
                <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                    <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Available Orders</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($this->stats['available']) }}</p>
                    </div>
                </div>
                <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                    <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Unassigned</p>
                        <p class="text-2xl font-bold text-yellow-400">{{ number_format($this->stats['total_unassigned']) }}</p>
                    </div>
                </div>
            @else
                <div
                    class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64 cursor-pointer hover:bg-gray-700 transition-colors"
                    wire:click="$set('statusFilter', '')">
                    <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                        <i data-lucide="list-checks" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">My Total Transactions</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($this->stats['total']) }}</p>
                    </div>
                </div>
                <div
                    class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64 cursor-pointer hover:bg-gray-700 transition-colors"
                    wire:click="$set('statusFilter', 'pending_confirmation')">
                    <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
                        <i data-lucide="loader-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Pending</p>
                        <p class="text-2xl font-bold text-yellow-400">{{ number_format($this->stats['pending']) }}</p>
                    </div>
                </div>
                <div
                    class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64 cursor-pointer hover:bg-gray-700 transition-colors"
                    wire:click="$set('statusFilter', 'completed')">
                    <div class="p-3 bg-green-500/20 text-green-400 rounded-full mr-4">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Completed</p>
                        <p class="text-2xl font-bold text-green-400">{{ number_format($this->stats['completed']) }}</p>
                    </div>
                </div>
                <div
                    class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64 cursor-pointer hover:bg-gray-700 transition-colors"
                    wire:click="$set('statusFilter', 'failed')">
                    <div class="p-3 bg-red-500/20 text-red-400 rounded-full mr-4">
                        <i data-lucide="x-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Failed / Rejected</p>
                        <p class="text-2xl font-bold text-red-400">{{ number_format($this->stats['failed']) }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            @if($viewMode === 'my_transactions')
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
            @endif

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
                    @if($viewMode === 'my_transactions')
                        <th class="px-6 py-3">STATUS</th>
                    @endif
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
                                             @click="isModalOpen = true; proofImage = '{{ asset(Storage::url($image)) }}'; proofUser = '{{ $transaction->user->fname }} {{ $transaction->user->lname }}'; proofId = '{{ $transaction->reference }}'"/>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs">#{{ $transaction->reference }}</div>
                            <div
                                class="text-xs text-gray-400">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</div>
                            <div class="text-sm">{{ $transaction->fromCurrency->code }}
                                - {{ $transaction->toCurrency->code }}</div>
                            <div class="text-xs text-gray-400">Rate: {{ number_format($transaction->rate, 4) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                Send: {{ $transaction->fromCurrency->code }} {{ number_format($transaction->amount_from, 2) }}
                                <div x-data="{ copied: false }" class="inline-flex">
                                    <button
                                        @click="
                                                                            navigator.clipboard.writeText(parseFloat('{{ $transaction->amount_from }}').toFixed(2));
                                                                            copied = true;
                                                                            setTimeout(() => copied = false, 2000);
                                                                        "
                                        class="text-gray-400 hover:text-white transition-colors p-1 rounded"
                                        :title="copied ? 'Copied!' : 'Copy amount'"
                                    >
                                        <i x-show="!copied" data-lucide="copy" class="w-3 h-3"></i>
                                        <i x-show="copied" data-lucide="check" class="w-3 h-3 text-green-400"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                Receive: {{ $transaction->toCurrency->code }} {{ number_format($transaction->amount_to, 2) }}
                                <div x-data="{ copied: false }" class="inline-flex">
                                    <button
                                        @click="
                                                                            navigator.clipboard.writeText(parseFloat('{{ $transaction->amount_to }}').toFixed(2));
                                                                            copied = true;
                                                                            setTimeout(() => copied = false, 2000);
                                                                        "
                                        class="text-gray-400 hover:text-white transition-colors p-1 rounded"
                                        :title="copied ? 'Copied!' : 'Copy amount'"
                                    >
                                        <i x-show="!copied" data-lucide="copy" class="w-3 h-3"></i>
                                        <i x-show="copied" data-lucide="check" class="w-3 h-3 text-green-400"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-xs  text-white">
                                @if(!empty($transaction['note']) && (isset($transaction['note']['receiving_bank']) || isset($transaction['note']['payment_bank'])))
                                    <span
                                        class="bg-[#E1B362] border border-[#E1B362] text-black">Method: DCASH Wallet</span>

                                @else
                                    Method: {{ getBankname($transaction->company_bank_account_id) }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                Name: {{ $transaction->user->fname }} {{ $transaction->user->lname }}</div>
                            <div class="text-xs text-gray-400">Email: {{ $transaction->user->email }}</div>
                            <div class="mt-2">
                                <div class="text-sm">Receiver Details</div>

                                {{-- check if crypto or fiat --}}
                                @if($transaction->recipient_wallet_address != null )
                                    <div class="text-sm">Network: {{ $transaction->recipient_network }}</div>
                                    <div class="text-xs text-gray-400 flex items-center gap-2">
                                        Wallet: {{ $transaction->recipient_wallet_address }}
                                        <div x-data="{ copied: false }" class="inline-flex">
                                            <button
                                                @click="
                                               navigator.clipboard.writeText('{{ $transaction->recipient_wallet_address }}');
                                               copied = true;
                                               setTimeout(() => copied = false, 2000);
                                           "
                                                class="text-gray-400 hover:text-white transition-colors p-1 rounded"
                                                :title="copied ? 'Copied!' : 'Copy Wallet Address'"
                                            >
                                                <i x-show="!copied" data-lucide="copy" class="w-3 h-3"></i>
                                                <i x-show="copied" data-lucide="check"
                                                   class="w-3 h-3 text-green-400"></i>
                                            </button>
                                        </div>
                                    </div>
                                @elseif(!empty($transaction['note']) && isset($transaction['note']['receiving_bank']))
                                    <div class="text-sm">Name: *******</div>
                                    <div class="text-sm">Bank: ***********</div>
                                    <div class="text-xs text-gray-400 flex items-center gap-2">
                                        Account: ********

                                    </div>
                                @else

                                    <div class="text-sm">Name: {{ $transaction->recipient_account_name }}</div>
                                    <div class="text-sm">Bank: {{ $transaction->recipient_bank_name }}</div>
                                    <div class="text-xs text-gray-400 flex items-center gap-2">
                                        Account: {{ $transaction->recipient_account_number }}
                                        <div x-data="{ copied: false }" class="inline-flex">
                                            <button
                                                @click="
                                               navigator.clipboard.writeText('{{ $transaction->recipient_account_number }}');
                                               copied = true;
                                               setTimeout(() => copied = false, 2000);
                                           "
                                                class="text-gray-400 hover:text-white transition-colors p-1 rounded"
                                                :title="copied ? 'Copied!' : 'Copy account number'"
                                            >
                                                <i x-show="!copied" data-lucide="copy" class="w-3 h-3"></i>
                                                <i x-show="copied" data-lucide="check"
                                                   class="w-3 h-3 text-green-400"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($transaction['note']) && (isset($transaction['note']['receiving_bank']) || isset($transaction['note']['payment_bank'])))
                                    <div class="mb-2 p-2  bg-[#E1B362] border border-[#E1B362] rounded-lg text-xs">
                                        @foreach($transaction->note as $key => $value)
                                            <div class="text-gray-300 flex items-center gap-2">
                                                <i data-lucide="arrow-down-circle"
                                                   class="w-4 h-4 text-green-400 flex-shrink-0"></i>
                                                <span class="font-semibold text-black">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </td>
                        @if($viewMode === 'my_transactions')
                            <td class="px-6 py-4">
                                @if(isset($transaction['note']['rejected']))
                                    <div
                                        class="mb-2 p-2 bg-gray-700 border border-gray-600 rounded-lg text-xs text-gray-300">
                                        {{--                                        Note: {{ $transaction->note }}--}}
                                    </div>
                                @endif
                                <span class="status-pill status-{{ $transaction->status }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                                                </span>

                                {{-- Transfer Bank Information --}}
                                @if(!empty($transaction['note']) && isset($transaction['note']['transfer_bank']))
                                    <div
                                        class="mt-2 p-3 bg-gradient-to-r from-[#E1B362] to-[#D4A555] border border-[#E1B362] rounded-lg shadow-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <i data-lucide="building-2" class="w-4 h-4 text-gray-800"></i>
                                            <span class="text-xs font-medium text-gray-800 uppercase tracking-wide">Transfer Bank</span>
                                        </div>
                                        @foreach($transaction->note as $key => $value)
                                            @if($key === 'transfer_bank')
                                                <div class="flex items-start gap-2">
                                                    <i data-lucide="bank"
                                                       class="w-4 h-4 text-gray-700 mt-0.5 flex-shrink-0"></i>
                                                    <div>

                                                        <span
                                                            class="font-semibold text-gray-900 text-sm">{{ $value }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @endif
                        <td class="px-6 py-4 text-right">
                            @if($viewMode === 'available_orders')
                                <button @click="confirmAccept = {{ $transaction->id }}"
                                        class="bg-[#E1B362] hover:bg-[#E1B362]/80 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 ml-auto">
                                    <i data-lucide="hand-heart" class="w-4 h-4"></i>
                                    Accept Order
                                </button>
                            @elseif($transaction->status === 'pending_confirmation')
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700 transition-colors">
                                        <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false"
                                         class="dropdown-menu absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-xl z-50"
                                         x-transition style="display: none;">
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
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">No actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $viewMode === 'my_transactions' ? '7' : '6' }}"
                            class="px-6 py-8 text-center text-gray-400">
                            @if($viewMode === 'available_orders')
                                No available orders at the moment
                            @else
                                No transactions assigned to you yet
                            @endif
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

    <!-- Accept Order Confirmation Modal -->
    <div x-show="confirmAccept" x-cloak x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Accept Order</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to accept this order? This will assign the transaction
                to you for processing.</p>
            <div class="flex justify-end gap-4">
                <button @click="confirmAccept = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Cancel
                </button>
                <button @click="$wire.acceptOrder(confirmAccept); confirmAccept = null"
                        class="bg-[#E1B362] hover:bg-[#E1B362]/80 text-white font-semibold py-2 px-4 rounded-lg">
                    Accept Order
                </button>
            </div>
        </div>
    </div>

    <!-- Existing modals (Proof Modal, Approve Modal, Reject Modal) remain the same -->
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
                <img :src="proofImage" alt="Proof of Payment" class="w-full h-auto object-contain max-h-[60vh]"/>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div x-show="confirmApprove" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);"
         x-data="{
             showBankSelect: false,
             selectedBank: '',
             init() {
                 this.$watch('confirmApprove', value => {
                     if (value) {
                         const transactions = @js($this->transactions->keyBy('id'));
                         this.showBankSelect = transactions[value]?.to_currency?.code === 'NGN';
                         this.selectedBank = '';
                     }
                 });
             }
         }">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Confirm Approval</h3>
            <p class="text-gray-300 mb-6">Are you sure you want to approve this transaction?</p>

            <!-- Bank Selection for NGN transactions -->
            <div x-show="showBankSelect" class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Select Transfer Bank</label>
                <select x-model="selectedBank"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    <option value="" disabled>Select a bank...</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank }}">{{ $bank }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-4">
                <button @click="confirmApprove = null; selectedBank = ''"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Cancel
                </button>
                <button @click="
                    if (showBankSelect && !selectedBank) {
                        alert('Please select a transfer bank');
                        return;
                    }
                    $wire.approveTransaction(confirmApprove, selectedBank || null);
                    confirmApprove = null;
                    selectedBank = '';
                "
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Approve
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div x-show="confirmReject" x-cloak x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4">
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
                <button @click="() => {
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
</div>
