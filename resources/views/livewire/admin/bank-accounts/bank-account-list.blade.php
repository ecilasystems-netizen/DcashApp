<div
    x-data="{
                                isBankModalOpen: false,
                                isWalletModalOpen: false,
                            }"
    x-on:close-bank-modal.window="isBankModalOpen = false"
    x-on:close-wallet-modal.window="isWalletModalOpen = false"
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


            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-4 py-2">Account Name</th>
                        <th class="px-4 py-2">Bank</th>
                        <th class="px-4 py-2">Currency</th>
                        <th class="px-4 py-2">Transactions</th>
                        <th class="px-4 py-2">Updated</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse($bankAccounts as $account)
                        <tr wire:key="bank-{{ $account->id }}">
                            <td class="px-4 py-3 font-semibold text-white">{{ $account->account_name }}</td>
                            <td class="px-4 py-3">
                                {{ $account->bank_name }}
                                <div class="text-gray-400 text-xs">{{ $account->account_number }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $account->currency?->code }}</td>
                            <td class="px-4 py-3">{{ $account->transactions_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $account->updated_at?->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <label class="switch">
                                    <input type="checkbox"
                                           @change="$wire.toggleStatus('bank', {{ $account->id }})"
                                        {{ $account->is_active ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No bank accounts yet.</td>
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


            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Network</th>
                        <th class="px-4 py-2">Address</th>
                        <th class="px-4 py-2">Currency</th>
                        <th class="px-4 py-2">Transactions</th>
                        <th class="px-4 py-2">Updated</th>
                        <th class="px-4 py-2">Status</th>
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
                            <td class="px-4 py-3">{{ $wallet->currency?->code }}</td>
                            <td class="px-4 py-3">{{ $wallet->transactions_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $wallet->updated_at?->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <label class="switch">
                                    <input type="checkbox"
                                           @change="$wire.toggleStatus('wallet', {{ $wallet->id }})"
                                        {{ $wallet->is_active ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">No wallets yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Bank Account Modal -->
    <div x-show="isBankModalOpen" x-transition class="fixed inset-0 z-30 flex items-center justify-center modal-overlay"
         style="display: none;">
        <div @click.away="isBankModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Add Bank Account</h3>
            <form wire:submit.prevent="storeBankAccount" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Currency</label>
                    <select wire:model="newBank.currency_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="" selected disabled>Select currency</option>
                        @foreach($currencies as $currency)

                            @if($currency->type == "fiat")
                                <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('newBank.currency_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Bank Name</label>
                    <input type="text" wire:model.defer="newBank.bank_name"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newBank.bank_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Account Name</label>
                    <input type="text" wire:model.defer="newBank.account_name"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newBank.account_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Account Number</label>
                    <input type="text" wire:model.defer="newBank.account_number"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newBank.account_number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">QR Code (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if($newBank['qr'])
                            <img src="{{ $newBank['qr']->temporaryUrl() }}"
                                 class="w-16 h-16 object-cover rounded-md bg-white p-1">
                        @else
                            <div class="w-16 h-16 bg-gray-700 rounded-md flex items-center justify-center">
                                <i data-lucide="qr-code" class="w-8 h-8 text-gray-500"></i>
                            </div>
                        @endif
                        <input type="file" wire:model="newBank.qr" accept="image/*"
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600">
                    </div>
                    @error('newBank.qr') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Set as Active</span>
                    <label class="switch">
                        <input type="checkbox" wire:model="newBank.is_active">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="pt-4 flex justify-end gap-4">
                    <button type="button" @click="isBankModalOpen = false"
                            class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">Cancel
                    </button>
                    <button type="submit" class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Save Account</span>
                        <span wire:loading class="animate-pulse">Saving...</span>
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
                    <select wire:model="newWallet.currency_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        <option value="" selected disabled>Select currency</option>
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
                    <input type="text" wire:model.defer="newWallet.crypto_name"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newWallet.crypto_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Network</label>
                    <input type="text" wire:model.defer="newWallet.crypto_network"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    @error('newWallet.crypto_network') <span
                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wallet Address</label>
                    <input type="text" wire:model.defer="newWallet.crypto_wallet_address"
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
                    <label class="switch">
                        <input type="checkbox" wire:model="newWallet.is_active">
                        <span class="slider"></span>
                    </label>
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

    @push('styles')
        <style>
            .brand-gradient {
                background: linear-gradient(135deg, #E1B362 0%, #D4A55A 100%);
            }

            .switch {
                position: relative;
                display: inline-block;
                width: 40px;
                height: 24px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: #4b5563;
                transition: .4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                left: 4px;
                bottom: 4px;
                background: #fff;
                transition: .4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background: #10B981;
            }

            input:checked + .slider:before {
                transform: translateX(16px);
            }

            .modal-overlay {
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(1px);
            }
        </style>
    @endpush
</div>
