<div x-data="{ isModalOpen: false, kycData: null }">
    <x-slot name="header">
        <!-- Header -->
        <header
            class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">KYC Management</h1>
            </div>
        </header>
    </x-slot>

    <div class="p-6">


        @if(session()->has('message'))
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif
        <!-- Statistics -->
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                    <i data-lucide="list-checks" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total KYC</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
                    <i data-lucide="loader-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-green-500/20 text-green-400 rounded-full mr-4">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Completed</p>
                    <p class="text-2xl font-bold text-green-400">{{ $stats['approved'] }}</p>
                </div>
            </div>
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-64">
                <div class="p-3 bg-red-500/20 text-red-400 rounded-full mr-4">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Failed / Rejected</p>
                    <p class="text-2xl font-bold text-red-400">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <select
                wire:model.live="statusFilter"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <input
                wire:model.live="dateFilter"
                type="date"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>

            <div class="relative hidden md:block">
                <i
                    data-lucide="search"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input
                    wire:model.live.debounce.300ms="searchTerm"
                    type="text"
                    placeholder="Search by user or ID..."
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>
        </div>

        <!-- KYC Submissions Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Document Type</th>
                        <th class="px-6 py-3">Date Submitted</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse ($kycVerifications as $kyc)
                        <tr class="hover:bg-gray-700/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-semibold">
                                        {{ substr($kyc->first_name, 0, 1) }}{{ substr($kyc->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white">
                                            {{ $kyc->first_name }} {{ $kyc->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $kyc->user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $kyc->document_type }}</td>
                            <td class="px-6 py-4">{{ $kyc->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="status-pill status-{{ $kyc->status }}">
                                    {{ ucfirst($kyc->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    wire:click="viewKyc({{ $kyc->id }})"
                                    class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-1 px-3 rounded-lg text-xs">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                No KYC verifications found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3">
                {{ $kycVerifications->links() }}
            </div>
        </div>
    </div>

    <!-- KYC Review Modal -->
    @if ($showModal && $selectedKyc)
        <div
            wire:key="kyc-modal-{{ $selectedKyc->id }}"
            class="fixed inset-0 z-30 flex items-center justify-center modal-overlay overflow-y-auto p-4">
            <div
                class="bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl border border-gray-700 max-h-[90vh] flex flex-col">
                <div
                    class="flex justify-between items-center p-4 border-b border-gray-700 flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-white">
                            Reviewing KYC for {{ $selectedKyc->first_name }} {{ $selectedKyc->last_name }}
                        </h3>
                        <p class="text-sm text-gray-400">{{ $selectedKyc->user->email }}</p>
                    </div>
                    <button
                        wire:click="closeModal"
                        class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Submitted Info -->
                        <div class="lg:col-span-1 space-y-4 text-sm">
                            <div>
                                <h4 class="font-semibold text-gray-400">Full Name</h4>
                                <p class="text-white">{{ $selectedKyc->first_name }} {{ $selectedKyc->last_name }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-400">Date of Birth</h4>
                                <p class="text-white">{{ $selectedKyc->date_of_birth->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-400">Address</h4>
                                <p class="text-white">{{ $selectedKyc->address }}</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-400">Nationality</h4>
                                <p class="text-[#E1B362] ">{{ $selectedKyc->nationality ?? 'not available' }}</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-400">Document Type</h4>
                                <p class="text-white">{{ $selectedKyc->document_type }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-400">Document Number</h4>
                                <p class="text-white">{{ $selectedKyc->document_number }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-400">Status</h4>
                                <p class="text-white">
                                    <span class="status-pill status-{{ $selectedKyc->status }}">
                                        {{ ucfirst($selectedKyc->status) }}
                                    </span>
                                </p>
                            </div>
                            @if ($selectedKyc->verified_at)
                                <div>
                                    <h4 class="font-semibold text-gray-400">Verified At</h4>
                                    <p class="text-white">{{ $selectedKyc->verified_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                            @if ($selectedKyc->rejection_reason)
                                <div>
                                    <h4 class="font-semibold text-gray-400">Rejection Reason</h4>
                                    <p class="text-white">{{ $selectedKyc->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Documents - with clickable functionality -->
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ activeImage: null }">
                            <div>
                                <h4 class="font-semibold text-gray-400 mb-2">ID Front</h4>
                                <img
                                    src="{{ asset('storage/' . $selectedKyc->document_front_image) }}"
                                    class="rounded-lg w-full object-cover cursor-pointer hover:opacity-90 transition"
                                    @click="activeImage = '{{ asset('storage/' . $selectedKyc->document_front_image) }}'"/>
                            </div>

                            @if ($selectedKyc->document_back_image)
                                <div>
                                    <h4 class="font-semibold text-gray-400 mb-2">ID Back</h4>
                                    <img
                                        src="{{ asset('storage/' . $selectedKyc->document_back_image) }}"
                                        class="rounded-lg w-full object-cover cursor-pointer hover:opacity-90 transition"
                                        @click="activeImage = '{{ asset('storage/' . $selectedKyc->document_back_image) }}'"/>
                                </div>
                            @endif
                            <div class="md:col-span-2">
                                <h4 class="font-semibold text-gray-400 mb-2">Selfie</h4>
                                <img
                                    src="{{ asset('storage/' . $selectedKyc->selfie_image) }}"
                                    class="rounded-lg w-1/2 mx-auto object-cover cursor-pointer hover:opacity-90 transition"
                                    @click="activeImage = '{{ asset('storage/' . $selectedKyc->selfie_image) }}'"/>
                            </div>

                            <!-- Image viewer modal -->
                            <div
                                x-show="activeImage !== null"
                                x-transition
                                class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
                                @click.self="activeImage = null">
                                <div class="relative max-w-5xl max-h-[90vh] overflow-hidden">
                                    <img
                                        :src="activeImage"
                                        class="max-w-full max-h-[85vh] object-contain"/>
                                    <button
                                        @click="activeImage = null"
                                        class="absolute top-2 right-2 bg-black/60 p-2 rounded-full hover:bg-black/80 text-white">
                                        <i data-lucide="x" class="w-6 h-6"></i>
                                    </button>
                                    <div
                                        class="absolute bottom-0 inset-x-0 bg-black/50 p-4 flex justify-center space-x-4">
                                        <button
                                            @click="window.open(activeImage, '_blank')"
                                            class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg text-sm">
                                            <i data-lucide="external-link" class="w-4 h-4 inline mr-1"></i>
                                            Open in New Tab
                                        </button>
                                        <button
                                            @click="const link = document.createElement('a'); link.href = activeImage; link.download = ''; document.body.appendChild(link); link.click(); document.body.removeChild(link);"
                                            class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg text-sm">
                                            <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                                            Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-700 flex-shrink-0">
                    @if ($selectedKyc->status === 'pending')
                        <div class="flex justify-between items-center">
                            <div class="w-1/2 pr-2">
                                @if ($selectedKyc->status !== 'approved')
                                    <button
                                        wire:click="approveKyc"
                                        class="w-full bg-green-600/80 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg">
                                        Approve
                                    </button>
                                @endif
                            </div>
                            <div class="w-1/2 pl-2">
                                @if ($selectedKyc->status !== 'rejected')
                                    <div x-data="{ showRejectForm: false }">
                                        <button
                                            x-show="!showRejectForm"
                                            @click="showRejectForm = true"
                                            class="w-full bg-red-600/80 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg">
                                            Reject
                                        </button>
                                        <div x-show="showRejectForm" class="mt-4">
                                            <textarea
                                                wire:model="rejectionReason"
                                                placeholder="Provide reason for rejection"
                                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] mb-2"
                                                rows="3"></textarea>
                                            @error('rejectionReason')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                            <div class="flex space-x-2 mt-2">
                                                <button
                                                    wire:click="rejectKyc"
                                                    class="bg-red-600/80 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg">
                                                    Submit
                                                </button>
                                                <button
                                                    @click="showRejectForm = false"
                                                    class="bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <button
                            wire:click="closeModal"
                            class="w-full bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg">
                            Close
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif



    @push('styles')
        <style>
            .status-pill {
                display: inline-block;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .status-approved {
                background-color: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .status-pending {
                background-color: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .status-rejected {
                background-color: rgba(239, 68, 68, 0.1);
                color: #ef4444;
            }

            .modal-overlay {
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(1px);
            }


            /* Add hover effect for clickable images */
            .cursor-pointer {
                transition: transform 0.2s;
            }

            .cursor-pointer:hover {
                transform: scale(1.02);
            }
        </style>
    @endpush
</div>
