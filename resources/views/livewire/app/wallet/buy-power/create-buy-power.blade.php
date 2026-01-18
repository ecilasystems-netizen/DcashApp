<div x-data="{
    showProviderModal: false,
    showMeterTypeModal: false,
    showConfirmationModal: @entangle('showConfirmationModal'),
    showSuccessModal: @entangle('showSuccessModal'),
    isVerifying: false,
    isPurchasing: false
}"
     @transaction-success.window="showConfirmationModal = false; showSuccessModal = true">

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
                <h1 class="text-xl font-semibold text-white">Buy Electricity</h1>
                <div class="w-6"></div>
            </div>
        </header>
    </x-slot>

    <div class="p-2 space-y-4 max-w-2xl mx-auto">


        <!-- Promotional Slider -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/80 to-gray-800/50 backdrop-blur-xl border border-gray-700/50 shadow-2xl"
            x-data="{
                currentSlide: 0,
              slides: [
                    {
                        title: 'Power Up Instantly',
                        subtitle: 'Get your meter token in under 30 seconds',
                        bgColor: 'from-blue-600/20 to-indigo-600/20'
                    },
                    {
                        title: 'Never Stay in the Dark',
                        subtitle: 'Recharge your meter anytime, anywhere',
                        bgColor: 'from-purple-600/20 to-pink-600/20'
                    },
                    {
                        title: 'Transparent Pricing',
                        subtitle: 'What you pay is what you get on your meter',
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
                <!-- Provider Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        Electricity Provider <span class="text-red-400">*</span>
                    </label>
                    <button @click="showProviderModal = true"
                            type="button"
                            class="w-full flex items-center justify-between px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-left hover:bg-gray-700/70 hover:border-gray-500/50 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200">
                        <span
                            class="text-gray-200">{{ $provider ? $providers[$provider] ?? 'Select Provider' : 'Select Provider' }}</span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @error('provider')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meter Type Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        Meter Type <span class="text-red-400">*</span>
                    </label>
                    <button @click="showMeterTypeModal = true"
                            type="button"
                            class="w-full flex items-center justify-between px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-left hover:bg-gray-700/70 hover:border-gray-500/50 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200">
                        <span class="text-gray-200">{{ $meterType }}</span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @error('meterType')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meter Number Input -->
                <div class="space-y-2">
                    <label for="meterNumber" class="block text-sm font-medium text-gray-300">
                        Meter Number <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input wire:model.live.debounce.500ms="meterNumber"
                               type="text"
                               id="meterNumber"
                               placeholder="Enter meter number"
                               class="w-full px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200 pr-20"
                               maxlength="13">
                        @if($provider && strlen($meterNumber) >= 10)
                            <button wire:click="verifyMeter"
                                    wire:loading.remove
                                    wire:target="verifyMeter"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#e1b362] text-sm font-medium hover:text-[#d4a55a] transition-colors">
                                Verify
                            </button>
                            <div wire:loading wire:target="verifyMeter"
                                 class="absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin h-5 w-5 text-[#e1b362]" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    @error('meterNumber')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror

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

                </div>

                <!-- Verified Customer Details -->
                @if($customerName)
                    <div
                        class="p-4 bg-gradient-to-br from-green-500/10 to-emerald-500/10 border border-green-500/20 rounded-xl space-y-2">
                        <div class="flex items-center gap-2 text-green-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-semibold">Meter Verified</span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-white font-semibold">{{ $customerName }}</p>
                            @if($customerAddress)
                                <p class="text-gray-400 text-sm">{{ $customerAddress }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Amount Input -->
                <div class="space-y-2">
                    <label for="amount" class="block text-sm font-medium text-gray-300">
                        Amount (₦) <span class="text-red-400">*</span>
                    </label>
                    <input wire:model.live="amount"
                           type="number"
                           id="amount"
                           min="900"
                           max="500000"
                           inputmode="numeric"
                           pattern="\d*"
                           placeholder="0"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                           class="w-full px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200">
                    @error('amount')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>
            </div>

            <!-- Purchase Button -->
            <div class="p-6 pt-0">
                <button @click="$wire.validatePurchase().then(() => { showConfirmationModal = true })"
                        :disabled="!{{ $customerName ? 'true' : 'false' }} || {{ $amount }} < 900"
                        class="w-full py-4 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Continue to Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Provider Selection Modal -->
    <div x-show="showProviderModal" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 modal-backdrop">
        <div @click.away="showProviderModal = false" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-t-3xl sm:rounded-3xl w-full max-w-md border border-gray-700/50 shadow-2xl">
            <div class="p-6 space-y-5">
                <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                    <h3 class="text-xl font-semibold text-white">Select Provider</h3>
                    <button @click="showProviderModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    @foreach($providers as $code => $name)
                        <button wire:click="$set('provider', '{{ $code }}')"
                                @click="showProviderModal = false"
                                class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700/50 text-white {{ $provider === $code ? 'bg-gray-700' : 'bg-gray-800/50' }} transition-all duration-200">
                            {{ $name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Meter Type Selection Modal -->
    <div x-show="showMeterTypeModal" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 modal-backdrop">
        <div @click.away="showMeterTypeModal = false" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-t-3xl sm:rounded-3xl w-full max-w-md border border-gray-700/50 shadow-2xl">
            <div class="p-6 space-y-5">
                <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                    <h3 class="text-xl font-semibold text-white">Select Meter Type</h3>
                    <button @click="showMeterTypeModal = false"
                            class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    <button wire:click="$set('meterType', 'PREPAID')"
                            @click="showMeterTypeModal = false"
                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700/50 text-white {{ $meterType === 'PREPAID' ? 'bg-gray-700' : 'bg-gray-800/50' }} transition-all duration-200">
                        Prepaid Meter
                    </button>
                    <button wire:click="$set('meterType', 'POSTPAID')"
                            @click="showMeterTypeModal = false"
                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700/50 text-white {{ $meterType === 'POSTPAID' ? 'bg-gray-700' : 'bg-gray-800/50' }} transition-all duration-200">
                        Postpaid Meter
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Confirmation Modal -->
    <div x-show="showConfirmationModal"
         @keydown.escape.window="showConfirmationModal = false"
         x-transition.opacity
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-2xl w-full max-w-sm border border-gray-700/50 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-5 space-y-5">
                <div class="text-center">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">Confirm Purchase</h3>
                    <p class="text-gray-400 text-xs">Review your electricity purchase</p>
                </div>

                <!-- Transaction Details -->
                <div class="space-y-3 bg-gray-700/30 rounded-xl p-4 border border-gray-600/30">
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Provider</span>
                        <span class="text-white font-medium">{{ $provider ? $providers[$provider] : '' }}</span>
                    </div>
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Meter Type</span>
                        <span class="text-white font-medium">{{ $meterType }}</span>
                    </div>
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Meter Number</span>
                        <span class="text-white font-medium">{{ $meterNumber }}</span>
                    </div>
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-gray-400">Customer Name</span>
                        <div class="text-right">
                            <span class="text-white font-medium">{{ $customerName }}</span>
                            @if($customerAddress)
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ Str::limit($customerAddress, 30) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-600/50">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-300 font-medium text-sm">Amount</span>
                            <span class="text-xl font-bold text-[#e1b362]">₦{{ number_format($amount) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Balance After</span>
                            <span
                                class="font-medium {{ ($userBalance - $amount) >= 0 ? 'text-white' : 'text-red-400' }}">
                                ₦{{ number_format($userBalance - $amount) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PIN Input -->
                <div class="space-y-3">
                    <label class="text-xs font-medium text-gray-300 block text-center">Enter 4-digit PIN</label>
                    <input type="password"
                           wire:model.live="pin"
                           placeholder="••••"
                           maxlength="4"
                           inputmode="numeric"
                           pattern="\d*"
                           class="w-full px-4 py-3.5 bg-gray-700/50 border {{ $pinError ? 'border-red-500/50' : 'border-gray-600/50' }} rounded-xl text-gray-200 text-center text-xl font-bold tracking-widest placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 transition-all"
                           x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')">
                    @if($pinError)
                        <div
                            class="bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-300 px-3 py-2 rounded-xl text-center">
                            <p class="text-xs">{{ $pinError }}</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2.5">
                    <button type="button"
                            @click="showConfirmationModal = false; $wire.cancelPurchase()"
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
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-white font-semibold text-sm rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg flex items-center justify-center gap-1.5"
                            :disabled="!$wire.pin || $wire.pin.length !== 4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Pay Now
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
    <div x-show="showSuccessModal"
         x-transition.opacity
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-2xl w-full max-w-xs border border-gray-700/50 shadow-2xl">
            <div class="p-6 text-center space-y-5">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">Success!</h3>
                    <p class="text-gray-400 text-sm">Electricity purchase is being processed</p>
                </div>
                <button wire:click="closeSuccessModal"
                        class="w-full py-3.5 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold text-sm rounded-xl active:scale-[0.98] transition-all shadow-lg">
                    Done
                </button>
            </div>
        </div>
    </div>


    @push('styles')
        <style>
            .modal-backdrop {
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(8px);
            }

            [x-cloak] {
                display: none !important;
            }

            @keyframes slide-in-from-top {
                0% {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                100% {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .animate-in {
                animation-fill-mode: both;
            }

            .slide-in-from-top {
                animation-name: slide-in-from-top;
            }

            .duration-300 {
                animation-duration: 300ms;
            }
        </style>
    @endpush
</div>
