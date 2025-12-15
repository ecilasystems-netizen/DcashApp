<div>
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Dcoin Exchange Rates</h1>
                    <p class="text-gray-400 text-sm">Manage exchange rates for supported currencies</p>
                </div>
                <button wire:click="updateRates"
                        class="bg-[#E1B362] hover:bg-[#E1B362]/80 text-white font-semibold py-2 px-6 rounded-lg flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save All Changes
                </button>
            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-600/20 border border-green-600 rounded-lg">
                <p class="text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <select wire:model.live="typeFilter"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="">All Types</option>
                <option value="fiat">Fiat Currencies</option>
                <option value="crypto">Cryptocurrencies</option>
            </select>

            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search currencies..."
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>

            <button wire:click="resetFilters"
                    class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                Reset
            </button>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500/20 text-blue-400 rounded-lg">
                        <i data-lucide="coins" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Currencies</p>
                        <p class="text-xl font-bold text-white">{{ count($currencies) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-500/20 text-green-400 rounded-lg">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Active</p>
                        <p class="text-xl font-bold text-green-400">
                            {{ collect($currencies)->where('is_active', true)->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-500/20 text-purple-400 rounded-lg">
                        <i data-lucide="banknote" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Fiat</p>
                        <p class="text-xl font-bold text-purple-400">
                            {{ collect($currencies)->where('type', 'fiat')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-500/20 text-orange-400 rounded-lg">
                        <i data-lucide="bitcoin" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Crypto</p>
                        <p class="text-xl font-bold text-orange-400">
                            {{ collect($currencies)->where('type', 'crypto')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currencies Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white">Exchange Rates Configuration</h3>
                <p class="text-sm text-gray-400">Set how many units of each currency equal 1 Dcoin</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-6 py-3">Currency</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Exchange Rate</th>
                        <th class="px-6 py-3">Min Redemption</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse($currencies as $index => $currency)
                        <tr class="hover:bg-gray-700/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-semibold">
                                        {{ $currency['symbol'] ?: substr($currency['code'], 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white">{{ $currency['name'] }}</p>
                                        <p class="text-xs text-gray-400">{{ $currency['code'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold {{ $currency['type'] === 'fiat' ? 'bg-purple-500/20 text-purple-400' : 'bg-orange-500/20 text-orange-400' }}">
                                    {{ ucfirst($currency['type']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $index }})"
                                        class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold transition-colors {{ $currency['is_active'] ? 'bg-green-500/20 text-green-400 hover:bg-green-500/30' : 'bg-gray-500/20 text-gray-400 hover:bg-gray-500/30' }}">
                                    <div
                                        class="w-2 h-2 rounded-full {{ $currency['is_active'] ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                                    {{ $currency['is_active'] ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400">1 Dcoin =</span>
                                    <input wire:model.blur="currencies.{{ $index }}.exchange_rate"
                                           type="number"
                                           step="0.0001"
                                           min="0"
                                           class="w-24 bg-gray-700 border border-gray-600 rounded px-2 py-1 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                                    <span class="text-xs text-gray-400">{{ $currency['code'] }}</span>
                                </div>
                                @error("currencies.$index.exchange_rate")
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <input wire:model.blur="currencies.{{ $index }}.min_redemption"
                                           type="number"
                                           min="1"
                                           class="w-20 bg-gray-700 border border-gray-600 rounded px-2 py-1 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                                    <span class="text-xs text-gray-400">Dcoins</span>
                                </div>
                                @error("currencies.$index.min_redemption")
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="updateSingleRate({{ $index }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded-lg transition-colors">
                                    <i data-lucide="check" class="w-3 h-3 inline mr-1"></i>
                                    Update
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                No supported currencies found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-blue-600/10 border border-blue-600/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-blue-400 mt-0.5"></i>
                <div>
                    <h4 class="text-blue-400 font-semibold mb-2">Exchange Rate Configuration</h4>
                    <div class="text-sm text-gray-300 space-y-1">
                        <p>• <strong>Exchange Rate:</strong> How many units of the currency equal 1 Dcoin</p>
                        <p>• <strong>Min Redemption:</strong> Minimum Dcoins required for redemption</p>
                        <p>• Example: If 1 Dcoin = 1000 NGN, users get 1000 Naira for every Dcoin redeemed</p>
                        <p>• Click "Update" for individual changes or "Save All Changes" for bulk updates</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Remove number input arrows */
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }
        </style>
    @endpush
</div>
