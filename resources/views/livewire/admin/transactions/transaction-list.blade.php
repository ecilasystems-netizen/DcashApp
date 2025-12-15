<div x-data="{
            isModalOpen: false,
            proofImage: '',
            proofUser: '',
            proofId: '',
            confirmDelete: null,
            confirmApprove: null,
            confirmReject: null,
            confirmAssign: null,
            selectedAgent: '',
            ApproveAndPay: null,
            rejectionReason: '',
            otherRejectionReason: '',
            exportDropdown: false
        }">
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Transaction Management</h1>

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


        <!-- SafeHaven Balance Display -->
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
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            <div
                class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64 cursor-pointer hover:bg-gray-700 transition-colors"
                wire:click="$set('statusFilter', '')">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                    <i data-lucide="list-checks" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Transactions</p>
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
        </div>


        <!-- Currency Transaction Totals -->
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            @forelse($this->currencyStats as $currencyCode => $stats)
                <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-80">
                    <div class="p-3 bg-purple-500/20 text-purple-400 rounded-full mr-4">
                        <i data-lucide="coins" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ $currencyCode }} Statistics</p>
                        <div>
                            <div>
                                <p class="text-sm text-red-400">
                                    OUT: {{ number_format($stats['total_received'], 2) }}
                                    ({{ $stats['currency']->symbol }})</p>
                            </div>
                            <div>
                                <p class="text-sm text-green-400">
                                    IN: {{ number_format($stats['total_sent'], 2) }}
                                    ({{ $stats['currency']->symbol }})</p>
                            </div>
                        </div>
                        <p class="text-xs text-purple-400">{{ $stats['currency']->name }}</p>
                    </div>
                </div>
            @empty
                <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                    <div class="p-3 bg-gray-500/20 text-gray-400 rounded-full mr-4">
                        <i data-lucide="coins" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">No completed transactions</p>
                        <p class="text-2xl font-bold text-gray-400">0.00</p>
                    </div>
                </div>
            @endforelse
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

            <div class="flex gap-2 flex-1">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by ID or user..."
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                </div>

                <button wire:click="exportExcel"
                        class="bg-[#E1B362] hover:bg-[#E1B362]/80 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export to excel
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg">

            <!-- Loading Overlay -->
            <div wire:loading.delay wire:target="statusFilter,search,dateFilter,perPage"
                 class="fixed inset-0 bg-gray-800/80 backdrop-blur-sm flex items-center justify-center z-50 rounded-lg">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#E1B362]"></div>
                    <p class="text-sm text-gray-400">Loading transactions...</p>
                </div>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-6 py-3">S/N</th>
                    <th class="px-6 py-3">POP</th>
                    <th class="px-6 py-3">ORDER</th>
                    <th class="px-6 py-3">AMOUNT</th>
                    <th class="px-6 py-3">DETAILS</th>
                    <th class="px-6 py-3">ASSIGNED AGENT</th>
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
                                    <div class="mb-2 p-2 bg-[#E1B362] border border-[#E1B362] rounded-lg text-xs">
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
                        <td class="px-6 py-4">
                            @if($transaction->agent)
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-blue-500/20 text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold">
                                        {{ substr($transaction->agent->fname, 0, 1) }}{{ substr($transaction->agent->lname, 0, 1) }}
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm text-white">{{ $transaction->agent->fname }} {{ $transaction->agent->lname }}</div>
                                        <div class="text-xs text-gray-400">{{ $transaction->agent->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-gray-600 text-gray-400 rounded-full flex items-center justify-center">
                                        <i data-lucide="user-x" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-sm text-gray-400">Not assigned</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">


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
                                            @if(!$transaction->agent_id)
                                                <button @click="confirmAssign = {{ $transaction->id }}; open = false"
                                                        class="flex items-center gap-3 px-4 py-2 text-sm text-blue-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                                    Assign to Agent
                                                </button>
                                                <hr class="border-gray-600 my-1">
                                            @endif
                                            <button @click="confirmApprove = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-yellow-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Approve
                                            </button>

                                            @if($transaction->to_currency_id === 1)

                                                <button @click="ApproveAndPay = {{ $transaction->id }}; open = false"
                                                        class="flex items-center gap-3 px-4 py-2 text-sm text-green-400 hover:bg-gray-600 w-full text-left transition-colors">
                                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                    Approve & Pay
                                                </button>
                                            @endif

                                            <button @click="confirmReject = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-950 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                Reject
                                            </button>
                                            <hr class="border-gray-600 my-1">
                                            <button @click="confirmDelete = {{ $transaction->id }}; open = false"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-gray-600 w-full text-left transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                Delete
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

    <!-- Assign to Agent Modal -->
    <div x-show="confirmAssign" x-cloak x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Assign Transaction to Agent</h3>
            <p class="text-gray-300 mb-6">Select an agent to assign this transaction to:</p>
            <div class="space-y-4">
                <select x-model="selectedAgent"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    <option value="" disabled>Select an agent...</option>
                    @foreach($this->agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->fname }} {{ $agent->lname }} ({{ $agent->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button @click="confirmAssign = null; selectedAgent = ''"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Cancel
                </button>
                <button
                    @click="$wire.assignToAgent(confirmAssign, selectedAgent); confirmAssign = null; selectedAgent = ''"
                    :disabled="!selectedAgent"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg disabled:bg-blue-800 disabled:cursor-not-allowed">
                    Assign
                </button>
            </div>
        </div>
    </div>

    <!-- Approve and Pay Modal -->
    <div x-show="ApproveAndPay" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);"
         x-data="{
             transactionDetails: null,
             init() {
                 this.$watch('ApproveAndPay', value => {
                     if (value) {
                         const transactions = @js($this->transactions->keyBy('id'));
                         this.transactionDetails = transactions[value];
                     }
                 });
             }
         }">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-400"></i>
                Confirm Bank Transfer
            </h3>

            <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-4 mb-4">
                <p class="text-yellow-400 text-sm font-medium">
                    You are about to initiate a bank transfer. Please verify the details carefully.
                </p>
            </div>

            <!-- Transaction Details -->
            <template x-if="transactionDetails">
                <div class="space-y-4 mb-6">
                    <!-- Amount -->
                    <div class="bg-gray-700/50 rounded-lg p-4">
                        <div class="text-xs text-gray-400 mb-1">Transfer Amount</div>
                        <div class="text-2xl font-bold text-[#E1B362]">
                            <span x-text="transactionDetails.to_currency?.code"></span>
                            <span
                                x-text="parseFloat(transactionDetails.amount_to).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                        </div>
                    </div>

                    <!-- Recipient Details -->
                    <div class="space-y-2">
                        <div class="text-sm font-medium text-white">Recipient Details</div>
                        <div class="bg-gray-700/50 rounded-lg p-3 space-y-2">
                            <div class="flex items-start gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-400">Account Name</div>
                                    <div class="text-sm text-white"
                                         x-text="transactionDetails.recipient_account_name"></div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <i data-lucide="hash" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-400">Account Number</div>
                                    <div class="text-sm text-white font-mono"
                                         x-text="transactionDetails.recipient_account_number"></div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <i data-lucide="building-2" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-400">Bank Name</div>
                                    <div class="text-sm text-white"
                                         x-text="transactionDetails.recipient_bank_name"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Reference -->
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i data-lucide="file-text" class="w-3 h-3"></i>
                        <span>Reference: <span class="text-white font-mono"
                                               x-text="transactionDetails.reference"></span></span>
                    </div>
                </div>
            </template>

            <div class="flex justify-end gap-4">
                <button @click="ApproveAndPay = null"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <button @click="
                    $wire.approveAndPay(ApproveAndPay);
                    ApproveAndPay = null;
                "
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Confirm Transfer
                </button>
            </div>
        </div>
    </div>
</div>
