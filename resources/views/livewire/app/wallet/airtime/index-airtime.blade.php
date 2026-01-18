<div x-data="{
    showNetworkModal: false,
    showConfirmationModal: @entangle('showConfirmModal').live,
    showPinModal: false,
    showSuccessModal: @entangle('showSuccessModal').live
}">

    <x-slot name="header">
        <header
            class="bg-gradient-to-r from-gray-900/95 to-gray-800/95 backdrop-blur-xl sticky top-0 z-10 border-b border-gray-700/50 shadow-xl">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="text-gray-300 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-semibold text-white">Buy Airtime</h1>
                <div class="w-6"></div>
            </div>
        </header>
    </x-slot>

    <div class="p-2 space-y-4 max-w-2xl mx-auto">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div
                class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 text-green-300 px-5 py-4 rounded-xl shadow-lg backdrop-blur-sm animate-in slide-in-from-top duration-300">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div
                class="bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-300 px-5 py-4 rounded-xl shadow-lg backdrop-blur-sm animate-in slide-in-from-top duration-300">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Promotional Slider -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/80 to-gray-800/50 backdrop-blur-xl border border-gray-700/50 shadow-2xl"
            x-data="{
                currentSlide: 0,
                slides: [
                    {
                        title: 'Instant Airtime',
                        subtitle: 'Top up any network in seconds',
                        bgColor: 'from-blue-600/20 to-indigo-600/20'
                    },
                    {
                        title: 'Best Value',
                        subtitle: 'No hidden charges, pay what you see',
                        bgColor: 'from-purple-600/20 to-pink-600/20'
                    },
                    {
                        title: 'Always Available',
                        subtitle: '24/7 instant airtime delivery',
                        bgColor: 'from-green-600/20 to-emerald-600/20'
                    }
                ],
                autoplay: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    this.autoplay = setInterval(() => {
                        this.next();
                    }, 7000);
                },
                stopAutoplay() {
                    if (this.autoplay) {
                        clearInterval(this.autoplay);
                    }
                },
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                },
                prev() {
                    this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
                },
                goTo(index) {
                    this.currentSlide = index;
                    this.stopAutoplay();
                    this.startAutoplay();
                }
            }"
            @mouseenter="stopAutoplay()"
            @mouseleave="startAutoplay()">

            <!-- Slides Container -->
            <div class="relative h-32 sm:h-36 overflow-hidden">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="currentSlide === index"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-x-full"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-full"
                         class="absolute inset-0 flex items-center"
                         :class="'bg-gradient-to-br ' + slide.bgColor">

                        <div class="w-full px-6 py-6 flex items-center justify-between">
                            <div class="flex-1 space-y-1.5">
                                <h3 class="text-lg sm:text-xl font-bold text-white" x-text="slide.title"></h3>
                                <p class="text-xs sm:text-sm text-gray-300" x-text="slide.subtitle"></p>
                            </div>

                            <!-- Icon -->
                            <div
                                class="hidden sm:flex items-center justify-center w-20 h-20 bg-white/10 rounded-full backdrop-blur-sm">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Dot Indicators -->
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="goTo(index)"
                            class="w-2 h-2 rounded-full transition-all duration-300 touch-manipulation"
                            :class="currentSlide === index ? 'bg-[#e1b362] w-6' : 'bg-white/40 hover:bg-white/60'">
                    </button>
                </template>
            </div>
        </div>

        <!-- Form Card -->
        <div
            class="bg-gradient-to-br from-gray-800/80 to-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50 overflow-hidden">
            <div class="p-6 space-y-6">
                <!-- Network Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        Network Provider <span class="text-red-400">*</span>
                    </label>
                    <button type="button" @click="showNetworkModal = true"
                            class="w-full flex items-center justify-between px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-left hover:bg-gray-700/70 hover:border-gray-500/50 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200">
                        <span class="text-gray-200 flex items-center gap-3">
                            @if ($selectedNetwork)
                                @php
                                    $selectedNet = collect($networks)->where('code', $selectedNetwork)->first();
                                @endphp
                                <img src="{{ $selectedNet['logo'] }}" alt="{{ $selectedNet['name'] }}"
                                     class="w-6 h-6 object-contain rounded">
                                {{ $selectedNet['name'] }}
                            @else
                                Select Network
                            @endif
                        </span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @error('selectedNetwork')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="space-y-2">
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-300">
                        Phone Number <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="tel"
                        id="phoneNumber"
                        wire:model.live="phoneNumber"
                        placeholder="Enter phone number"
                        class="w-full px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200"
                        maxlength="11"
                    >
                    @error('phoneNumber')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-small text-gray-300">
                        Select Amount <span class="text-red-400">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @foreach($predefinedAmounts as $amount)
                            <button type="button" wire:click="selectAmount({{ $amount }})"
                                    class="px-4 py-3 bg-gray-700/50 border border-gray-600/50 rounded-xl
                                    text-gray-200 font-small hover:bg-gray-700/70 hover:border-gray-500/50
                                    focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200 {{ $selectedAmount === $amount ? 'bg-[#e1b362]/20 border-[#e1b362]/50 text-[#e1b362]' : '' }}">
                                ₦{{ number_format($amount) }}
                            </button>
                        @endforeach
                    </div>
                    <input
                        type="number"
                        wire:model.live="customAmount"
                        placeholder="Or enter custom amount"
                        class="w-full px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200"
                    >
                </div>

                <!-- Amount Display -->
                @if ($this->getSelectedAmountProperty() > 0)
                    <div
                        class="p-4 bg-gradient-to-br from-[#e1b362]/10 to-[#d4a55a]/10 border border-[#e1b362]/20 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300 text-sm">Amount to Pay</span>
                            <span
                                class="text-2xl font-bold text-white">₦{{ number_format($this->getSelectedAmountProperty(), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-gray-400 text-xs">Service Fee</span>
                            <span class="text-sm text-gray-400 line-through">₦0.00</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Purchase Button -->
            <div class="p-6 pt-0">
                <button type="button" wire:click="openConfirmationModal" wire:loading.attr="disabled"
                        class="w-full py-4 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                    {{ !$this->canProceed() ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span wire:loading.remove wire:target="openConfirmationModal">Continue to Payment</span>
                    <span wire:loading wire:target="openConfirmationModal">Processing...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Network Selection Modal -->
    <div x-show="showNetworkModal" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 modal-backdrop">
        <div @click.away="showNetworkModal = false" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-t-3xl sm:rounded-3xl w-full max-w-md border border-gray-700/50 shadow-2xl">
            <div class="p-6 space-y-5">
                <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                    <h3 class="text-xl font-semibold text-white">Select Network</h3>
                    <button @click="showNetworkModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    @foreach ($networks as $network)
                        <button type="button" wire:click="selectNetwork('{{ $network['code'] }}')"
                                @click="showNetworkModal = false"
                                class="w-full flex items-center justify-between p-4 bg-gray-700/30 hover:bg-gray-700/50 border border-gray-600/30 hover:border-[#e1b362]/50 rounded-xl transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <img src="{{ $network['logo'] }}" alt="{{ $network['name'] }}"
                                     class="w-8 h-8 object-contain rounded">
                                <span
                                    class="text-gray-200 font-medium group-hover:text-white">{{ $network['name'] }}</span>
                            </div>
                            @if ($selectedNetwork === $network['code'])
                                <svg class="w-5 h-5 text-[#e1b362]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmationModal" x-transition.opacity x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-2xl w-full max-w-sm border border-gray-700/50 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-5 space-y-5">
                <div class="text-center">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500/20 to-indigo-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">Confirm Airtime Purchase</h3>
                    <p class="text-gray-400 text-xs">Review your transaction</p>
                </div>

                <div class="space-y-3 bg-gray-700/30 rounded-xl p-4 border border-gray-600/30">
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Network</span>
                        <div class="flex items-center gap-1.5 text-right">
                            @if($selectedNetwork)
                                @php
                                    $selectedNet = collect($networks)->where('code', $selectedNetwork)->first();
                                @endphp
                                <img src="{{ $selectedNet['logo'] }}" alt="{{ $selectedNet['name'] }}"
                                     class="w-4 h-4 object-contain rounded">
                                <span class="text-white font-medium">{{ $selectedNet['name'] }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Phone</span>
                        <span class="text-white font-medium">{{ $phoneNumber }}</span>
                    </div>
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Service Fee</span>
                        <span class="text-gray-400 line-through">₦0.00</span>
                    </div>
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Current Balance</span>
                        <span class="text-white font-medium">₦{{ number_format($userBalance) }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-600/50">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-300 font-medium text-sm">Total</span>
                            <span
                                class="text-xl font-bold text-[#e1b362]">₦{{ number_format($this->getSelectedAmountProperty(), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Balance After</span>
                            <span
                                class="font-medium {{ ($userBalance - $this->getSelectedAmountProperty()) >= 0 ?
                                'text-[#ffffff]' : 'text-red-400' }}">
                                ₦{{ number_format($userBalance - $this->getSelectedAmountProperty()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PIN Input -->
                <div class="space-y-3">
                    <label class="text-xs font-medium text-gray-300 block text-center">Enter 4-digit PIN</label>
                    <input
                        type="password"
                        wire:model.live="pin"
                        placeholder="••••"
                        maxlength="4"
                        inputmode="numeric"
                        class="w-full px-4 py-3.5 bg-gray-700/50 border {{ $pinError ? 'border-red-500/50' : 'border-gray-600/50' }} rounded-xl text-gray-200 text-center text-xl font-bold tracking-widest placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 transition-all"
                        x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                        x-on:keydown.enter="if ($event.target.value.length === 4) $wire.confirmPurchase()"
                    >
                    @error('pin')
                    <p class="text-red-400 text-xs text-center">{{ $message }}</p>
                    @enderror

                    @if($pinError)
                        <div
                            class="bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-300 px-3 py-2 rounded-xl text-center">
                            <p class="text-xs">{{ $pinError }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex gap-2.5">
                    <button type="button"
                            wire:click="cancelPurchase"
                            wire:loading.class="hidden"
                            wire:target="confirmPurchase"
                            class="flex-1 px-4 py-3 bg-gray-700/50 text-gray-300 font-medium text-sm rounded-xl hover:bg-gray-700/70 active:scale-[0.98] transition-all">
                        Cancel
                    </button>
                    <button type="button"
                            wire:click="confirmPurchase"
                            wire:loading.attr="disabled"
                            wire:loading.class="hidden"
                            wire:target="confirmPurchase"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-[#d4a55a] to-[#d4a55a] text-white font-semibold text-sm rounded-xl hover:from-[#d4a55a] hover:to-[#d4a55a] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg flex items-center justify-center gap-1.5"
                            :disabled="!$wire.pin || $wire.pin.length !== 4">
                        Pay
                    </button>
                    <button type="button"
                            wire:loading.class.remove="hidden"
                            wire:target="confirmPurchase"
                            disabled
                            class="hidden w-full px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold text-sm rounded-xl disabled:cursor-not-allowed transition-all shadow-lg flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-3xl w-full max-w-sm border border-gray-700/50 shadow-2xl overflow-hidden">
            <div class="p-8 text-center space-y-6">
                <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white mb-2">Purchase Successful!</h3>
                    <p class="text-gray-400">Your airtime purchase is being processed. You will receive a notification
                        shortly.</p>
                </div>
                <button wire:click="closeSuccessModal"
                        class="w-full py-4 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Done
                </button>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            .modal-backdrop {
                background: rgba(0, 0, 0, 0.85);
                backdrop-filter: blur(8px);
            }

            [x-cloak] {
                display: none !important;
            }

            @keyframes slide-in-from-top {
                from {
                    opacity: 0;
                    transform: translateY(-1rem);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-in {
                animation: slide-in-from-top 0.3s ease-out;
            }

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }
        </style>
    @endpush
</div>
