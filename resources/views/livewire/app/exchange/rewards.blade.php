<div>
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center space-x-4">
                    <a type="button" class="p-1 rounded-full bg-gray-800" href="{{ route('dashboard') }}">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Rewards</p>
                        <h2 class="font-bold text-xl text-white">Hub</h2>
                    </div>
                </div>
                <div class="lg:hidden flex items-center space-x-2">
                    <p class="text-sm text-gray-400">Total Points</p>
                    <button
                        class="bg-gray-800 border-2 border-[#E1B362] text-[#E1B362] px-6 py-2 rounded-lg font-semibold hover:bg-[#E1B362]/20 transition-all">
                        0.00
                    </button>
                </div>


                <!-- Desktop Header -->
                <div class="hidden lg:block">

                    <h1 class="text-2xl font-bold text-white">Rewards Hub</h1>
                    <p class="text-gray-400 text-sm mt-1">Complete tasks to earn rewards.</p>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Total Rewards Earned</p>
                        <button
                            class="bg-gray-800 border-2 border-[#E1B362] text-[#E1B362] px-6 py-2 rounded-lg font-semibold hover:bg-[#E1B362]/20 transition-all">
                            0.00
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <!-- Main Content: Rewards Hub -->
    <div class="p-1 lg:p-0 lg:py-8">
        <!-- Featured Reward -->
        <div class="mb-8">
            <div
                class="bg-gray-800 border border-gray-700 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-[#E1B362] transition-all">
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase text-[#E1B362]">Featured Reward</span>
                        <span
                            class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded-full hidden md:inline-block">New Users</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mt-4">Invite Friends, Earn Cash</h3>
                    <p class="text-sm text-gray-400 mt-2">Get up to 500 coins for every friend who signs up and
                        completes
                        their first exchange using the referral code below.</p>
                </div>
                <button
                    class="brand-gradient text-white font-semibold w-full md:w-auto py-2 px-6 rounded-lg hover:opacity-90 transition-all">
                    {{$referralCode}}
                </button>
            </div>
        </div>

        <!-- Tasks to Earn -->
        <div>
            <h3 class="text-lg font-bold text-white mb-4">Tasks to Earn</h3>
            <div class="space-y-4">
                <!-- Task 1 -->
                @if($kycStatus != 'approved')
                    <div
                        class="bg-gray-800 border border-gray-700 rounded-lg p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-purple-500/20 text-purple-400 p-3 rounded-lg">
                                <i data-lucide="user-check"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Complete Your Profile</p>
                                <p class="text-sm text-gray-400">Verify your identity to unlock all features.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <p class="font-semibold text-lg text-green-400 flex-grow md:flex-grow-0">+ ₱50</p>
                            <a href="{{ route('kyc.start') }}"
                               class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all w-full md:w-auto">
                                Verify Now
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Task 2 -->
                <div
                    class="bg-gray-800 border border-gray-700 rounded-lg p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-500/20 text-green-400 p-3 rounded-lg">
                            <i data-lucide="repeat"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Make Your First Exchange</p>
                            <p class="text-sm text-gray-400">Exchange at least ₱1,000 worth of any currency.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <p class="font-semibold text-lg text-green-400 flex-grow md:flex-grow-0">+ ₱100</p>
                        <a href="{{ route('dashboard') }}"
                           class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all w-full md:w-auto">
                            Exchange Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
