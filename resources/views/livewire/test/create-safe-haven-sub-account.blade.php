<div
    class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <x-logo class="w-[220px] h-[100px] mx-auto mb-6"/>
            <h1 class="text-3xl font-bold text-white mb-2">SafeHaven Sub Account</h1>
            <p class="text-gray-400">Create your sub account in just a few steps</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center {{ $step >= 1 ? 'text-blue-500' : 'text-gray-500' }}">
                    <div
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center {{ $step >= 1 ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-500' }}">
                        @if($step > 1)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-sm font-semibold">1</span>
                        @endif
                    </div>
                    <span class="ml-2 text-sm font-medium">BVN</span>
                </div>

                <div class="flex-1 h-1 mx-2 {{ $step >= 2 ? 'bg-blue-500' : 'bg-gray-600' }}"></div>

                <div class="flex items-center {{ $step >= 2 ? 'text-blue-500' : 'text-gray-500' }}">
                    <div
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center {{ $step >= 2 ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-500' }}">
                        @if($step > 2)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-sm font-semibold">2</span>
                        @endif
                    </div>
                    <span class="ml-2 text-sm font-medium">OTP</span>
                </div>

                <div class="flex-1 h-1 mx-2 {{ $step >= 3 ? 'bg-blue-500' : 'bg-gray-600' }}"></div>

                <div class="flex items-center {{ $step >= 3 ? 'text-blue-500' : 'text-gray-500' }}">
                    <div
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center {{ $step >= 3 ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-500' }}">
                        @if($step > 3)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-sm font-semibold">3</span>
                        @endif
                    </div>
                    <span class="ml-2 text-sm font-medium">Details</span>
                </div>
            </div>
        </div>

        <!-- Card Container -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-8 border border-gray-700">
            <!-- Error Alert -->
            @if($error)
                <div class="mb-6 bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-lg flex items-start"
                     role="alert">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">{{ $error }}</span>
                </div>
            @endif

            <!-- Success Alert -->
            @if(session('success'))
                <div
                    class="mb-6 bg-green-900/50 border border-green-500 text-green-200 px-4 py-3 rounded-lg flex items-start"
                    role="alert">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Step 1: BVN Input -->
            @if($step === 1)
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Enter Your BVN</h2>
                    <p class="text-gray-400 text-sm mb-6">We need to verify your Bank Verification Number to
                        proceed.</p>

                    <form wire:submit.prevent="submitBvn">
                        <div class="mb-6">
                            <label for="bvn" class="block text-sm font-medium text-gray-300 mb-2">
                                Bank Verification Number (BVN)
                            </label>
                            <input
                                type="text"
                                id="bvn"
                                wire:model="bvn"
                                maxlength="11"
                                placeholder="Enter 11-digit BVN"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                            @if($loading) disabled @endif
                        >
                            @if($loading)
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            @else
                                Continue
                            @endif
                        </button>
                    </form>
                </div>
            @endif

            <!-- Step 2: OTP Verification -->
            @if($step === 2)
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Verify OTP</h2>
                    <p class="text-gray-400 text-sm mb-6">Enter the OTP sent to your registered phone number.</p>

                    <form wire:submit.prevent="submitOtp">
                        <div class="mb-6">
                            <label for="otp" class="block text-sm font-medium text-gray-300 mb-2">
                                One-Time Password (OTP)
                            </label>
                            <input
                                type="text"
                                id="otp"
                                wire:model="otp"
                                maxlength="6"
                                placeholder="Enter 6-digit OTP"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                            <p class="mt-2 text-xs text-gray-400">
                                Didn't receive OTP? Dial *347*238#
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                            @if($loading) disabled @endif
                        >
                            @if($loading)
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Verifying...
                            @else
                                Verify OTP
                            @endif
                        </button>
                    </form>
                </div>
            @endif

            <!-- Step 3: Account Details -->
            @if($step === 3)
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Complete Your Profile</h2>
                    <p class="text-gray-400 text-sm mb-6">Review and complete your account information.</p>

                    <form wire:submit.prevent="submitSubAccount" class="space-y-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-300 mb-2">
                                First Name
                            </label>
                            <input
                                type="text"
                                id="firstName"
                                wire:model="firstName"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>

                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-300 mb-2">
                                Last Name
                            </label>
                            <input
                                type="text"
                                id="lastName"
                                wire:model="lastName"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                                Phone Number
                            </label>
                            <input
                                type="tel"
                                id="phone"
                                wire:model="phone"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center mt-6"
                            @if($loading) disabled @endif
                        >
                            @if($loading)
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating Account...
                            @else
                                Create Sub Account
                            @endif
                        </button>
                    </form>
                </div>
            @endif

            <!-- Step 4: Success -->
            @if($step === 4)
                <div class="text-center py-8">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Account Created Successfully!</h2>
                    <p class="text-gray-400 mb-8">Your SafeHaven sub account has been created and is ready to use.</p>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        Go to Dashboard
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm">
                Need help? <a href="#" class="text-blue-500 hover:text-blue-400">Contact Support</a>
            </p>
        </div>
    </div>
</div>
