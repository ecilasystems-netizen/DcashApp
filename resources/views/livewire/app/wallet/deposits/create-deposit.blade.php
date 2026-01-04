<div>
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
                <a href="{{route('dashboard')}}" class="p-2 rounded-full hover:bg-gray-800">
                    <i data-lucide="arrow-left"></i>
                </a>
                <div>
                    <p class="text-xs text-gray-400">Make</p>
                    <h2 class="font-bold text-xl text-white">Deposits</h2>
                </div>
            </div>
        </header>
    </x-slot>

    @if($walletProvider === 'safehaven')

        <!-- Deposit Details -->
        <div class=" lg:py-8 space-y-8">


            <!-- Instructions -->
            <div class="bg-gray-800/2  border-gray-700 rounded-2xl text-center">
                <h3 class="font-semibold text-[#E1B362] mb-4">Instructions</h3>
                <ul class="space-y-3 text-white text-sm list-disc list-inside bg-gray-800 p-5 rounded-lg">
                    Make a transfer into the account, your wallet will be credited automatically within 2-5 minutes.
                </ul>
            </div>

            <div class="bg-gray-800/2 border-2 border-dashed border-gray-700 rounded-2xl p-6 text-center">
                <h2 class="text-lg font-semibold text-white mb-4">Your Dedicated Account Details</h2>
                <div class="space-y-5">
                    <!-- Account Number -->
                    <div>
                        <p class="text-sm text-gray-400">Account Number</p>
                        <div class="flex items-center justify-center gap-4 mt-1">
                            <p id="account-number" class="text-2xl font-bold text-[#E1B362]">{{$accountNumber}}</p>
                            <button data-copy-target="account-number"
                                    class="copy-btn p-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                                <i data-lucide="copy" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Bank Name -->
                    <div>
                        <p class="text-sm text-gray-400">Bank Name</p>
                        <div class="flex items-center justify-center gap-4 mt-1">
                            <p id="bank-name" class="text-xl font-semibold text-white">{{$bankName}}</p>

                        </div>
                    </div>
                    <!-- Account Name -->
                    <div>
                        <p class="text-sm text-gray-400">Account Name</p>
                        <div class="flex items-center justify-center gap-4 mt-1">
                            <p id="account-name" class="text-xl font-semibold text-white">{{$accountName}}</p>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    @else
        {{-- BVN Verification Modal --}}
        @livewire('app.kyc.bvn-verification-modal')


        <button
            wire:click="$dispatch('openModal')"
            class="w-full px-6 py-3 bg-[#E1B362] hover:bg-orange-400 text-white rounded-lg font-medium transition-colors"
        >
            Generate Deposit Account
        </button>

    @endif

    {{--    limit upgrade request--}}
    <div>
        <!-- Main Content -->
        <div class="mx-auto p-0 md:p-0 mt-5">
            <!-- Current Tier Display -->
            <div class="bg-gray-900 rounded-xl p-6 mb-6 border border-gray-800">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i data-lucide="shield" class="w-6 h-6 text-[#E1B362]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Your Current Tier: {{$currentTier->name}} </h3>
                        <p class="text-sm text-gray-400">Transaction Limits</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-800/50 rounded-lg">
                        <span class="text-gray-300">Daily Limit</span>
                        <span
                            class="font-semibold text-white">₦{{ number_format($currentTier->max_daily_transaction) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-800/50 rounded-lg">
                        <span class="text-gray-300">Balance Limit</span>
                        <span class="font-semibold text-white">₦{{ number_format($currentTier->max_balance) }}</span>
                    </div>
                </div>

                @if($hasExistingRequest)
                    <!-- Existing Request Notice -->
                    <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="clock" class="w-3 h-3 text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-300">Request Under Review</p>
                                <p class="text-xs text-gray-400">Your limit upgrade request is being processed. We'll
                                    notify you once completed.</p>
                            </div>
                        </div>
                    </div>

                    <button disabled
                            class="w-full mt-4 px-4 py-2 bg-gray-600 text-gray-400 rounded-lg font-medium cursor-not-allowed">
                        Request Submitted
                    </button>
                @else
                    @if($currentTier->id == 1)

                        <button
                            wire:click="$set('showLimitIncreaseModal', true)"
                            class="w-full mt-6 px-4 py-2 bg-[#E1B362] hover:bg-orange-400 text-white rounded-lg font-medium transition-colors">
                            Increase Limits
                        </button>
                    @endif

                @endif
            </div>

            <!-- Rest of your deposit form content -->
        </div>

        <!-- Limit Increase Modal -->
        @if($showLimitIncreaseModal && !$hasExistingRequest)
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

                <!-- Modal Panel -->
                <div class="relative w-full max-w-lg bg-gray-900 rounded-2xl shadow-xl border border-gray-800 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Increase Your Limits</h3>
                        <button wire:click="$set('showLimitIncreaseModal', false)"
                                class="text-gray-400 hover:text-white">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                            <p class="text-red-400 text-sm">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form wire:submit.prevent="submitLimitIncrease" class="space-y-4">

                        <!-- Upgrade Info Card -->
                        <div
                            class="bg-gradient-to-r from-[#E1B362]/10 to-orange-500/10 border border-[#E1B362]/30 rounded-xl p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#E1B362]/20 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i data-lucide="trending-up" class="w-4 h-4 text-[#E1B362]"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-[#E1B362] mb-2">New Limit Details</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-300">Daily Limit:</span>
                                            <span class="font-medium text-white">₦5,000,000</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-300">Balance Limit:</span>
                                            <span class="font-medium text-green-400">Unlimited</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">Complete the form below to upgrade your
                                        account
                                        limits.</p>
                                </div>
                            </div>
                        </div>

                        <form wire:submit.prevent="submitLimitIncrease" class="space-y-4">
                            <!-- Occupation -->
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Occupation</label>
                                <select wire:model.live="occupation"
                                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-blue-500">
                                    <option value="">Select your occupation</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="lawyer">Lawyer</option>
                                    <option value="engineer">Engineer</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="nurse">Nurse</option>
                                    <option value="businessman">Businessman/woman</option>
                                    <option value="trader">Trader</option>
                                    <option value="civil_servant">Civil Servant</option>
                                    <option value="banker">Banker</option>
                                    <option value="consultant">Consultant</option>
                                    <option value="contractor">Contractor</option>
                                    <option value="farmer">Farmer</option>
                                    <option value="artisan">Artisan</option>
                                    <option value="student">Student</option>
                                    <option value="retired">Retired</option>
                                    <option value="unemployed">Unemployed</option>
                                    <option value="others">Others</option>
                                </select>
                                @error('occupation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Source of Income -->
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Source of Income</label>
                                <select wire:model.live="sourceOfIncome"
                                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-blue-500">
                                    <option value="">Select source</option>
                                    <option value="salary">Salary</option>
                                    <option value="business">Business Income</option>
                                    <option value="investments">Investments</option>
                                    <option value="others">Others</option>
                                </select>
                                @error('sourceOfIncome') <span
                                    class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submitLimitIncrease"
                                    class="w-full px-6 py-3 bg-[#E1B362] hover:bg-orange-400 disabled:bg-gray-600 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors">
                                <span wire:loading.remove wire:target="submitLimitIncrease">Submit for Review</span>
                                <span wire:loading wire:target="submitLimitIncrease"
                                      class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                            </button>
                        </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

            <!-- Modal Panel -->
            <div
                class="relative w-full max-w-md bg-gray-900 rounded-2xl shadow-xl border border-gray-800 p-6 text-center">
                <!-- Success Icon -->
                <div class="w-16 h-16 mx-auto bg-green-500/20 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-green-400"></i>
                </div>

                <h3 class="text-xl font-bold text-white mb-2">Request Submitted!</h3>
                <p class="text-gray-300 mb-6">Your limit increase request has been submitted successfully. We'll review
                    your application and get back to you within 24-48 hours.</p>

                <button wire:click="$set('showSuccessModal', false)"
                        class="w-full px-6 py-3 bg-[#E1B362] hover:bg-orange-400 text-white rounded-lg font-medium transition-colors">
                    Got it
                </button>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>

            document.addEventListener('DOMContentLoaded', () => {
                const copyButtons = document.querySelectorAll('.copy-btn');

                copyButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const targetId = button.dataset.copyTarget;
                        const targetElement = document.getElementById(targetId);
                        const textToCopy = targetElement.textContent;

                        // Create a temporary textarea to hold the text
                        const textArea = document.createElement('textarea');
                        textArea.value = textToCopy;
                        document.body.appendChild(textArea);
                        textArea.select();

                        try {
                            // Use the Clipboard API
                            document.execCommand('copy');

                            // Visual feedback
                            const originalIcon = button.innerHTML;
                            button.innerHTML = `<i data-lucide="check" class="w-5 h-5 text-green-400"></i>`;
                            lucide.createIcons();

                            setTimeout(() => {
                                button.innerHTML = originalIcon;
                                lucide.createIcons();
                            }, 2000);

                        } catch (err) {
                            console.error('Failed to copy text: ', err);
                        }

                        document.body.removeChild(textArea);
                    });
                });
            });
        </script>
    @endpush
</div>
