<div>
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Edit User</h1>
                <div>
                    <a href="{{ route('admin.users') }}"
                       class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                        Back to Users
                    </a>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if(session()->has('message'))
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <form wire:submit="updateUser">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- First Name -->
                    <div>
                        <label for="fname" class="block text-sm font-medium text-gray-400 mb-1">First Name</label>
                        <input
                            type="text"
                            id="fname"
                            wire:model="fname"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                        @error('fname') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="lname" class="block text-sm font-medium text-gray-400 mb-1">Last Name</label>
                        <input
                            type="text"
                            id="lname"
                            wire:model="lname"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                        @error('lname') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                        <input
                            type="text"
                            id="username"
                            wire:model="username"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                        @error('username') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-400 mb-1">Phone</label>
                        <input
                            type="text"
                            id="phone"
                            wire:model="phone"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                        @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- KYC Status -->
                    <div>
                        <label for="kyc_status" class="block text-sm font-medium text-gray-400 mb-1">KYC Status</label>
                        <select
                            id="kyc_status"
                            wire:model="kyc_status"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        >
                            <option value="rejected">Rejected</option>
                            <option value="verified">Verified</option>
                        </select>
                        @error('kyc_status') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- KYC Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-white mb-4">KYC Information</h2>
                    @if($user->latestKyc)
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-gray-400 text-sm">Status:</span>
                                    <span class="ml-2 text-white">{{ ucfirst($user->latestKyc->status) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm">Verified At:</span>
                                    <span class="ml-2 text-white">
                                        {{ $user->latestKyc->verified_at ? $user->latestKyc->verified_at->format('M d, Y H:i') : 'Not verified' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm">Document Type:</span>
                                    <span class="ml-2 text-white">{{ $user->latestKyc->document_type }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm">Document Number:</span>
                                    <span class="ml-2 text-white">{{ $user->latestKyc->document_number }}</span>
                                </div>
                            </div>

                            @if($user->latestKyc->status === 'rejected')
                                <div class="bg-red-500/10 text-red-400 p-4 rounded-lg mb-4">
                                    <strong>Rejection Reason:</strong> {{ $user->latestKyc->rejection_reason }}
                                </div>
                            @endif

                            <!-- Document Images -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">ID Front</p>
                                    <img
                                        src="{{ asset('storage/' . $user->latestKyc->document_front_image) }}"
                                        alt="ID Front"
                                        class="w-full h-40 object-cover rounded-lg"
                                    >
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">ID Back</p>
                                    <img
                                        src="{{ asset('storage/' . $user->latestKyc->document_back_image) }}"
                                        alt="ID Back"
                                        class="w-full h-40 object-cover rounded-lg"
                                    >
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Selfie</p>
                                    <img
                                        src="{{ asset('storage/' . $user->latestKyc->selfie_image) }}"
                                        alt="Selfie"
                                        class="w-full h-40 object-cover rounded-lg"
                                    >
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-400">No KYC submission found for this user.</p>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-[#E1B362] hover:bg-[#c99c55] text-gray-900 font-semibold rounded-lg"
                    >
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .status-pill {
                @apply px-2 py-1 rounded-full text-xs font-semibold;
            }

            .status-active {
                @apply bg-green-500/20 text-green-400;
            }

            .status-verified {
                @apply bg-blue-500/20 text-blue-400;
            }

            .status-pending {
                @apply bg-yellow-500/20 text-yellow-400;
            }

            .status-rejected {
                @apply bg-red-500/20 text-red-400;
            }

            .status-suspended {
                @apply bg-orange-500/20 text-orange-400;
            }

            .status-blocked {
                @apply bg-gray-500/20 text-gray-400;
            }

            .status-not-submitted {
                @apply bg-gray-500/20 text-gray-400;
            }
        </style>
    @endpush
</div>
