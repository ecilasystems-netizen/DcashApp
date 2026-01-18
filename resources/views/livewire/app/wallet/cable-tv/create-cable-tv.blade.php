<div x-data="{
    showProviderModal: false,
    showBundleModal: false,
    showConfirmationModal: @entangle('showConfirmationModal'),
    showPinModal: @entangle('showPinModal'),
    isVerifying: false
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
                <h1 class="text-xl font-semibold text-white">Pay Cable TV</h1>
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
                       title: 'Never Miss Your Shows',
                       subtitle: 'Renew DStv, GOtv & Startimes instantly',
                       bgColor: 'from-blue-600/20 to-indigo-600/20'
                   },
                   {
                       title: 'Best Rates Guaranteed',
                       subtitle: 'Enjoy competitive prices on all packages',
                       bgColor: 'from-purple-600/20 to-pink-600/20'
                   },
                   {
                       title: '24/7 Instant Activation',
                       subtitle: 'Your subscription activates immediately',
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
                <!-- TV Provider Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        TV Provider <span class="text-red-400">*</span>
                    </label>
                    <button type="button" @click="showProviderModal = true"
                            class="w-full flex items-center justify-between px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-left hover:bg-gray-700/70 hover:border-gray-500/50 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200">
                        <span class="text-gray-200">
                            @if ($selectedProvider)
                                {{ $this->providerName }}
                            @else
                                Select TV Provider
                            @endif
                        </span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @error('selectedProvider')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Smartcard/IUC Number -->
                <div class="space-y-2">
                    <label for="cardNumber" class="block text-sm font-medium text-gray-300">
                        Smartcard/IUC Number <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="cardNumber"
                            wire:model.live="cardNumber"
                            x-on:input="
                if ($event.target.value.length === 10) {
                    $wire.verifyCard();
                }
            "
                            placeholder="Smartcard/IUC Number"
                            class="w-full px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200 pr-24"
                            maxlength="10"
                            minlength="10"
                            {{ !$selectedProvider ? 'disabled' : '' }}
                        >

                        {{-- Verified badge or Verifying spinner --}}
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            {{-- Show spinner while verifying --}}
                            <span wire:loading wire:target="verifyCard"
                                  class="inline-flex items-center gap-2 text-gray-300">
                <svg class="w-5 h-5 animate-spin text-[#e1b362]" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-xs font-medium">Verifying...</span>
            </span>

                            {{-- Show verified badge when done --}}
                            <span wire:loading.remove wire:target="verifyCard">
                @if($isVerified)
                                    <div
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-lg border border-green-500/30">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        Verified
                    </div>
                                @endif
            </span>
                        </div>
                    </div>
                    @error('cardNumber')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Details (Shown after verification) -->
                @if ($isVerified && $customerName)
                    <div
                        class="p-4 bg-gradient-to-br from-green-500/10 to-emerald-500/10 border border-green-500/20 rounded-xl space-y-2">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1 space-y-1">
                                <p class="text-sm text-gray-400">Customer Name</p>
                                <p class="text-white font-medium">{{ $customerName }}</p>
                                @if ($customerAddress)
                                    <p class="text-sm text-gray-400 mt-2">Address</p>
                                    <p class="text-gray-300 text-sm">{{ $customerAddress }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Bundle Selection -->
                @if ($selectedProvider && $isVerified)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">
                            Select Package <span class="text-red-400">*</span>
                        </label>
                        <button type="button" @click="showBundleModal = true"
                                class="w-full flex items-center justify-between px-4 py-3.5 bg-gray-700/50 border border-gray-600/50 rounded-xl text-left hover:bg-gray-700/70 hover:border-gray-500/50 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200">
                            <span class="text-gray-200">
                                @if ($this->selectedBundle)
                                    {{ $this->selectedBundle['name'] }}
                                @else
                                    Select Package
                                @endif
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        @error('selectedBundleCode')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Amount Display -->
                @if ($amount > 0)
                    <div
                        class="p-4 bg-gradient-to-br from-[#e1b362]/10 to-[#d4a55a]/10 border border-[#e1b362]/20 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300 text-sm">Amount to Pay</span>
                            <span class="text-2xl font-bold text-white">₦{{ number_format($amount, 2) }}</span>
                        </div>
                        @if ($this->selectedBundle)
                            <p class="text-gray-400 text-xs mt-2">
                                Duration: {{ $this->selectedBundle['formattedDuration'] }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Purchase Button -->
            <div class="p-6 pt-0">
                <button type="button" wire:click="showConfirmation" wire:loading.attr="disabled"
                        class="w-full py-4 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                    {{ !$isVerified || !$selectedBundleCode || $amount <= 0 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Continue to Payment</span>
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
                    <h3 class="text-xl font-semibold text-white">Select TV Provider</h3>
                    <button @click="showProviderModal = false"
                            class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    @foreach ($providers as $providerId => $providerName)
                        <button type="button" wire:click="$set('selectedProvider', '{{ $providerId }}')"
                                @click="showProviderModal = false"
                                class="w-full flex items-center justify-between p-4 bg-gray-700/30 hover:bg-gray-700/50 border border-gray-600/30 hover:border-[#e1b362]/50 rounded-xl transition-all duration-200 group">
                            <span class="text-gray-200 font-medium group-hover:text-white">{{ $providerName }}</span>
                            @if ($selectedProvider === $providerId)
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

    <!-- Bundle Selection Modal -->
    <div x-show="showBundleModal" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 modal-backdrop">
        <div @click.away="showBundleModal = false" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-t-3xl sm:rounded-3xl w-full max-w-md border border-gray-700/50 shadow-2xl max-h-[80vh] flex flex-col">
            <div class="p-6 space-y-5 flex-1 overflow-hidden flex flex-col">
                <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                    <h3 class="text-xl font-semibold text-white">Select Package</h3>
                    <button @click="showBundleModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div
                    class="space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent pr-2">
                    @forelse($availableBundles as $bundle)
                        <button type="button" wire:click="$set('selectedBundleCode', '{{ $bundle['bundleCode'] }}')"
                                @click="showBundleModal = false"
                                class="w-full flex items-start justify-between p-4 bg-gray-700/30 hover:bg-gray-700/50 border border-gray-600/30 hover:border-[#e1b362]/50 rounded-xl transition-all duration-200 group">
                            <div class="flex-1 text-left">
                                <p class="text-gray-200 font-medium group-hover:text-white mb-1">{{ $bundle['name'] }}
                                </p>
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="text-[#e1b362] font-semibold">{{ $bundle['formattedAmount'] }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="text-gray-400">{{ $bundle['formattedDuration'] }}</span>
                                </div>
                            </div>
                            @if ($selectedBundleCode === $bundle['bundleCode'])
                                <svg class="w-5 h-5 text-[#e1b362] flex-shrink-0 mt-1" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </button>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <p>No packages available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if ($customerName && $amount > 0)
        <div x-show="showConfirmationModal" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-3xl w-full max-w-md border border-gray-700/50 shadow-2xl overflow-hidden">
                <div class="p-8 space-y-6">
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-[#e1b362]/20 to-[#d4a55a]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#e1b362]" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Confirm Purchase</h3>
                        <p class="text-gray-400">Please review your subscription details</p>
                    </div>

                    <div class="space-y-4 bg-gray-700/30 rounded-xl p-5 border border-gray-600/30">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Provider</span>
                            <span class="text-white font-medium text-right">{{ $this->providerName }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Package</span>
                            <span
                                class="text-white font-medium text-right max-w-[60%]">{{ $this->selectedBundle['name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Customer</span>
                            <span class="text-white font-medium text-right max-w-[60%]">{{ $customerName }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400 text-sm">Smartcard No.</span>
                            <span class="text-white font-medium">{{ $cardNumber }}</span>
                        </div>
                        <div class="pt-4 border-t border-gray-600/50">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-300 font-medium">Total Amount</span>
                                <span
                                    class="text-2xl font-bold text-[#e1b362]">₦{{ number_format($amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showConfirmationModal = false"
                                class="flex-1 px-6 py-3.5 bg-gray-700/50 text-gray-300 font-medium rounded-xl hover:bg-gray-700/70 focus:outline-none focus:ring-2 focus:ring-gray-600/50 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="button" wire:click="proceedToPin"
                                class="flex-1 px-6 py-3.5 bg-gradient-to-r from-[#e1b362] to-[#d4a55a] text-gray-900 font-semibold rounded-xl hover:from-[#d4a55a] hover:to-[#c59952] focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4"/>
                            </svg>
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- PIN Confirmation Modal -->
    <div x-show="showPinModal" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="bg-gradient-to-br from-gray-800 to-gray-800/95 backdrop-blur-xl rounded-3xl w-full max-w-sm border border-gray-700/50 shadow-2xl overflow-hidden">
            <div class="p-8 space-y-6">
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-6V7a2 2 0 00-2-2H8a2 2 0 00-2 2v4m8 0V7a2 2 0 00-2-2H8a2 2 0 00-2 2v4m8 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Enter PIN</h3>
                    <p class="text-gray-400">Please enter your 4-digit transaction PIN</p>
                </div>

                <div class="space-y-4">
                    <div class="relative">
                        <input
                            type="password"
                            wire:model.live="pin"
                            placeholder="••••"
                            maxlength="4"
                            class="w-full px-4 py-4 bg-gray-700/50 border border-gray-600/50 rounded-xl text-gray-200 text-center text-2xl font-bold tracking-widest placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e1b362]/50 focus:border-[#e1b362]/50 transition-all duration-200"
                            x-ref="pinInput"
                            x-on:input="
                                $event.target.value = $event.target.value.replace(/[^0-9]/g, '');
                                if ($event.target.value.length === 4) {
                                    $event.target.blur();
                                }
                            "
                            x-on:keydown.enter="$wire.purchaseSubscription()"
                        >
                    </div>

                    @error('pin')
                    <p class="text-red-400 text-sm text-center">{{ $message }}</p>
                    @enderror

                    <div class="text-center">
                        <p class="text-xs text-gray-500">
                            Enter your 4-digit PIN to authorize this transaction
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="showPinModal = false"
                            class="flex-1 px-6 py-3.5 bg-gray-700/50 text-gray-300 font-medium rounded-xl hover:bg-gray-700/70 focus:outline-none focus:ring-2 focus:ring-gray-600/50 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="purchaseSubscription" wire:loading.attr="disabled"
                            wire:target="purchaseSubscription"
                            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                            :disabled="!$wire.pin || $wire.pin.length !== 4">
                        <span wire:loading.remove wire:target="purchaseSubscription">Pay</span>
                        <span wire:loading wire:target="purchaseSubscription" class="flex items-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
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

            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
            }

            .scrollbar-thumb-gray-700::-webkit-scrollbar-thumb {
                background-color: rgb(55, 65, 81);
                border-radius: 3px;
            }

            .scrollbar-thumb-gray-700::-webkit-scrollbar-thumb:hover {
                background-color: rgb(75, 85, 99);
            }

            .scrollbar-track-transparent::-webkit-scrollbar-track {
                background: transparent;
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
