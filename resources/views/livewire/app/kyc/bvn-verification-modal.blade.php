<div>
    @if ($showModal)
        <div x-data="bvnModal()" x-init="init()"
             @bvn-initiated.window="handleBvnInitiated()"
             @bvn-verified.window="handleBvnVerified()"
             class="fixed inset-0 z-50 flex items-center justify-center">

            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"
                 @click="$wire.closeModal()"></div>

            <!-- Modal Panel -->
            <div
                class="relative w-full max-w-md bg-gray-900 rounded-2xl shadow-xl border border-gray-800 overflow-hidden">

                <!-- Progress Bar -->
                <div class="absolute top-0 left-0 h-1 bg-gray-800 w-full" x-show="step < 5">
                    <div
                        class="h-full bg-gradient-to-r from-[#E1B362] to-amber-500 transition-all duration-500 ease-out"
                        :style="`width: ${(step / 4) * 100}%`"></div>
                </div>

                <!-- Close Button -->
                <button wire:click="closeModal"
                        class="absolute top-4 right-4 text-gray-400 hover:text-white transition z-10 p-1.5 hover:bg-gray-800 rounded-full">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="p-6">
                    <!-- Step 1: Welcome -->
                    <div x-show="step === 1"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="text-center py-4">

                        <!-- Animated Wallet Icon -->
                        <div class="relative w-24 h-24 mx-auto mb-6">
                            <div
                                class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/20 to-green-600/20 animate-ping"></div>
                            <div
                                class="absolute inset-2 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-pulse"></div>
                            <div
                                class="absolute inset-3 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-110 transition-transform duration-300">
                                <i data-lucide="wallet" class="w-10 h-10 text-white"></i>
                            </div>
                            <div
                                class="absolute -top-1 -right-1 w-9 h-9 bg-green-700 rounded-full flex items-center justify-center animate-bounce border-2 border-gray-900">
                                <span class="text-white text-sm font-bold">₦</span>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-3">Welcome to Your Naira Wallet</h3>
                        <p class="text-gray-400 mb-8 px-4">
                            Create your virtual Nigerian bank account to enjoy seamless deposits, instant transfers,
                            and convenient bill payments.
                        </p>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-8">
                            <div
                                class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 border border-blue-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                                <div
                                    class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i data-lucide="building-2" class="w-6 h-6 text-blue-400"></i>
                                </div>
                                <h4 class="font-semibold text-white text-sm mb-1">Bank Transfers</h4>
                                <p class="text-xs text-gray-400">To any Nigerian bank</p>
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-500/10 to-purple-600/10 border border-purple-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                                <div
                                    class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i data-lucide="smartphone" class="w-6 h-6 text-purple-400"></i>
                                </div>
                                <h4 class="font-semibold text-white text-sm mb-1">Bill Payments</h4>
                                <p class="text-xs text-gray-400">Airtime, data & utilities</p>
                            </div>

                            <div
                                class="bg-gradient-to-br from-green-500/10 to-green-600/10 border border-green-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                                <div
                                    class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i data-lucide="credit-card" class="w-6 h-6 text-green-400"></i>
                                </div>
                                <h4 class="font-semibold text-white text-sm mb-1">Virtual Account</h4>
                                <p class="text-xs text-gray-400">Instant activation</p>
                            </div>

                            <div
                                class="bg-gradient-to-br from-yellow-500/10 to-amber-600/10 border border-yellow-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                                <div
                                    class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i data-lucide="shield-check" class="w-6 h-6 text-yellow-400"></i>
                                </div>
                                <h4 class="font-semibold text-white text-sm mb-1">Bank-Level Security</h4>
                                <p class="text-xs text-gray-400">Protected & encrypted</p>
                            </div>
                        </div>

                        <button @click="step = 2"
                                class="w-full py-3.5 bg-gradient-to-r from-[#E1B362] to-amber-500 hover:from-amber-500 hover:to-[#E1B362] text-white rounded-xl font-bold transition-all shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2">
                            Get Started
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </button>
                    </div>


                    <!-- Step 2: Terms & Conditions -->
                    <div x-show="step === 2" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="py-2">

                        <div class="flex items-center gap-3 mb-6">
                            <button @click="step = 1" class="p-1.5 hover:bg-gray-800 rounded-full transition">
                                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-400"></i>
                            </button>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white">Terms & Conditions</h3>
                                <p class="text-xs text-gray-400">Please review before proceeding</p>
                            </div>
                        </div>

                        <!-- Terms Content -->
                        <div
                            class="bg-gray-800/50 rounded-xl p-4 border border-gray-700/50 h-64 overflow-y-auto mb-6 space-y-4 custom-scrollbar">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-blue-500/10 rounded-lg flex-shrink-0">
                                    <i data-lucide="shield" class="w-5 h-5 text-blue-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white mb-1">Security & Privacy</h4>
                                    <p class="text-sm text-gray-400">Your personal information and BVN are encrypted and
                                        stored securely. We comply with CBN regulations and NDPR privacy laws.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-green-500/10 rounded-lg flex-shrink-0">
                                    <i data-lucide="wallet" class="w-5 h-5 text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white mb-1">Virtual Account Services</h4>
                                    <p class="text-sm text-gray-400">Access to Nigerian Naira virtual accounts powered
                                        by SafeHaven Bank. You can receive deposits and make transfers instantly.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-purple-500/10 rounded-lg flex-shrink-0">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-purple-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white mb-1">BVN Requirement</h4>
                                    <p class="text-sm text-gray-400">BVN is mandatory per CBN policy for virtual account
                                        issuance. We verify your identity but cannot access your bank funds.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-yellow-500/10 rounded-lg flex-shrink-0">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-yellow-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white mb-1">Transaction Finality</h4>
                                    <p class="text-sm text-gray-400">All transactions are final. Please verify recipient
                                        details before confirming transfers.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox -->
                        <div class="flex items-start gap-3 mb-6">
                            <input type="checkbox" id="terms" x-model="termsAccepted"
                                   class="mt-1 w-4 h-4 rounded border-gray-600 bg-gray-700 text-[#E1B362] focus:ring-[#E1B362] focus:ring-offset-gray-900">
                            <label for="terms" class="text-sm text-gray-300 cursor-pointer">
                                I have read and agree to the <span
                                    class="text-[#E1B362] font-medium">Terms of Service</span> and <span
                                    class="text-[#E1B362] font-medium">Privacy Policy</span>.
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3">
                            <button @click="step = 1"
                                    class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-medium transition-colors">
                                Back
                            </button>
                            <button @click="if(termsAccepted) step = 3" :disabled="!termsAccepted"
                                    :class="{ 'opacity-50 cursor-not-allowed': !termsAccepted }"
                                    class="flex-1 py-3 bg-gradient-to-r from-[#E1B362] to-amber-500 hover:from-amber-500 hover:to-[#E1B362] text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                                Accept & Continue
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: BVN Verification -->
                    <div x-show="step === 3" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="py-2">

                        <div class="flex items-center gap-3 mb-6">
                            <button @click="step = 2" class="p-1.5 hover:bg-gray-800 rounded-full transition">
                                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-400"></i>
                            </button>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white">Identity Verification</h3>
                                <p class="text-xs text-gray-400">Verify your Bank Verification Number</p>
                            </div>
                        </div>

                        @if ($errorMessage)
                            <div
                                class="mb-4 p-4 bg-red-500/10 border border-red-500/30 rounded-lg flex items-start gap-2 animate-shake">
                                <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                                <p class="text-red-400 text-sm">{{ $errorMessage }}</p>
                            </div>
                        @endif

                        <form wire:submit.prevent="initiateBvnVerification" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-[#E1B362]"></i>
                                    Bank Verification Number (BVN)
                                </label>
                                <div class="relative">
                                    <input wire:model="bvn" type="text" maxlength="11" inputmode="numeric"
                                           placeholder="Enter your 11-digit BVN"
                                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3.5 text-white focus:border-[#E1B362] focus:ring-2 focus:ring-[#E1B362]/50 outline-none transition placeholder-gray-500"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11)">
                                </div>
                                @error('bvn')
                                <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4">
                                <div class="flex gap-3">
                                    <i data-lucide="info" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5"></i>
                                    <div class="space-y-2">
                                        <p class="text-sm text-blue-300">
                                            Dial <code
                                                class="bg-blue-500/20 px-2 py-0.5 rounded text-blue-200 font-mono">*565*0#</code>
                                            on your registered phone to retrieve your BVN.
                                        </p>
                                        <p class="text-xs text-blue-400/80">
                                            Your BVN is only used for identity verification in compliance with CBN
                                            regulations. We cannot access your bank funds.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="initiateBvnVerification"
                                    class="w-full py-3.5 bg-gradient-to-r from-[#E1B362] to-amber-500 hover:from-amber-500 hover:to-[#E1B362] disabled:from-gray-700 disabled:to-gray-700 disabled:text-gray-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2">
                                            <span wire:loading.remove wire:target="initiateBvnVerification"
                                                  class="flex items-center gap-2">
                                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                                Verify BVN
                                            </span>
                                <span wire:loading wire:target="initiateBvnVerification"
                                      class="flex items-center gap-2">
                                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4" fill="none"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Verifying...
                                            </span>
                            </button>
                        </form>
                    </div>

                    <!-- Step 4: Phone & DOB Confirmation -->
                    <div x-show="step === 4" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="py-2">

                        <div class="flex items-center gap-3 mb-6">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white">Confirm Your Details</h3>
                                <p class="text-xs text-gray-400">Verify phone number and date of birth</p>
                            </div>
                        </div>

                        @if ($successMessage)
                            <div
                                class="mb-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                                <p class="text-green-400 text-sm">{{ $successMessage }}</p>
                            </div>
                        @endif

                        @if ($errorMessage)
                            <div
                                class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-lg flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                                <p class="text-red-400 text-sm">{{ $errorMessage }}</p>
                            </div>
                        @endif

                        <form wire:submit.prevent="confirmAndComplete" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4 text-[#E1B362]"></i>
                                    Phone Number
                                </label>
                                <input wire:model="phoneNumber" type="tel" maxlength="11" inputmode="numeric"
                                       placeholder="08012345678"
                                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3.5 text-white focus:border-[#E1B362] focus:ring-2 focus:ring-[#E1B362]/50 outline-none transition"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11)">
                                @error('phoneNumber')
                                <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                                @if ($phoneSuffix)
                                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                        <i data-lucide="info" class="w-3 h-3"></i>
                                        Must match the number linked to your BVN (ends in <span
                                            class="text-[#E1B362] font-mono">{{ $phoneSuffix }}</span>)
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-[#E1B362]"></i>
                                    Date of Birth
                                </label>
                               <input wire:model="dateOfBirth" type="date" max="{{ date('Y-m-d') }}"
                                              class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-3 sm:px-4 sm:py-3.5 text-white text-sm focus:border-[#E1B362] focus:ring-2 focus:ring-[#E1B362]/50 outline-none transition [color-scheme:dark]">
                                @error('dateOfBirth')
                                <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-4">
                                <div class="flex gap-3">
                                    <i data-lucide="shield" class="w-5 h-5 text-gray-400 flex-shrink-0"></i>
                                    <p class="text-xs text-gray-400">
                                        Your information is encrypted and stored securely in compliance with banking
                                        regulations.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" wire:click="resetBvnEntry" @click="step = 3"
                                        class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                    Back
                                </button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="confirmAndComplete"
                                        class="flex-1 py-3 bg-gradient-to-r from-[#E1B362] to-amber-500 hover:from-amber-500 hover:to-[#E1B362] disabled:from-gray-700 disabled:to-gray-700 disabled:text-gray-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2">
                                                <span wire:loading.remove wire:target="confirmAndComplete"
                                                      class="flex items-center gap-2">
                                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                                    Complete Setup
                                                </span>
                                    <span wire:loading wire:target="confirmAndComplete" class="flex items-center gap-2">
                                                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                stroke="currentColor" stroke-width="4"
                                                                fill="none"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 5: Success -->
                    <div x-show="step === 5" x-cloak
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 scale-90"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="text-center py-6">

                        <!-- Success Animation -->
                        <div class="relative w-24 h-24 mx-auto mb-6">
                            <div
                                class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-ping"></div>
                            <div
                                class="absolute inset-3 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-2xl animate-bounce">
                                <i data-lucide="check" class="w-12 h-12 text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-2">Wallet Created Successfully!</h3>
                        <p class="text-gray-400 mb-8 text-sm px-4">Your virtual account is ready. You can now fund your
                            wallet and start transacting.</p>

                        <!-- Account Details Card -->
                        <div
                            class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 rounded-2xl p-6 space-y-5 border border-gray-700/50 text-left relative overflow-hidden backdrop-blur-sm mb-8">
                            <div
                                class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-[#E1B362]/10 rounded-full blur-3xl"></div>

                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2 flex items-center gap-2">
                                    <i data-lucide="building-2" class="w-3 h-3"></i>
                                    Bank Name
                                </p>
                                <p class="text-white font-medium text-lg">{{ $createdAccountDetails['bankName'] ?? 'SafeHaven Bank' }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-700/50">
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2 flex items-center gap-2">
                                    <i data-lucide="hash" class="w-3 h-3"></i>
                                    Account Number
                                </p>
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-[#E1B362] font-mono font-bold text-2xl tracking-widest">{{ $createdAccountDetails['accountNumber'] ?? 'N/A' }}</p>
                                    <button
                                        onclick="navigator.clipboard.writeText('{{ $createdAccountDetails['accountNumber'] ?? '' }}'); this.querySelector('span').textContent = 'Copied!'; setTimeout(() => this.querySelector('span').textContent = 'Copy', 2000)"
                                        class="text-gray-400 hover:text-white transition p-2 hover:bg-gray-700/50 rounded-lg group"
                                        title="Copy account number">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                        <span class="text-xs ml-1 hidden group-hover:inline">Copy</span>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-700/50">
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2 flex items-center gap-2">
                                    <i data-lucide="user" class="w-3 h-3"></i>
                                    Account Name
                                </p>
                                <p class="text-white font-medium text-lg">{{ $createdAccountDetails['accountName'] ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <button wire:click="closeModalAndNavigate"
                                class="w-full py-4 bg-gradient-to-r from-[#E1B362] to-amber-500 hover:from-amber-500 hover:to-[#E1B362] text-white rounded-xl font-bold transition-all shadow-xl shadow-amber-500/30 flex items-center justify-center gap-2">
                            <i data-lucide="rocket" class="w-5 h-5"></i>
                            Start Transacting
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        Alpine.data('bvnModal', () => ({
            step: 1,
            termsAccepted: false,

            init() {
                // Sync step with Livewire state
                if (@js($isCompleted)) {
                    this.step = 5;
                } else if (@js($bvnInitiated)) {
                    this.step = 4;
                }

                this.initializeLucideIcons();
            },

            handleBvnInitiated() {
                this.step = 4;
                this.$nextTick(() => this.initializeLucideIcons());
            },

            handleBvnVerified() {
                this.step = 5;
                this.$nextTick(() => this.initializeLucideIcons());
            },

            initializeLucideIcons() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }));
    </script>
    @endscript

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(225, 179, 98, 0.5);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(225, 179, 98, 0.7);
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            75% {
                transform: translateX(5px);
            }
        }

        .animate-shake {
            animation: shake 0.3s ease-in-out;
        }
    </style>
</div>
