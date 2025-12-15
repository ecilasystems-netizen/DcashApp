<div x-data="{
        transactionsModalOpen: @entangle('showTransactionsModal')
    }">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Users with Virtual Accounts</h1>
            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if(session()->has('message'))
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-60">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                    <i data-lucide="credit-card" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Users with Virtual Accounts</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($users->total()) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative md:flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search users..."
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>

            <select
                wire:model.live="perPage"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>

        <!-- Users Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Date Joined</th>
                        <th class="px-6 py-3">Virtual Account</th>
                        <th class="px-6 py-3">Total Transactions</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-700/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-semibold">
                                        {{ substr($user->fname ?? '', 0, 1) }}{{ substr($user->lname ?? '', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white">
                                            {{ $user->fname ?? '' }} {{ $user->lname ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-white font-medium">{{ $user->virtualBankAccount->account_number }}</p>
                                    <p class="text-gray-400">{{ $user->virtualBankAccount->bank_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->virtualBankAccount->account_name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400">
                                    {{ number_format($user->wallet_transactions_count) }} transactions
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    wire:click="showUserTransactions({{ $user->id }})"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View Transactions
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                No users with virtual accounts found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="p-4 border-t border-gray-700 flex justify-between items-center text-sm">
                <p class="text-gray-400">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                    users
                </p>
                <div class="flex items-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Modal -->
    <div x-show="transactionsModalOpen" x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="$wire.closeTransactionsModal()"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] border border-gray-700 flex flex-col">

            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Wallet Transactions</h3>
                <button @click="$wire.closeTransactionsModal()" class="text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-6 max-h-96">
                @if(!empty($selectedUserTransactions))
                    <div class="space-y-4">
                        @foreach($selectedUserTransactions as $transaction)
                            <div class="bg-gray-700/50 rounded-lg p-4 border border-gray-600">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div
                                                class="p-2 rounded-full {{ $transaction['type'] === 'credit' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                @if($transaction['type'] === 'credit')
                                                    <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                                                @else
                                                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ ucfirst($transaction['type']) }}</p>
                                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>

                                        @if($transaction['description'])
                                            <p class="text-sm text-gray-300 mb-2">{{ $transaction['description'] }}</p>
                                        @endif

                                        @if($transaction['reference'])
                                            <p class="text-xs text-gray-400">Ref: {{ $transaction['reference'] }}</p>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold {{ $transaction['type'] === 'credit' ? 'text-green-400' : 'text-red-400' }}">
                                            {{ $transaction['type'] === 'credit' ? '+' : '-' }}
                                            ₦{{ number_format($transaction['amount'], 2) }}
                                        </p>
                                        @if($transaction['status'])
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $transaction['status'] === 'completed' ? 'bg-green-500/20 text-green-400' : '' }}
                                                {{ $transaction['status'] === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                                {{ $transaction['status'] === 'failed' ? 'bg-red-500/20 text-red-400' : '' }}">
                                                {{ ucfirst($transaction['status']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="credit-card" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-400">No transactions found for this user</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-700 flex justify-end">
                <button @click="$wire.closeTransactionsModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
