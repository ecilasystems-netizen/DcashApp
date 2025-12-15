<div x-data="{ isCurrencyModalOpen: false, isRateModalOpen: false, selectedRate: null }"
     @close-modal.window="isCurrencyModalOpen = false">
    <x-slot name="header">
        <header
            class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Currencies & Rates</h1>
            </div>
        </header>
    </x-slot>

    <!-- Main Content -->

    <div class="p-6">


        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-emerald-500/20 rounded-lg">
                        <i data-lucide="landmark" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Total Currencies</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ $currencies->count() }}</div>
            </div>
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-500/20 rounded-lg">
                        <i data-lucide="banknote" class="w-5 h-5 text-blue-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Fiat Currencies</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ $currencies->where('type', 'fiat')->count() }}</div>
            </div>
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-500/20 rounded-lg">
                        <i data-lucide="bitcoin" class="w-5 h-5 text-purple-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Crypto Currencies</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ $currencies->where('type', 'crypto')->count() }}</div>
            </div>
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-amber-500/20 rounded-lg">
                        <i data-lucide="repeat" class="w-5 h-5 text-amber-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Exchange Pairs</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ count($currencyPairs) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Supported Currencies Section -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg">
                <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white">Supported Currencies</h3>
                    <button
                        @click="isCurrencyModalOpen = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 rounded-lg flex items-center gap-2 text-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add Currency
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-2">Currency</th>
                            <th class="px-4 py-2">Code</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                        @forelse($currencies as $currency)
                            <tr class="hover:bg-gray-700/30">
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        @if($currency->flag)
                                            <img src="{{ asset($currency->flag) }}"
                                                 alt="{{ $currency->name }}"
                                                 class="w-5 h-5 rounded-full object-cover border border-gray-600"/>
                                        @else
                                            <div
                                                class="w-5 h-5 rounded-full bg-gray-600 flex items-center justify-center">
                                                <i data-lucide="{{ $currency->type === 'crypto' ? 'bitcoin' : 'banknote' }}"
                                                   class="w-3 h-3 text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-white text-xs">{{ $currency->name }}</div>
                                            <div class="text-gray-400 text-xs">{{ $currency->symbol }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="font-mono text-gray-300 text-xs">{{ $currency->code }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $currency->type === 'crypto'
                                            ? 'bg-purple-500/20 text-purple-400'
                                            : 'bg-blue-500/20 text-blue-400' }}">
                                        @if($currency->type === 'crypto')
                                            <i data-lucide="bitcoin" class="w-3 h-3 mr-1"></i>
                                            Crypto
                                        @else
                                            <i data-lucide="banknote" class="w-3 h-3 mr-1"></i>
                                            Fiat
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <label class="switch-small">
                                        <input
                                            type="checkbox"
                                            wire:click="toggleCurrency({{ $currency->id }})"
                                            @if($currency->status) checked @endif
                                        />
                                        <span class="slider-small"></span>
                                    </label>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button
                                        class="bg-gray-700 hover:bg-gray-600 text-white p-1 rounded text-xs">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    No currencies found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Exchange Rates -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                <h3 class="font-bold text-white mb-4">Exchange Rates</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="py-2">Pair</th>
                            <th class="py-2">Buy Rate</th>
                            <th class="py-2">Sell Rate</th>
                            <th class="py-2">Auto</th>
                            <th class="py-2 text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">

                        @foreach($currencyPairs as $pair)
                            <tr>
                                <td class="py-3 font-semibold text-white">{{ $pair['pair'] }}</td>
                                <td class="py-3 text-green-400">{{ $pair['buy_rate'] }}</td>
                                <td class="py-3 text-red-400">{{ $pair['sell_rate'] }}</td>
                                <td class="py-3 text-center">
                                    <label class="switch-small">
                                        <input
                                            type="checkbox"
                                            wire:click="toggleAutoUpdate({{ $pair['id'] }})"
                                            @if($pair['auto_update']) checked @endif
                                        />
                                        <span class="slider-small"></span>
                                    </label>
                                </td>
                                <td class="py-3 text-right">
                                    <button
                                        @click="isRateModalOpen = true; selectedRate = {
                                            pair: '{{ $pair['pair'] }}',
                                            buyRate: '{{ $pair['buy_rate'] }}',
                                            sellRate: '{{ $pair['sell_rate'] }}',
                                            id: {{ $pair['id'] }}
                                        }; $wire.set('selectedRateId', {{ $pair['id'] }}); $wire.set('newBuyRate', '{{ $pair['buy_rate'] }}'); $wire.set('newSellRate', '{{ $pair['sell_rate'] }}')"
                                        class="text-xs bg-gray-700 hover:bg-gray-600 font-semibold py-1 px-3 rounded-lg">
                                        Update
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Currency Modal -->
    <div
        x-show="isCurrencyModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 flex items-center justify-center modal-overlay"
        style="display: none">
        <div
            @click.away="isCurrencyModalOpen = false"
            class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">Add New Currency</h3>

            <!-- Add this right after the form opening tag for debugging -->
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-4">
                    <h4 class="text-red-400 font-semibold">Validation Errors:</h4>
                    <ul class="text-red-300 text-sm mt-2">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-4" wire:submit.prevent="saveCurrency" enctype="multipart/form-data">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Currency Name</label>
                    <input
                        type="text"
                        placeholder="e.g., Kenyan Shilling"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white"
                        wire:model.live="name"
                    />
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Currency Code</label>
                    <input
                        type="text"
                        placeholder="e.g., KES"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white"
                        wire:model.live="code"
                    />
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Symbol</label>
                    <input
                        type="text"
                        placeholder="e.g., $"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white"
                        wire:model="symbol"
                    />
                    @error('symbol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Flag</label>
                    <input
                        type="file"
                        accept="image/*"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white"
                        wire:model="flag"
                    />
                    @error('flag') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @if ($flag)
                        <img src="{{ $flag->temporaryUrl() }}" alt="Flag Preview"
                             class="mt-3 h-12 rounded border border-gray-600"/>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Set as Crypto</span>
                    <label class="switch">
                        <input type="checkbox" wire:model="is_crypto"/>
                        <span class="slider"></span>
                    </label>
                </div>

                <!-- Display potential currency pairs if available -->
                @if(count($potentialPairs) > 0)
                    <div class="mt-6">
                        <h4 class="text-white font-semibold mb-2">Set Exchange Rates</h4>
                        <div class="overflow-x-auto max-h-60 overflow-y-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs text-gray-400 uppercase sticky top-0 bg-gray-800 z-10">
                                <tr>
                                    <th class="py-2">Currency Pair</th>
                                    <th class="py-2">Rate</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                @foreach($potentialPairs as $pair)
                                    <tr>
                                        <td class="py-2 font-semibold text-white">
                                            {{ $pair['pair'] }}
                                            <span
                                                class="text-xs ml-1 {{ $pair['direction'] == 'buy' ? 'text-green-400' : 'text-red-400' }}">
                                        ({{ ucfirst($pair['direction']) }})
                                    </span>
                                        </td>
                                        <td class="py-2">
                                            <input
                                                type="number"
                                                step="0.0001"
                                                min="0"
                                                placeholder="Enter rate"
                                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-2 py-1 text-white text-sm"
                                                wire:model.live="newPairRates.{{ $pair['key'] }}"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif


                <div class="pt-4 flex justify-end gap-4">
                    <button
                        type="button"
                        @click="isCurrencyModalOpen = false"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg">
                        <span wire:loading.remove>Save Currency</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>


            </form>

        </div>
    </div>

    <!-- Update Rate Modal -->
    <div
        x-show="isRateModalOpen"
        @close-rate-modal.window="isRateModalOpen = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 flex items-center justify-center modal-overlay"
        style="display: none"
        @keydown.escape.window="isRateModalOpen = false">

        <div
            @click.away="isRateModalOpen = false"
            class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">

            @if (session()->has('error'))
                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-2"></i>
                        <span class="text-red-400 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-white"
                    x-text="`Update Rate for ${selectedRate?.pair || 'Currency Pair'}`"></h3>
                <button
                    @click="isRateModalOpen = false"
                    class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form class="space-y-4" wire:submit.prevent="updateRate">
                <!-- Current Rates Display -->
                <div class="bg-gray-700/50 rounded-lg p-3 mb-4" x-show="selectedRate">
                    <div class="text-xs text-gray-400 mb-2">Current Rates</div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Buy:</span>
                            <span class="text-green-400 font-medium" x-text="selectedRate?.buyRate"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Sell:</span>
                            <span class="text-red-400 font-medium" x-text="selectedRate?.sellRate"></span>
                        </div>
                    </div>
                </div>

                <!-- New Rates Input -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">New Buy Rate</label>
                        <input
                            type="number"
                            step="0.0001"
                            min="0"
                            wire:model.live="newBuyRate"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter buy rate"/>
                        @error('newBuyRate')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">New Sell Rate</label>
                        <input
                            type="number"
                            step="0.0001"
                            min="0"
                            wire:model.live="newSellRate"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter sell rate"/>
                        @error('newSellRate')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Rate Change Preview -->
                <div x-show="selectedRate && ($wire.newBuyRate || $wire.newSellRate)"
                     class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-3">
                    <div class="text-xs text-blue-400 mb-2">Rate Changes</div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div x-show="$wire.newBuyRate && $wire.newBuyRate !== selectedRate?.buyRate">
                            <span class="text-gray-400">Buy:</span>
                            <span class="text-green-400" x-text="selectedRate?.buyRate"></span>
                            <span class="text-gray-400">→</span>
                            <span class="text-green-300 font-medium" x-text="$wire.newBuyRate"></span>
                        </div>
                        <div x-show="$wire.newSellRate && $wire.newSellRate !== selectedRate?.sellRate">
                            <span class="text-gray-400">Sell:</span>
                            <span class="text-red-400" x-text="selectedRate?.sellRate"></span>
                            <span class="text-gray-400">→</span>
                            <span class="text-red-300 font-medium" x-text="$wire.newSellRate"></span>
                        </div>
                    </div>
                </div>

                <input type="hidden" wire:model="selectedRateId">

                <div class="pt-4 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="isRateModalOpen = false"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!$wire.newBuyRate && !$wire.newSellRate">
                        <span wire:loading.remove wire:target="updateRate">Update Rate</span>
                        <span wire:loading wire:target="updateRate" class=" items-center flex gap-0">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                            Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>


            /* Custom switch toggle */
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
                background-color: #4b5563;
                transition: 0.4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: 0.4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background-color: #10b981;
            }

            input:checked + .slider:before {
                transform: translateX(16px);
            }

            /* Perfect semi-transparent overlay */
            .modal-overlay {
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(1px);
            }

            /* Smaller switch for compact table */
            .switch-small {
                position: relative;
                display: inline-block;
                width: 28px;
                height: 16px;
            }

            .switch-small input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider-small {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #4b5563;
                transition: 0.4s;
                border-radius: 34px;
            }

            .slider-small:before {
                position: absolute;
                content: "";
                height: 12px;
                width: 12px;
                left: 2px;
                bottom: 2px;
                background-color: white;
                transition: 0.4s;
                border-radius: 50%;
            }

            input:checked + .slider-small {
                background-color: #10b981;
            }

            input:checked + .slider-small:before {
                transform: translateX(12px);
            }

            /* Your existing styles... */
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
                background-color: #4b5563;
                transition: 0.4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: 0.4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background-color: #10b981;
            }

            input:checked + .slider:before {
                transform: translateX(16px);
            }

            .modal-overlay {
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(1px);
            }
        </style>
    @endpush

    @push('scripts')

        <script>

            // Alpine.js store for managing modal state
            window.addEventListener('close-currency-modal', (event) => {
                window.dispatchEvent(new CustomEvent('close-modal'));
            });

            window.addEventListener('close-modal', () => {
                document.querySelector('[x-data]').__x.$data.isRateModalOpen = false;
            });
        </script>

        @if (session()->has('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Assuming you have a toast notification system
                    showToast('error', '{{ session('error') }}');
                });
            </script>
        @endif
    @endpush
</div>
