<div>
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Bonus Management</h1>
                <div class="flex items-center gap-4">
                    <button wire:click="resetForm"
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                        Reset Form
                    </button>
                </div>
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div
                class="bg-gradient-to-br from-purple-500/20 to-purple-600/10 border border-purple-500/30 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-300 mb-1">Total Bonuses</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['total_bonuses']) }}</p>
                    </div>
                    <div class="p-3 bg-purple-500/20 rounded-full">
                        <i data-lucide="gift" class="w-8 h-8 text-purple-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500/20 to-green-600/10 border border-green-500/30 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-300 mb-1">Total Amount</p>
                        <p class="text-3xl font-bold text-white">${{ number_format($stats['total_amount'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-500/20 rounded-full">
                        <i data-lucide="dollar-sign" class="w-8 h-8 text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/10 border border-blue-500/30 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-300 mb-1">This Month</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['this_month']) }}</p>
                    </div>
                    <div class="p-3 bg-blue-500/20 rounded-full">
                        <i data-lucide="calendar" class="w-8 h-8 text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-yellow-500/20 to-yellow-600/10 border border-yellow-500/30 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-300 mb-1">Active Users</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['active_users']) }}</p>
                    </div>
                    <div class="p-3 bg-yellow-500/20 rounded-full">
                        <i data-lucide="users" class="w-8 h-8 text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Bonus Form -->
            <div class="lg:col-span-2">
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i data-lucide="sparkles" class="w-5 h-5 mr-2 text-[#E1B362]"></i>
                        Award Bonus
                    </h2>

                    <form wire:submit.prevent="submitBonus" class="space-y-6">
                        <!-- User Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Select User *
                            </label>
                            @if($selectedUser)
                                <div
                                    class="bg-gray-700 border border-gray-600 rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-full bg-gradient-to-br from-[#E1B362] to-[#d4a04d] flex items-center justify-center text-white font-semibold text-lg">
                                            {{ substr($selectedUser->fname ?? '', 0, 1) }}{{ substr($selectedUser->lname ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">
                                                {{ $selectedUser->fname }} {{ $selectedUser->lname }}
                                            </p>
                                            <p class="text-sm text-gray-400">{{ $selectedUser->email }}</p>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="clearUser"
                                            class="text-red-400 hover:text-red-300">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            @else
                                <div class="relative">
                                    <i data-lucide="search"
                                       class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input
                                        wire:model.live.debounce.300ms="search"
                                        type="text"
                                        placeholder="Search users by name or email..."
                                        class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                </div>
                                @if($search && count($users) > 0)
                                    <div
                                        class="mt-2 bg-gray-700 border border-gray-600 rounded-lg overflow-hidden max-h-60 overflow-y-auto">
                                        @foreach($users as $user)
                                            <button
                                                type="button"
                                                wire:click="selectUser({{ $user->id }})"
                                                class="w-full flex items-center gap-3 p-3 hover:bg-gray-600 transition-colors text-left">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold">
                                                    {{ substr($user->fname ?? '', 0, 1) }}{{ substr($user->lname ?? '', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-white">{{ $user->fname }} {{ $user->lname }}</p>
                                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @elseif($search)
                                    <p class="mt-2 text-sm text-gray-400">No users found</p>
                                @endif
                            @endif
                            @error('selectedUserId') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Bonus Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Bonus Type *
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <button
                                    type="button"
                                    wire:click="$set('bonusType', 'welcome')"
                                    class="p-4 rounded-lg border-2 transition-all {{ $bonusType === 'welcome' ? 'border-[#E1B362] bg-[#E1B362]/10' : 'border-gray-600 bg-gray-700 hover:border-gray-500' }}">
                                    <i data-lucide="hand-heart"
                                       class="w-6 h-6 mx-auto mb-2 {{ $bonusType === 'welcome' ? 'text-[#E1B362]' : 'text-gray-400' }}"></i>
                                    <p class="text-xs font-medium {{ $bonusType === 'welcome' ? 'text-[#E1B362]' : 'text-gray-300' }}">
                                        Welcome</p>
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('bonusType', 'referral')"
                                    class="p-4 rounded-lg border-2 transition-all {{ $bonusType === 'referral' ? 'border-blue-500 bg-blue-500/10' : 'border-gray-600 bg-gray-700 hover:border-gray-500' }}">
                                    <i data-lucide="users-round"
                                       class="w-6 h-6 mx-auto mb-2 {{ $bonusType === 'referral' ? 'text-blue-400' : 'text-gray-400' }}"></i>
                                    <p class="text-xs font-medium {{ $bonusType === 'referral' ? 'text-blue-400' : 'text-gray-300' }}">
                                        Referral</p>
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('bonusType', 'promo')"
                                    class="p-4 rounded-lg border-2 transition-all {{ $bonusType === 'promo' ? 'border-purple-500 bg-purple-500/10' : 'border-gray-600 bg-gray-700 hover:border-gray-500' }}">
                                    <i data-lucide="megaphone"
                                       class="w-6 h-6 mx-auto mb-2 {{ $bonusType === 'promo' ? 'text-purple-400' : 'text-gray-400' }}"></i>
                                    <p class="text-xs font-medium {{ $bonusType === 'promo' ? 'text-purple-400' : 'text-gray-300' }}">
                                        Promo</p>
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('bonusType', 'loyalty')"
                                    class="p-4 rounded-lg border-2 transition-all {{ $bonusType === 'loyalty' ? 'border-green-500 bg-green-500/10' : 'border-gray-600 bg-gray-700 hover:border-gray-500' }}">
                                    <i data-lucide="heart"
                                       class="w-6 h-6 mx-auto mb-2 {{ $bonusType === 'loyalty' ? 'text-green-400' : 'text-gray-400' }}"></i>
                                    <p class="text-xs font-medium {{ $bonusType === 'loyalty' ? 'text-green-400' : 'text-gray-300' }}">
                                        Loyalty</p>
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('bonusType', 'special')"
                                    class="p-4 rounded-lg border-2 transition-all {{ $bonusType === 'special' ? 'border-yellow-500 bg-yellow-500/10' : 'border-gray-600 bg-gray-700 hover:border-gray-500' }}">
                                    <i data-lucide="star"
                                       class="w-6 h-6 mx-auto mb-2 {{ $bonusType === 'special' ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                                    <p class="text-xs font-medium {{ $bonusType === 'special' ? 'text-yellow-400' : 'text-gray-300' }}">
                                        Special</p>
                                </button>
                            </div>
                            @error('bonusType') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Amount with Presets -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Bonus Amount [DCoins] *
                            </label>
                            <div class="flex gap-2 mb-3 flex-wrap">
                                @foreach($presetAmounts as $preset)
                                    <button
                                        type="button"
                                        wire:click="setPresetAmount({{ $preset }})"
                                        class="px-4 py-2 rounded-lg border transition-all {{ $amount == $preset ? 'border-[#E1B362] bg-[#E1B362]/20 text-[#E1B362]' : 'border-gray-600 bg-gray-700 text-gray-300 hover:border-gray-500' }}">
                                        {{ number_format($preset) }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></span>
                                <input
                                    wire:model="amount"
                                    type="number"
                                    step="0.01"
                                    placeholder="Enter custom amount"
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-8 pr-4 py-3 text-white text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                            </div>
                            @error('amount') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea
                                wire:model="notes"
                                rows="3"
                                placeholder="Add a note about this bonus..."
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"></textarea>
                            @error('notes') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-[#E1B362] to-[#d4a04d] hover:from-[#d4a04d] hover:to-[#c89540] text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2 shadow-lg">
                            <i data-lucide="gift" class="w-5 h-5"></i>
                            Award Bonus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Bonuses -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <i data-lucide="history" class="w-5 h-5 mr-2 text-gray-400"></i>
                        Recent Bonuses
                    </h3>
                    <div class="space-y-3">
                        @forelse($recentBonuses as $bonus)
                            <div class="bg-gray-700/50 rounded-lg p-3 border border-gray-600">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white text-xs font-semibold">
                                            {{ substr($bonus->user->fname ?? '', 0, 1) }}{{ substr($bonus->user->lname ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white">{{ $bonus->user->fname ?? 'N/A' }} {{ $bonus->user->lname ?? '' }}</p>
                                            <p class="text-xs text-gray-400">{{ $bonus->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xs px-2 py-1 rounded-full bg-gray-600 text-gray-300">{{ ucfirst($bonus->type) }}</span>
                                    <span
                                        class="text-lg font-bold text-[#E1B362]">{{ number_format($bonus->bonus_amount, 2) }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">No recent bonuses</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-data="{ show: @entangle('showSuccessModal') }" x-show="show" x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="show = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md border border-gray-700 p-6 text-center">
            <div class="mb-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                    <i data-lucide="check-circle" class="h-10 w-10 text-green-600"></i>
                </div>
            </div>
            <h3 class="mb-2 text-xl font-bold text-white">Bonus Awarded Successfully!</h3>
            <p class="mb-6 text-gray-400">The bonus has been added to the user's account.</p>
            <button
                @click="show = false"
                class="bg-[#E1B362] hover:bg-[#d4a04d] text-white font-medium py-2 px-6 rounded-lg transition-colors">
                Great!
            </button>
        </div>
    </div>
</div>
