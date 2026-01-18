<div x-data="{
    showNetworkModal: false,
    showConfirmationModal: @entangle('showConfirmModal').live,
    showSuccessModal: @entangle('showSuccessModal').live
}">

    <x-slot name="header">
        <header
            class="bg-gradient-to-r from-gray-900/95 to-gray-800/95 backdrop-blur-xl sticky top-0 z-10 border-b border-gray-700/50 shadow-xl">
            <div class="px-4 py-3.5 flex justify-between items-center">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="text-gray-300 hover:text-white transition-colors p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Buy Data Bundle</h1>
                <div class="w-6"></div>
            </div>
        </header>
    </x-slot>

    <div class="p-2 sm:p-4 space-y-4 pb-safe">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div
                class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl shadow-lg backdrop-blur-sm">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1">{{ session('success') }}</p>
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
                        title: 'Fast & Reliable Data',
                        subtitle: 'Get instant data delivery to any network',
                        image: '{{ asset('images/data-promo-1.jpg') }}',
                        bgColor: 'from-blue-600/20 to-indigo-600/20'
                    },
                    {
                        title: 'Best Rates Guaranteed',
                        subtitle: 'Enjoy competitive prices on all bundles',
                        image: '{{ asset('images/data-promo-2.jpg') }}',
                        bgColor: 'from-purple-600/20 to-pink-600/20'
                    },
                    {
                        title: '24/7 Support',
                        subtitle: 'Always here to help with your data needs',
                        image: '{{ asset('images/data-promo-3.jpg') }}',
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

                            <!-- Optional: Add icon or illustration -->
                            <div
                                class="hidden sm:flex items-center justify-center w-20 h-20 bg-white/10 rounded-full backdrop-blur-sm">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
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
            class="bg-gradient-to-br from-gray-800/80 to-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50">
            <div class="p-4 space-y-4">
                <!-- Network Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        Network Provider <span class="text-red-400">*</span>
                    </label>
                    <button type="button" @click="showNetworkModal = true"
                            class="w-full flex items-center justify-between px-3.5 py-3 bg-gray-700/50 border border-gray-600/50 rounded-xl text-left hover:bg-gray-700/70 active:scale-[0.98] transition-all duration-200">
                        <span class="text-gray-200 flex items-center gap-2.5 text-sm">
                            @if ($this->selectedNetworkRecord)
                                <img src="{{ $this->selectedNetworkRecord['logo'] }}"
                                     alt="{{ $this->selectedNetworkRecord['name'] }}"
                                     class="w-5 h-5 object-contain rounded">
                                {{ $this->selectedNetworkRecord['name'] }}
                            @else
                                Select Network
                            @endif
                        </span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @error('selectedNetwork')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
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
                        class="w-full px-3.5 py-3 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all"
                        maxlength="11"
                        inputmode="numeric"
                    >
                    @error('phoneNumber')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data Bundles -->
                @if($selectedNetwork && !empty($availableBundles))
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-300">
                            Select Data Bundle <span class="text-red-400">*</span>
                        </label>

                        <!-- Tabs -->
                        <div class="flex bg-gray-700/30 rounded-xl p-1 gap-1">
                            @foreach(['daily', 'weekly', 'monthly'] as $tab)
                                <button type="button" wire:click="selectTab('{{ $tab }}')"
                                        class="flex-1 px-3 py-2 text-xs font-medium rounded-lg transition-all active:scale-95 {{ $currentTab === $tab ? 'bg-[#e1b362] text-gray-900' : 'text-gray-300 hover:bg-gray-700/50' }}">
                                    {{ ucfirst($tab) }}
                                    @if(!empty($availableBundles[$tab]))
                                        <span class="ml-0.5 text-[10px] opacity-70">({{ count($availableBundles[$tab]) }})</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <!-- Bundle Cards -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                            @if(!empty($availableBundles[$currentTab]))
                                @foreach($availableBundles[$currentTab] as $bundle)
                                    <div wire:click="selectBundle({{ $bundle['id'] }})"
                                         class="cursor-pointer p-2.5 sm:p-3 bg-gray-700/30 border border-gray-600/30 rounded-lg active:scale-[0.98] transition-all touch-manipulation {{ $selectedBundleId === $bundle['id'] ? 'bg-[#e1b362]/20 border-[#e1b362]/50 ring-1 ring-[#e1b362]/30' : '' }}">
                                        <div class="space-y-1.5 sm:space-y-1 text-center">
                                            <div class="space-y-1">
                                                <h4 class="font-medium text-white text-sm sm:text-xs">{{ $bundle['data_size'] ?? 'N/A' }}</h4>
                                                <div class="flex justify-center">
                                                    <span
                                                        class="text-[10px] sm:text-[9px] px-1.5 py-0.5 bg-blue-500/20 text-blue-300 rounded-full whitespace-nowrap">
                                                        {{ $bundle['duration_days'].' Day(s)' ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-center gap-1">
                                                <p class="text-sm font-bold text-[#e1b362]">
                                                    ₦{{ number_format($bundle['amount'] / 100, 2) }}</p>
                                                @if($selectedBundleId === $bundle['id'])
                                                    <svg class="w-4 h-4 sm:w-3 sm:h-3 text-[#e1b362] flex-shrink-0"
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-2 sm:col-span-3 text-center py-6">
                                    <svg class="w-10 h-10 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-gray-400 text-sm">No {{ $currentTab }} plans</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Selected Bundle Display -->
                @if($this->selectedAmount > 0)
                    <div
                        class="p-3.5 bg-gradient-to-br from-[#e1b362]/10 to-[#d4a55a]/10 border border-[#e1b362]/20 rounded-xl">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-gray-300 text-xs">Total Amount</span>
                            <span
                                class="text-xl font-bold text-white">₦{{ number_format($this->selectedAmount) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 text-[10px]">Service Fee</span>
                            <span class="text-xs text-gray-400 line-through">₦0</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Purchase Button -->
            <div class="p-4 pt-0">
                <button type="button" wire:click="openConfirmationModal" wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg flex items-center justify-center gap-2 text-sm"
                    {{ !$this->canProceed() ? 'disabled' : '' }}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span wire:loading.remove wire:target="openConfirmationModal">Continue to Payment</span>
                    <span wire:loading wire:target="openConfirmationModal">Loading...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Network Selection Modal -->
    <div x-show="showNetworkModal" x-transition.opacity x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center modal-backdrop">
        <div @click.away="showNetworkModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-t-3xl sm:rounded-3xl w-full sm:max-w-md border border-gray-700/50 shadow-2xl safe-bottom">
            <div class="p-4 space-y-4">
                <!-- Header with drag indicator -->
                <div class="flex flex-col items-center gap-2">
                    <div class="w-12 h-1 bg-gray-600 rounded-full"></div>
                    <div class="w-full flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-white">Select Network</h3>
                        <button @click="showNetworkModal = false" class="text-gray-400 hover:text-white p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-2 max-h-[60vh] overflow-y-auto">
                    @foreach ($networks as $network)
                        <button type="button" wire:click="selectNetwork('{{ $network['code'] }}')"
                                @click="showNetworkModal = false"
                                class="w-full flex items-center justify-between p-3.5 bg-gray-700/30 hover:bg-gray-700/50 active:scale-[0.98] border border-gray-600/30 hover:border-[#e1b362]/50 rounded-xl transition-all">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $network['logo'] }}" alt="{{ $network['name'] }}"
                                     class="w-7 h-7 object-contain rounded">
                                <span class="text-gray-200 font-medium text-sm">{{ $network['name'] }}</span>
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
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">Confirm Data Purchase</h3>
                    <p class="text-gray-400 text-xs">Review your transaction</p>
                </div>

                @if($this->getSelectedBundle())
                    <div class="space-y-3 bg-gray-700/30 rounded-xl p-4 border border-gray-600/30">
                        <div class="flex justify-between items-start text-xs">
                            <span class="text-gray-400">Network</span>
                            <div class="flex items-center gap-1.5 text-right">
                                @if($this->selectedNetworkRecord)
                                    <img src="{{ $this->selectedNetworkRecord['logo'] }}"
                                         alt="{{ $this->selectedNetworkRecord['name'] }}"
                                         class="w-4 h-4 object-contain rounded">
                                    <span
                                        class="text-white font-medium">{{ $this->selectedNetworkRecord['name'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-start text-xs">
                            <span class="text-gray-400">Phone</span>
                            <span class="text-white font-medium">{{ $phoneNumber }}</span>
                        </div>
                        <div class="flex justify-between items-start text-xs">
                            <span class="text-gray-400">Bundle</span>
                            <div class="text-right">
                                <span class="text-white font-medium">{{ $this->getSelectedBundle()->data_size }}</span>
                                <p class="text-[10px] text-gray-400">{{ $this->getSelectedBundle()->validity }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-600/50">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-300 font-medium text-sm">Total</span>
                                <span
                                    class="text-xl font-bold text-[#e1b362]">₦{{ number_format($this->selectedAmount) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-400">Balance After</span>
                                <span
                                    class="font-medium {{ ($userBalance - $this->selectedAmount) >= 0 ?
                                    'text-[#ffffff]' :
                                    'text-red-400' }}">
                                    ₦{{ number_format($userBalance - $this->selectedAmount) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

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
                    >
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
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-[#e1b362] to-[#e1b362] text-white font-semibold text-sm rounded-xl hover:from-[#e1b362] hover:to-[#e1b362] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg flex items-center justify-center gap-1.5"
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
    <div x-show="showSuccessModal" x-transition.opacity x-cloak
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
                    <p class="text-gray-400 text-sm">Data bundle purchased</p>
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
                background: rgba(0, 0, 0, 0.9);
                backdrop-filter: blur(8px);
            }

            [x-cloak] {
                display: none !important;
            }

            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom, 1rem);
            }

            .safe-bottom {
                padding-bottom: env(safe-area-inset-bottom, 0);
            }

            /* Custom scrollbar for mobile */
            @media (max-width: 640px) {
                ::-webkit-scrollbar {
                    width: 4px;
                }

                ::-webkit-scrollbar-track {
                    background: rgba(75, 85, 99, 0.2);
                    border-radius: 10px;
                }

                ::-webkit-scrollbar-thumb {
                    background: rgba(156, 163, 175, 0.3);
                    border-radius: 10px;
                }

                ::-webkit-scrollbar-thumb:hover {
                    background: rgba(156, 163, 175, 0.5);
                }
            }

            /* Prevent iOS zoom on input focus */
            @media screen and (max-width: 768px) {
                input[type="tel"],
                input[type="password"],
                select {
                    font-size: 16px !important;
                }
            }

            /* Smooth scrolling for overscroll */
            .overscroll-contain {
                overscroll-behavior: contain;
                -webkit-overflow-scrolling: touch;
            }
        </style>
    @endpush
</div>
