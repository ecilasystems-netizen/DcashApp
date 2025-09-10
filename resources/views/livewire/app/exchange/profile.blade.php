<div>

    <x-slot name="header">
        <header class="bg-gray-950 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center space-x-4">
                    <a type="button" class="p-1 rounded-full bg-gray-800" href="{{ route('dashboard') }}">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Profile</p>
                        <h2 class="font-bold text-xl text-white">Settings</h2>
                    </div>
                </div>

                <!-- Desktop Header -->
                <div class="hidden lg:block">
                    <h1 class="text-2xl font-bold text-white">Profile & Settings</h1>
                    <p class="text-gray-400 text-sm mt-1">Manage your account details and preferences.</p>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Profile Card and Verification -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Profile Card -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 text-center">
                @php
                    $user = auth()->user();
                    $hasVerifiedKyc = $user->latestKyc && $user->latestKyc->status === 'approved';
                    $initials = strtoupper(substr($user->fname, 0, 1) . substr($user->lname, 0, 1));
                    $profileImage = $hasVerifiedKyc && $user->latestKyc->selfie_image ? Storage::url($user->latestKyc->selfie_image) : "https://placehold.co/100x100/374151/E1B362?text={$initials}";
                @endphp

                <div class="relative w-24 h-24 mx-auto mb-4">
                    <img src="{{ $profileImage }}"
                         alt="{{ $user->fname }} {{ $user->lname }}"
                         class="rounded-full w-full h-full object-cover border-4 border-gray-700">
                </div>
                <h3 class="text-xl font-bold text-white">{{ $user->fname }} {{ $user->lname }}</h3>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                <p class="text-xs text-gray-500 mt-2">Joined {{ $user->created_at->format('F d, Y') }}</p>
            </div>

            <!-- Verification Status -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h4 class="font-bold text-white mb-4">Account Verification</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            <span class="text-sm">Email</span>
                        </div>
                        <span class="flex items-center gap-1 text-sm text-green-400">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i> Verified
                                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="user-check" class="w-5 h-5 text-gray-400"></i>
                            <span class="text-sm">Identity (KYC)</span>
                        </div>
                        @if($user->latestKyc)
                            @if($user->latestKyc->status === 'pending')
                                <span class="flex items-center gap-1 text-sm text-yellow-400">
                                                    <i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Pending
                                                </span>
                            @elseif($user->latestKyc->status === 'approved')
                                <span class="flex items-center gap-1 text-sm text-green-400">
                                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Verified
                                                </span>
                            @else
                                <a href="{{ route('kyc.start') }}"
                                   class="text-sm text-red-400 hover:underline">Rejected</a>
                            @endif
                        @else
                            <a href="{{ route('kyc.start') }}" class="text-sm text-[#E1B362] hover:underline">Verify
                                Now</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Accordion -->
        <div class="lg:col-span-2">
            <div class="space-y-4" x-data="{ openTab: @entangle('openTab').live }">
                <!-- KYC / Personal Information -->
                <div class="bg-gray-900 border border-gray-700 rounded-lg">
                    <div wire:click="toggleTab('kyc')" class="p-6 cursor-pointer flex justify-between items-center">
                        <h4 class="font-bold text-white text-lg">Personal Information</h4>
                        <i data-lucide="chevron-down"
                           class="w-5 h-5 text-gray-400 transition-transform"
                           :class="openTab === 'kyc' && 'rotate-180'"></i>
                    </div>
                    <div x-show="openTab === 'kyc'" x-collapse class="px-6 pb-6">
                        <form wire:submit.prevent="savePersonalInfo"
                              class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div>
                                <label for="fname" class="block text-gray-400 mb-2">First Name</label>
                                <input wire:model="fname" type="text" id="fname"
                                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('fname') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="lname" class="block text-gray-400 mb-2">Last Name</label>
                                <input wire:model="lname" type="text" id="lname"
                                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('lname') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-gray-400 mb-2">Email Address</label>
                                <input type="email" id="email" value="{{ $user->email }}"
                                       class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-gray-400"
                                       disabled>
                            </div>
                            <div>
                                <label for="phone" class="block text-gray-400 mb-2">Phone Number</label>
                                <input wire:model="phone" type="tel" id="phone"
                                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('phone') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit"
                                        class="brand-gradient text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition-all">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Referrals -->
                <div class="bg-gray-900 border border-gray-700 rounded-lg">
                    <div wire:click="toggleTab('referrals')"
                         class="p-6 cursor-pointer flex justify-between items-center">
                        <h4 class="font-bold text-white text-lg">Referrals</h4>
                        <i data-lucide="chevron-down"
                           class="w-5 h-5 text-gray-400 transition-transform"
                           :class="openTab === 'referrals' && 'rotate-180'"></i>
                    </div>
                    <div x-show="openTab === 'referrals'" x-collapse
                         class="px-6 pb-6">
                        <p class="text-sm text-gray-400 mb-4">Share your referral code to earn rewards.</p>
                        <div class="flex items-center gap-2" x-data="{ code: '{{ $user->referral_code }}' }">
                            <input type="text" readonly :value="code"
                                   class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
                            <button
                                @click="navigator.clipboard.writeText(code); $dispatch('notify', {message: 'Copied to clipboard!', type: 'success'})"
                                class="bg-gray-700 text-white p-2 rounded-lg hover:bg-gray-600">
                                <i data-lucide="copy" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-gray-900 border border-gray-700 rounded-lg">
                    <div wire:click="toggleTab('security')"
                         class="p-6 cursor-pointer flex justify-between items-center">
                        <h4 class="font-bold text-white text-lg">Security</h4>
                        <i data-lucide="chevron-down"
                           class="w-5 h-5 text-gray-400 transition-transform"
                           :class="openTab === 'security' && 'rotate-180'"></i>
                    </div>
                    <div x-show="openTab === 'security'" x-collapse
                         class="px-6 pb-6">
                        <form wire:submit.prevent="changePassword" class="space-y-4 text-sm">
                            <p class="font-semibold text-white">Change Password</p>
                            <div>
                                <label for="current_password" class="block text-gray-400 mb-2">Current Password</label>
                                <input wire:model="current_password" type="password" id="current_password"
                                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('current_password') <span
                                    class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-gray-400 mb-2">New Password</label>
                                <input wire:model="password" type="password" id="password"
                                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('password') <span
                                    class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-gray-400 mb-2">Confirm New
                                    Password</label>
                                <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Action Links -->
            <div class="mt-8 pt-8 border-t border-gray-800 space-y-4 text-sm">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="font-semibold text-[#E1B362] hover:underline">
                            Logout
                        </button>
                    </form>

                    <a href="#" class="font-semibold text-red-500 hover:underline">
                        Delete Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
