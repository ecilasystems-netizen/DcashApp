<div>
    @push('styles')
        <style>

            .brand-gradient {
                background: linear-gradient(135deg, #E1B362 0%, #D4A55A 100%);
            }

            /* Custom switch toggle */
            .switch {
                position: relative;
                display: inline-block;
                width: 40px;
                height: 24px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #4b5563;
                transition: .4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background-color: #E1B362;
            }

            input:checked + .slider:before {
                transform: translateX(16px);
            }
        </style>
    @endpush
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center space-x-4">
                    <button class="p-2 rounded-full bg-gray-800">
                        <i data-lucide="arrow-left"></i>
                    </button>
                    <div>
                        <h2 class="font-bold text-xl text-white">Profile & Settings</h2>
                    </div>
                </div>

                <!-- Desktop Header -->
                <div class="hidden lg:block">
                    <h1 class="text-2xl font-bold text-white">Profile & Settings</h1>
                    <p class="text-gray-400 text-sm mt-1">Manage your account details and preferences.</p>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <button
                        class="bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-all">
                        Discard Changes
                    </button>
                    <button
                        class="brand-gradient text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition-all">
                        Save Changes
                    </button>
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
                    $profileImage = $hasVerifiedKyc ? Storage::url($user->latestKyc->selfie_image) : "https://placehold.co/100x100/374151/E1B362?text={$initials}";
                @endphp

                <div class="relative w-24 h-24 mx-auto mb-4">
                    <img src="{{ $profileImage }}"
                         alt="{{ $user->fname }} {{ $user->lname }}"
                         class="rounded-full w-full h-full object-cover border-4 border-gray-700">
                    {{--                    <button--}}
                    {{--                        class="absolute bottom-0 right-0 bg-[#E1B362] text-white p-1.5 rounded-full hover:opacity-90 transition-all">--}}
                    {{--                        <i data-lucide="camera" class="w-4 h-4"></i>--}}
                    {{--                    </button>--}}
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
                        @if($user)
                            <span class="flex items-center gap-1 text-sm text-green-400">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Verified
                            </span>
                            {{--                        @else--}}
                            {{--                            <a href="{{ route('kyc.start') }}" class="text-sm text-[#E1B362] hover:underline">Verify--}}
                            {{--                                Now</a>--}}
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="user-check" class="w-5 h-5 text-gray-400"></i>
                            <span class="text-sm">Identity (KYC)</span>
                        </div>
                        @if($user->latestKyc)
                            @if($user->latestKyc->status === 'pending')
                                <span class="flex items-center gap-1 text-sm text-yellow-400">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Pending
                                </span>
                            @else

                                <span class="flex items-center gap-1 text-sm text-green-400">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Verified
                                </span>
                            @endif
                        @else
                            <a href="{{ route('kyc.start') }}" class="text-sm text-[#E1B362] hover:underline">Verify
                                Now</a>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-gray-400"></i>
                            <span class="text-sm">Address</span>
                        </div>
                        @if($user->latestKyc)
                            @if($user->latestKyc->status === 'pending')
                                <span class="flex items-center gap-1 text-sm text-yellow-400">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Pending
                                </span>
                            @else

                                <span class="flex items-center gap-1 text-sm text-green-400">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Verified
                                </span>
                            @endif
                        @else
                            <a href="{{ route('kyc.start') }}" class="text-sm text-[#E1B362] hover:underline">Verify
                                Now</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h4 class="font-bold text-white mb-6">Personal Information</h4>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <label for="fullName" class="block text-gray-400 mb-2">Full Name</label>
                        <input type="text" id="fullName" value="{{ $user->fname }} {{ $user->lname }}"
                               class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    </div>
                    <div>
                        <label for="email" class="block text-gray-400 mb-2">Email Address</label>
                        <input type="email" id="email" value="{{ $user->email }}"
                               class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                               disabled>
                    </div>
                    <div>
                        <label for="phone" class="block text-gray-400 mb-2">Phone Number</label>
                        <input type="tel" id="phone" value="{{ $user->phone ?? 'Not Submitted' }}"
                               class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    </div>
                    <div>
                        <label for="phone" class="block text-gray-400 mb-2">Country</label>
                        <input type="tel" id="phone" value="{{ $user->latestKyc->nationality ?? 'Not Submitted' }}"
                               class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                    </div>
                    {{--                    <div>--}}
                    {{--                        <label for="country" class="block text-gray-400 mb-2">Country</label>--}}
                    {{--                        <select id="country"--}}
                    {{--                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">--}}
                    {{--                            <option value="{{ $user->country }}" selected>{{ $user->country }}</option>--}}
                    {{--                            <option value="Nigeria">Nigeria</option>--}}
                    {{--                            <option value="Ghana">Ghana</option>--}}
                    {{--                            <option value="Kenya">Kenya</option>--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}
                </form>
            </div>

            <!-- Security Settings -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h4 class="font-bold text-white mb-6">Security</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-white">Change Password</p>
                            <p class="text-gray-400">Last changed on Jun 15, 2025</p>
                        </div>
                        <button
                            class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                            Change
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-white">Two-Factor Authentication (2FA)</p>
                            <p class="text-gray-400">Secure your account with an extra layer of protection.</p>
                        </div>
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                            Enable
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h4 class="font-bold text-white mb-6">Notifications</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-white">Email Notifications</p>
                            <p class="text-gray-400">Get emails about new logins, transactions, and promotions.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    {{--                    <div class="flex items-center justify-between">--}}
                    {{--                        <div>--}}
                    {{--                            <p class="font-semibold text-white">Push Notifications</p>--}}
                    {{--                            <p class="text-gray-400">Receive real-time alerts on your mobile device.</p>--}}
                    {{--                        </div>--}}
                    {{--                        <label class="switch">--}}
                    {{--                            <input type="checkbox">--}}
                    {{--                            <span class="slider"></span>--}}
                    {{--                        </label>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

</div>
