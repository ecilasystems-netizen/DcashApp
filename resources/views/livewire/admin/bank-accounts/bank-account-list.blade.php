<div
    x-data="{
                    isBankModalOpen: false,
                    isWalletModalOpen: false,
                    isEditBankModalOpen: false,
                    isEditWalletModalOpen: false
                }"
    x-on:close-bank-modal.window="isBankModalOpen = false"
    x-on:close-wallet-modal.window="isWalletModalOpen = false"
    x-on:open-edit-bank-modal.window="isEditBankModalOpen = true"
    x-on:close-edit-bank-modal.window="isEditBankModalOpen = false"
    x-on:open-edit-wallet-modal.window="isEditWalletModalOpen = true"
    x-on:close-edit-wallet-modal.window="isEditWalletModalOpen = false"
>

    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Bank Accounts & Wallets</h1>
            </div>
        </header>
    </x-slot>

    <div class="p-6 space-y-8">
        <!-- Bank Accounts -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-white">Bank Accounts</h3>
                <button @click="isBankModalOpen = true"
                        class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Bank
                </button>
            </div>

            <!-- Bank Accounts Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-500/20 rounded-lg">
                            <i data-lucide="landmark" class="w-5 h-5 text-blue-400"></i>
                        </div>
                        <div class="text-gray-400 text-xs uppercase">Total Accounts</div>
                    </div>
                    <div class="text-white text-2xl font-bold">{{ $bankAccounts->count() }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-500/20 rounded-lg">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <div class="text-gray-400 text-xs uppercase">Active Accounts</div>
                    </div>
                    <div
                        class="text-white text-2xl font-bold">{{ $bankAccounts->where('is_active', true)->count() }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-purple-500/20 rounded-lg">
                            <i data-lucide="arrow-right-left" class="w-5 h-5 text-purple-400"></i>
                        </div>
                        <div class="text-gray-400 text-xs uppercase">Total Transactions</div>
                    </div>
                    <div class="text-white text-2xl font-bold">{{ $bankAccounts->sum('transactions_count') }}</div>
                </div>
            </div>

            <!-- Bank Accounts Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-4 py-2">Bank</th>
                        <th class="px-4 py-2">Account Details</th>
                        <th class="px-4 py-2">Account Type</th>
                        <th class="px-4 py-2">QR Code</th>
                        <th class="px-4 py-2">Currency</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Tab Name</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse($bankAccounts as $account)
                        <tr class="hover:bg-gray-700/30">
                            <td class="p-4 font-semibold text-white">{{ $account->bank_name }}</td>
                            <td class="p-4 text-gray-300">
                                <div class="font-mono">{{ $account->account_number }}</div>
                                <div>{{ $account->account_name }}</div>
                            </td>
                            <td class="p-4">
                                @if($account->account_type)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if(strtolower($account->account_type) === 'g-cash')
                                            bg-blue-500/20 text-blue-400
                                        @elseif(strtolower($account->account_type) === 'paymaya')
                                            bg-green-500/20 text-green-400
                                        @else
                                            bg-gray-500/20 text-gray-400
                                        @endif">
                                        {{ ucwords(str_replace('-', ' ', $account->account_type)) }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($account->bank_account_qr_code_url)
                                    <img src="{{ $account->bank_account_qr_code_url }}" alt="QR Code"
                                         class="w-12 h-12 object-cover rounded-md bg-white p-1">
                                @else
                                    <span class="text-gray-500 text-xs">N/A</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-300">{{ $account->currency->code }}</td>
                            <td class="p-4">
                                <button wire:click="toggleStatus({{ $account->id }})"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 {{ $account->is_active ? 'bg-green-500' : 'bg-gray-600' }}">
                                    <span
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $account->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                                <span
                                    class="ml-2 text-xs {{ $account->is_active ? 'text-green-400' : 'text-gray-400' }}">
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-300">{{ $account->tab_name ?? 'N/A' }}</td>
                            <td class="p-4 text-gray-300">{{ $account->position }}</td>
                            <td class="p-4 text-right">
                                <button wire:click="editBankAccount({{ $account->id }})"
                                        class="text-blue-400 hover:text-blue-300">Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-400">No bank accounts found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Crypto Wallets -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-white">Crypto Wallets</h3>
                <button @click="isWalletModalOpen = true"
                        class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Wallet
                </button>
            </div>


            <!-- Bank Accounts Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-500/20 rounded-lg">
                            <i data-lucide="building-bank" class="w-5 h-5 text-blue-400"></i>
                        </div>
                        <div class="text-gray-400 text-xs uppercase">Total Accounts</div>
                    </div>
                    <div class="text-white text-2xl font-bold">{{ $bankAccounts->count() }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-500/20 rounded-lg">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <div class="text-gray-400 text-xs uppercase">Active Accounts</div>
                    </div>
                    <div
                        class="text-white text-2xl font-bold">{{ $bankAccounts->where('is_active', true)->count() }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-purple-500/20 rounded-lg">
                            <i data-lucide="arrow-left-right" class="w-5 h-5 text-purple-400"></i>
                        </div>
                        <div class="text-gray-400 text-xs uppercase">Total Transactions</div>
                    </div>
                    <div class="text-white text-2xl font-bold">{{ $bankAccounts->sum('transactions_count') }}</div>
                </div>
            </div>


            <!-- Crypto Wallets Table - Update to include Edit button -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Network</th>
                        <th class="px-4 py-2">Address</th>
                        <th class="px-4 py-2">QR Code</th>
                        <th class="px-4 py-2">Currency</th>
                        <th class="px-4 py-2">Transactions</th>
                        <th class="px-4 py-2">Updated</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse($wallets as $wallet)
                        <tr wire:key="wallet-{{ $wallet->id }}">
                            <td class="px-4 py-3 font-semibold text-white">{{ $wallet->crypto_name }}</td>
                            <td class="px-4 py-3">{{ $wallet->crypto_network }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="font-mono">{{ Str::limit($wallet->crypto_wallet_address, 16) }}</span>
                            </td>
                            <td class="px-4 py-2">
                                @if($wallet->crypto_qr_code_url)
                                    <img src="{{ $wallet->crypto_qr_code_url }}" alt="QR Code"
                                         class="w-12 h-12 object-cover rounded-md bg-white p-1">
                                @else
                                    <span class="text-gray-500 text-xs">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $wallet->currency?->code }}</td>
                            <td class="px-4 py-3">{{ $wallet->transactions_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $wallet->updated_at?->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleStatus({{ $wallet->id }})"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 {{ $wallet->is_active ? 'bg-green-500' : 'bg-gray-600' }}">
                            <span
                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $wallet->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                                <span
                                    class="ml-2 text-xs {{ $wallet->is_active ? 'text-green-400' : 'text-gray-400' }}">
                            {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                        </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="editWallet({{ $wallet->id }})"
                                        class="text-blue-400 hover:text-blue-300">Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">No wallets yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Bank Modal -->
    <div x-show="isBankModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="isBankModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-lg p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Add New Bank Account</h3>
            <form wire:submit.prevent="storeBankAccount" class="space-y-4">
                <div>
                    <label for="new_bank_currency" class="block text-sm text-gray-400 mb-1">Currency</label>
                    <select id="new_bank_currency" wire:model.defer="newBank.currency_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Select Currency</option>
                        @foreach($currencies as $currency)
                            @if($currency->type == "fiat")
                                <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('newBank.currency_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="new_account_type" class="block text-sm text-gray-400 mb-1">Account Type</label>
                    <select id="new_account_type" wire:model.defer="newBank.account_type"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Select Account Type</option>
                        <option value="bank">Bank Account</option>
                        <option value="g-cash">G-Cash</option>
                        <option value="paymaya">PayMaya</option>
                    </select>
                    @error('newBank.account_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="new_bank_name" class="block text-sm text-gray-400 mb-1">Bank Name</label>
                    <input type="text" id="new_bank_name" wire:model.defer="newBank.bank_name"
                           placeholder="e.g. Bank of Phillipines"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newBank.bank_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="new_account_name" class="block text-sm text-gray-400 mb-1">Account Name</label>
                    <input type="text" id="new_account_name" wire:model.defer="newBank.account_name"
                           placeholder="e.g. John Doe"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newBank.account_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="new_account_number" class="block text-sm text-gray-400 mb-1">Account Number</label>
                    <input type="text" id="new_account_number" wire:model.defer="newBank.account_number"
                           placeholder="e.g. 1234 5678 9012"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newBank.account_number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">QR Code (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if(isset($newBankQr) && $newBankQr)
                            <img src="{{ $newBankQr->temporaryUrl() }}"
                                 class="w-16 h-16 object-cover rounded-md bg-white p-1">
                        @else
                            <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                            </div>
                        @endif
                        <input type="file" wire:model="newBankQr" accept="image/*"
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600">
                    </div>
                    @error('newBankQr') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="new_tab_name" class="block text-sm text-gray-400 mb-1">Tab Name</label>
                        <input type="text" id="new_tab_name" wire:model.defer="newBank.tab_name"
                               placeholder="e.g. Bank Account"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        @error('newBank.tab_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="new_position" class="block text-sm text-gray-400 mb-1">Position</label>
                        <input type="number" id="new_position" wire:model.defer="newBank.position"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        @error('newBank.position') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" @click="isBankModalOpen = false"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                    </button>
                    <button type="submit"
                            class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg">Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Bank Modal -->
    <div x-show="isEditBankModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="isEditBankModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-lg p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Edit Bank Account</h3>
            <form wire:submit.prevent="updateBankAccount" class="space-y-4">
                <div>
                    <label for="edit_bank_currency" class="block text-sm text-gray-400 mb-1">Currency</label>
                    <select id="edit_bank_currency" wire:model.defer="editingBank.currency_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Select Currency</option>
                        @foreach($currencies as $currency)

                            @if($currency->type == "fiat")
                                <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('editingBank.currency_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="edit_account_type" class="block text-sm text-gray-400 mb-1">Account Type</label>
                    <select id="edit_account_type" wire:model.defer="editingBank.account_type"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Select Account Type</option>
                        <option value="bank" {{ ($editingBank['account_type'] ?? '') === 'bank' ? 'selected' : '' }}>
                            Bank Account
                        </option>
                        <option
                            value="g-cash" {{ ($editingBank['account_type'] ?? '') === 'g-cash' ? 'selected' : '' }}>
                            G-Cash
                        </option>
                        <option
                            value="paymaya" {{ ($editingBank['account_type'] ?? '') === 'paymaya' ? 'selected' : '' }}>
                            PayMaya
                        </option>
                    </select>
                    @error('editingBank.account_type') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="edit_bank_name" class="block text-sm text-gray-400 mb-1">Bank Name</label>
                    <input type="text" id="edit_bank_name" wire:model.defer="editingBank.bank_name"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('editingBank.bank_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="edit_account_name" class="block text-sm text-gray-400 mb-1">Account Name</label>
                    <input type="text" id="edit_account_name" wire:model.defer="editingBank.account_name"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('editingBank.account_name') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="edit_account_number" class="block text-sm text-gray-400 mb-1">Account Number</label>
                    <input type="text" id="edit_account_number" wire:model.defer="editingBank.account_number"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('editingBank.account_number') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">QR Code (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if($editingBankQr)
                            <img src="{{ $editingBankQr->temporaryUrl() }}"
                                 class="w-16 h-16 object-cover rounded-md bg-white p-1">
                        @elseif($editingBankId)
                            @php
                                $currentBank = \App\Models\CompanyBankAccount::find($editingBankId);
                            @endphp
                            @if($currentBank && $currentBank->bank_account_qr_code_url)
                                <img src="{{ $currentBank->bank_account_qr_code_url }}"
                                     class="w-16 h-16 object-cover rounded-md bg-white p-1">
                            @else
                                <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                    <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                            </div>
                        @endif

                        <input type="file" wire:model="editingBankQr" accept="image/*"
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600">
                    </div>
                    @error('editingBankQr') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_tab_name" class="block text-sm text-gray-400 mb-1">Tab Name</label>
                        <input type="text" id="edit_tab_name" wire:model.defer="editingBank.tab_name"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        @error('editingBank.tab_name') <span
                            class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="edit_position" class="block text-sm text-gray-400 mb-1">Position</label>
                        <input type="number" id="edit_position" wire:model.defer="editingBank.position"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        @error('editingBank.position') <span
                            class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="cancelEdit"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                    </button>
                    <button type="submit"
                            class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg">Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Crypto Wallet Modal -->
    <div x-show="isWalletModalOpen" x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay" style="display: none;">
        <div @click.away="isWalletModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Add Crypto Wallet</h3>
            <form wire:submit.prevent="storeWallet" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Currency</label>
                    <select wire:model.defer="newWallet.currency_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Select Currency</option>
                        @foreach($currencies as $currency)
                            @if($currency->type == "crypto")
                                <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('newWallet.currency_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wallet Name</label>
                    <input type="text" wire:model.defer="newWallet.crypto_name" placeholder="e.g. My Bitcoin Wallet"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newWallet.crypto_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Network</label>
                    <input type="text" wire:model.defer="newWallet.crypto_network" placeholder="e.g. Bitcoin, Ethereum"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newWallet.crypto_network') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wallet Address</label>
                    <input type="text" wire:model.defer="newWallet.crypto_wallet_address"
                           placeholder="e.g. 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white font-mono">
                    @error('newWallet.crypto_wallet_address') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">QR Code (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if($newWallet['qr'])
                            <img src="{{ $newWallet['qr']->temporaryUrl() }}"
                                 class="w-16 h-16 object-cover rounded-md bg-white p-1">
                        @else
                            <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                            </div>
                        @endif
                        <input type="file" wire:model="newWallet.qr" accept="image/*"
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600">
                    </div>
                    @error('newWallet.qr') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Set as Active</span>
                    <div class="relative">
                        <input id="newWalletActive" type="checkbox" wire:model="newWallet.is_active"
                               class="sr-only peer" aria-label="Set wallet as active">
                        <div
                            class="w-12 h-6 bg-gray-600 rounded-full peer-checked:bg-green-500 transition-colors"></div>
                        <div
                            class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transform transition-transform peer-checked:translate-x-6"></div>
                    </div>
                </div>
                <div class="pt-4 flex justify-end gap-4">
                    <button type="button" @click="isWalletModalOpen = false"
                            class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                    </button>
                    <button type="submit" class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Save Wallet</span>
                        <span wire:loading class="animate-pulse">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Wallet Modal -->
    <div x-show="isEditWalletModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="isEditWalletModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-lg p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Edit Crypto Wallet</h3>
            <form wire:submit.prevent="updateWallet" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Currency</label>
                    <select wire:model.defer="editingWallet.currency_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Select currency</option>
                        @foreach($currencies as $currency)
                            @if($currency->type == "crypto")
                                <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('editingWallet.currency_id') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wallet Name</label>
                    <input type="text" wire:model.defer="editingWallet.crypto_name"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('editingWallet.crypto_name') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Network</label>
                    <input type="text" wire:model.defer="editingWallet.crypto_network"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('editingWallet.crypto_network') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wallet Address</label>
                    <input type="text" wire:model.defer="editingWallet.crypto_wallet_address"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white font-mono">
                    @error('editingWallet.crypto_wallet_address') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">QR Code (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if($editingWalletQr)
                            <img src="{{ $editingWalletQr->temporaryUrl() }}"
                                 class="w-16 h-16 object-cover rounded-md bg-white p-1">
                        @elseif($editingWalletId)
                            @php
                                $currentWallet = \App\Models\CompanyBankAccount::find($editingWalletId);
                            @endphp
                            @if($currentWallet && $currentWallet->crypto_qr_code_url)
                                <img src="{{ $currentWallet->crypto_qr_code_url }}"
                                     class="w-16 h-16 object-cover rounded-md bg-white p-1">
                            @else
                                <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                    <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                            </div>
                        @endif

                        <input type="file" wire:model="editingWalletQr" accept="image/*"
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600">
                    </div>
                    @error('editingWalletQr') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Set as Active</span>
                    <div class="relative">
                        <input id="editWalletActive" type="checkbox" wire:model.defer="editingWallet.is_active"
                               class="sr-only peer" aria-label="Set wallet as active">
                        <div
                            class="w-12 h-6 bg-gray-600 rounded-full peer-checked:bg-green-500 transition-colors"></div>
                        <div
                            class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transform transition-transform peer-checked:translate-x-6"></div>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="cancelWalletEdit"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                    </button>
                    <button type="submit"
                            class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg">Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
