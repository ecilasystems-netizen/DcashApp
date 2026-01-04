<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center"
             x-data="{ currentStep: 1 }">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"
                 x-show="true"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
            </div>

            {{-- Modal Panel --}}
            <div class="relative w-full sm:max-w-2xl transform transition-all
                                         sm:rounded-2xl bg-gray-900 shadow-2xl border border-gray-800
                                         h-[85vh] sm:h-auto overflow-hidden"
                 x-show="true"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                 x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
                 x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0">

                {{-- Header --}}
                <div class="sticky top-0 z-10 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800">
                    <div class="flex items-center gap-4 p-4 sm:p-5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg">
                            <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-white truncate"
                                x-text="currentStep === 1 ? 'Welcome to Your Wallet' : (currentStep === 2 ? 'Terms & Conditions' : 'Complete Your Profile')">
                            </h3>
                            <p class="text-sm text-gray-400"
                               x-text="currentStep === 1 ? 'Discover what you can do' : (currentStep === 2 ? 'Please review our terms' : 'Fill in your details to continue')">
                            </p>
                        </div>
                        <button wire:click="closeModal"
                                class="rounded-full p-2 text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Progress Steps --}}
                    <div class="flex px-4 sm:px-5 pb-4">
                        <div class="w-full bg-gray-800 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-yellow-500 to-amber-600 h-2 rounded-full transition-all duration-500"
                                :style="{ width: (currentStep / 3) * 100 + '%' }">
                            </div>
                        </div>
                    </div>

                    {{-- Step Indicators --}}
                    <div class="flex justify-between px-4 sm:px-5 pb-4">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium transition-all"
                                :class="currentStep >= 1 ? 'bg-yellow-500 text-white' : 'bg-gray-700 text-gray-400'">
                                1
                            </div>
                            <span class="text-xs text-gray-400">Welcome</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium transition-all"
                                :class="currentStep >= 2 ? 'bg-yellow-500 text-white' : 'bg-gray-700 text-gray-400'">
                                2
                            </div>
                            <span class="text-xs text-gray-400">Terms</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium transition-all"
                                :class="currentStep >= 3 ? 'bg-yellow-500 text-white' : 'bg-gray-700 text-gray-400'">
                                3
                            </div>
                            <span class="text-xs text-gray-400">Profile</span>
                        </div>
                    </div>
                </div>

                {{-- Scrollable Content --}}
                <div class="overflow-y-auto h-[calc(100%-16rem)]">
                    {{-- Step 1: Welcome --}}
                    <div x-show="currentStep === 1"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="h-full flex flex-col p-4 overflow-hidden">

                        {{-- Compact Animated Welcome Section --}}
                        <div class="text-center mb-4">
                            {{-- Naira Wallet Animation --}}
                            <div class="relative w-20 h-20 mx-auto mb-4">
                                <!-- Background circles with pulse animation -->
                                <div
                                    class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/20 to-green-600/20 animate-ping"></div>
                                <div
                                    class="absolute inset-1 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-pulse"></div>

                                <!-- Main wallet container -->
                                <div
                                    class="absolute inset-2 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-xl transform transition-transform duration-1000 hover:scale-110">
                                    <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
                                </div>

                                <!-- Floating Naira symbol -->
                                <div
                                    class="absolute -top-1 -right-1 w-8 h-8 bg-green-700 rounded-full flex items-center justify-center animate-bounce border-2 border-white">
                                    <span class="text-white text-sm font-bold">₦</span>
                                </div>

                                <!-- Nigerian flag colors accent -->
                                <div
                                    class="absolute -bottom-1 -left-1 w-6 h-6 bg-green-600 rounded-full flex items-center justify-center animate-bounce"
                                    style="animation-delay: 0.5s;">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>

                            <h2 class="text-xl font-bold text-white mb-2">Your Naira Wallet Awaits</h2>
                            <p class="text-sm text-gray-400">Get your virtual Nigerian bank account instantly</p>
                        </div>

                        {{-- Feature Cards Grid --}}
                        <div class="flex-1 grid grid-cols-2 gap-2 h-full max-h-64">
                            <div
                                class="bg-gradient-to-r from-green-500/10 to-green-600/10 border border-green-500/20 rounded-lg p-3">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="credit-card" class="w-4 h-4 text-green-400"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-sm">Virtual Account</h4>
                                    <p class="text-xs text-gray-400">Get account number</p>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-blue-500/10 to-blue-600/10 border border-blue-500/20 rounded-lg p-3">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="building-2" class="w-4 h-4 text-blue-400"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-sm">Bank Transfers</h4>
                                    <p class="text-xs text-gray-400">To any Nigerian bank</p>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-purple-500/10 to-purple-600/10 border border-purple-500/20 rounded-lg p-3">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="smartphone" class="w-4 h-4 text-purple-400"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-sm">Bills Payment</h4>
                                    <p class="text-xs text-gray-400">Airtime, data & utilities</p>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-yellow-500/10 to-amber-600/10 border border-yellow-500/20 rounded-lg p-3">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-8 h-8 bg-yellow-500/20 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-yellow-400"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-sm">Secure</h4>
                                    <p class="text-xs text-gray-400">Bank-level protection</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Terms & Conditions --}}
                    <div x-show="currentStep === 2"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="p-4 sm:p-5 space-y-4">

                        <div class="bg-gray-800/50 rounded-xl p-4">
                            <details class="group" open>
                                <summary class="flex items-center justify-between cursor-pointer list-none">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="scroll-text" class="w-5 h-5 text-yellow-500"></i>
                                        <span class="font-medium text-white">Terms & Conditions</span>
                                    </div>
                                    <i data-lucide="chevron-down"
                                       class="w-5 h-5 text-gray-400 transition-transform group-open:rotate-180"></i>
                                </summary>
                                <div class="mt-4 text-sm text-gray-300 space-y-4">
                                    <p>Before proceeding, please review our service terms:</p>

                                    <div class="space-y-3">
                                        <div class="flex gap-3">
                                            <i data-lucide="shield"
                                               class="w-5 h-5 text-yellow-500 flex-shrink-0"></i>
                                            <div>
                                                <h6 class="font-medium text-white">Security & Privacy</h6>
                                                <p class="text-sm text-gray-400">Your personal information and BVN
                                                    details are encrypted and protected with bank-level security</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <i data-lucide="wallet"
                                               class="w-5 h-5 text-yellow-500 flex-shrink-0"></i>
                                            <div>
                                                <h6 class="font-medium text-white">Virtual Account Services</h6>
                                                <p class="text-sm text-gray-400">Access to Nigerian Naira virtual
                                                    account for deposits, transfers, and bill payments</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <i data-lucide="check-circle"
                                               class="w-5 h-5 text-yellow-500 flex-shrink-0"></i>
                                            <div>
                                                <h6 class="font-medium text-white">Transaction Finality</h6>
                                                <p class="text-sm text-gray-400">All transactions are final - verify
                                                    recipient details and amounts before confirming</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>

                    {{-- Step 3: Profile Form OR Progress Loading --}}
                    <div x-show="currentStep === 3"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="p-4 sm:p-5 space-y-4">

                        {{-- Profile Form (shown when not loading) --}}
                        <div wire:loading.remove wire:target="acceptTermsAndCreateWallet">
                            <div class="bg-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-6">
                                    <i data-lucide="user-circle" class="w-5 h-5 text-yellow-500"></i>
                                    <h6 class="font-medium text-white">Complete Your Profile</h6>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1.5">Full Name</label>
                                        <input type="text" wire:model.live="fullName"
                                               placeholder="Enter your full name"
                                               class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 transition-colors">
                                        @error('fullName')
                                        <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
                                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div
                                        class="flex items-start gap-2 text-sm text-blue-400 bg-blue-500/10 border border-blue-500/20 p-3 rounded-lg">
                                        <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                        <p>Please enter your full name exactly as it appears on your submitted KYC
                                            documents for verification purposes.</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1.5">Date of Birth</label>
                                        <input type="date" wire:model.live="dateOfBirth"
                                               class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 transition-colors">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1.5">Bank Verification Number
                                            (BVN)</label>
                                        <div class="relative">
                                            <input type="text" wire:model.live="bvn" inputmode="numeric"
                                                   maxlength="11"
                                                   placeholder="Enter your 11-digit BVN"
                                                   class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-500 transition-colors"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11)">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i data-lucide="check" class="w-5 h-5 text-green-500"
                                                   x-show="$wire.bvn?.length === 11"></i>
                                            </div>
                                        </div>
                                        @error('bvn')
                                        <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
                                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <div
                                            class="flex items-start gap-2 text-sm text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 p-3 rounded-lg">
                                            <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                            <p>BVN is required by CBN (Central Bank of Nigeria) policy for all
                                                virtual account number issuance and compliance with banking
                                                regulations.</p>
                                        </div>

                                        <div
                                            class="flex items-start gap-2 text-sm text-gray-400 bg-gray-900/30 p-3 rounded-lg">
                                            <i data-lucide="shield" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                            <p>Your information is encrypted and stored securely in compliance with
                                                banking regulations.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Loading Content (shown when loading) --}}
                        <div wire:loading wire:target="acceptTermsAndCreateWallet"
                             class="flex items-center justify-center min-h-[400px] py-8">
                            <div class="text-center w-full max-w-md mx-auto px-4">
                                {{-- Animated Wallet Icon --}}
                                <div class="relative w-16 h-16 mx-auto mb-6">
                                    <div
                                        class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-pulse"></div>
                                    <div
                                        class="absolute inset-2 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-xl animate-bounce">
                                        <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
                                    </div>
                                </div>

                                {{-- Loading Text --}}
                                <h3 class="text-xl font-bold text-white mb-2">Creating Your Wallet</h3>
                                <p class="text-gray-400 mb-6">Please wait while we set up your virtual
                                    account...</p>

                                {{-- Progress Bar --}}
                                <div class="relative w-full max-w-sm mx-auto">
                                    <div class="w-full bg-gray-800 rounded-full h-2 mb-4">
                                        <div
                                            class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full animate-pulse"
                                            style="width: 100%; animation: progress 3s ease-in-out infinite;">
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">This may take a few moments...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        @keyframes progress {
                            0% {
                                width: 0%;
                            }
                            50% {
                                width: 75%;
                            }
                            100% {
                                width: 100%;
                            }
                        }
                    </style>
                </div>

                {{-- Footer Actions --}}
                <div class="sticky bottom-0 bg-gray-900/95 backdrop-blur-sm border-t border-gray-800 p-4 sm:p-5">
                    <div class="flex justify-between items-center gap-2">
                        <div class="flex gap-2">
                            <button x-show="currentStep > 1" @click="currentStep--"
                                    class="px-3 py-2.5 sm:px-4 rounded-lg text-gray-300 hover:text-white hover:bg-gray-800 border border-gray-700 hover:border-gray-600 transition-colors text-sm">
                                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Back</span>
                            </button>
                            <button wire:click="closeModal"
                                    class="px-3 py-2.5 sm:px-4 rounded-lg text-gray-300 hover:text-white hover:bg-gray-800 border border-gray-700 hover:border-gray-600 transition-colors text-sm">
                                <span class="sm:hidden">✕</span>
                                <span class="hidden sm:inline">Cancel</span>
                            </button>
                        </div>

                        <div class="flex gap-2">
                            <button x-show="currentStep < 3" @click="currentStep++"
                                    class="px-4 py-2.5 sm:px-6 rounded-lg font-medium bg-gradient-to-r from-yellow-500 to-amber-600 text-white hover:from-yellow-600 hover:to-amber-700 transition-all text-sm">
                                <span class="sm:hidden">Next</span>
                                <span class="hidden sm:inline">Next</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1 sm:ml-2"></i>
                            </button>

                            <button x-show="currentStep === 3" wire:click="acceptTermsAndCreateWallet"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2.5 sm:px-6 rounded-lg font-medium bg-gradient-to-r from-yellow-500 to-amber-600 text-white hover:from-yellow-600 hover:to-amber-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all text-sm">
                                                 <span wire:loading.remove wire:target="acceptTermsAndCreateWallet">
                                                     <span class="sm:hidden">Create</span>
                                                     <span class="hidden sm:inline">Create Wallet</span>
                                                 </span>
                                <span wire:loading wire:target="acceptTermsAndCreateWallet"
                                      class="flex items-center gap-2">
                                                     <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                                         <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                 stroke="currentColor"
                                                                 stroke-width="4" fill="none"/>
                                                         <path class="opacity-75" fill="currentColor"
                                                               d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                     </svg>
                                                     <span class="sm:hidden">Creating...</span>
                                                     <span class="hidden sm:inline">Creating Wallet...</span>
                                                 </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        // Re-initialize Lucide icons when the Livewire component updates
        Livewire.hook('morph.updated', ({el, component}) => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Initialize immediately for the first render
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    @endscript
</div>
