<div x-data="{
        suspendModalOpen: false,
        activateModalOpen: false,
        blockModalOpen: false,
        agentId: null,

        showSuspendModal(id) {
            this.agentId = id;
            this.suspendModalOpen = true;
        },
        showActivateModal(id) {
            this.agentId = id;
            this.activateModalOpen = true;
        },
        showBlockModal(id) {
            this.agentId = id;
            this.blockModalOpen = true;
        }
    }">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Agent Management</h1>
                <div class="flex items-center gap-4">
                    <div class="relative hidden md:block">
                        <!-- Search already implemented below -->
                    </div>
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

        <!-- Statistics -->
        <div class="flex space-x-6 mb-6 overflow-x-auto pb-4">
            <!-- Total Agents -->
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-60">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full mr-4">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Agents</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <!-- Active Agents -->
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-60">
                <div class="p-3 bg-green-500/20 text-green-400 rounded-full mr-4">
                    <i data-lucide="user-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
            <!-- Suspended -->
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-60">
                <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
                    <i data-lucide="user-x" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Suspended</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($stats['suspended']) }}</p>
                </div>
            </div>
            <!-- Blocked -->
            <div class="flex items-center bg-gray-800 border border-gray-700 p-4 rounded-lg flex-shrink-0 w-60">
                <div class="p-3 bg-red-500/20 text-red-400 rounded-full mr-4">
                    <i data-lucide="slash" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Blocked</p>
                    <p class="text-2xl font-bold text-red-400">{{ number_format($stats['blocked']) }}</p>
                </div>
            </div>


        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <select wire:model.live="statusFilter"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="blocked">Blocked</option>
            </select>

            <div class="relative md:flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search agents..."
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>

            <select wire:model.live="perPage"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>

        <!-- Agents Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-6 py-3">Agent</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Performance</th>
                    <th class="px-6 py-3">Date Joined</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($agents as $agent)
                    <tr class="hover:bg-gray-700/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-semibold">
                                    {{ substr($agent->fname ?? '', 0, 1) }}{{ substr($agent->lname ?? '', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">
                                        {{ $agent->fname ?? '' }} {{ $agent->lname ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $agent->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($agent->status === 'active')
                                <span class="status-pill status-active">Active</span>
                            @elseif($agent->status === 'suspended')
                                <span class="status-pill status-suspended">Suspended</span>
                            @elseif($agent->status === 'blocked')
                                <span class="status-pill status-blocked">Blocked</span>
                            @else
                                <span class="status-pill">{{ ucfirst($agent->status ?? 'New') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                    <span class="text-sm text-gray-300">{{ number_format($agent->total_transactions) }} Total</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <span class="text-sm text-gray-300">{{ number_format($agent->completed_transactions) }} Completed</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                    <span class="text-sm text-gray-300">{{ number_format($agent->pending_transactions) }} Pending</span>
                                </div>
                            </div>
                        </td>
                       
                        <td class="px-6 py-4">{{ $agent->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                                <div x-show="open" x-cloak @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-lg z-10"
                                     x-transition>
                                    <a href="{{ route('admin.users.edit', $agent->id) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-600">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                        Edit Agent
                                    </a>

                                    @if($agent->status !== 'suspended')
                                        <button @click="showSuspendModal({{ $agent->id }})"
                                                class="flex items-center gap-3 px-4 py-2 text-sm text-yellow-400 hover:bg-gray-600 w-full text-left">
                                            <i data-lucide="pause-circle" class="w-4 h-4"></i>
                                            Suspend
                                        </button>
                                    @else
                                        <button @click="showActivateModal({{ $agent->id }})"
                                                class="flex items-center gap-3 px-4 py-2 text-sm text-green-400 hover:bg-gray-600 w-full text-left">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            Activate
                                        </button>
                                    @endif

                                    @if($agent->status !== 'blocked')
                                        <button @click="showBlockModal({{ $agent->id }})"
                                                class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-gray-600 w-full text-left">
                                            <i data-lucide="slash" class="w-4 h-4"></i>
                                            Block
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                            No agents found matching your criteria
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-700 flex justify-between items-center text-sm">
                <p class="text-gray-400">
                    Showing {{ $agents->firstItem() ?? 0 }} to {{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }}
                    agents
                </p>
                <div class="flex items-center">
                    {{ $agents->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Suspend Agent Modal -->
    <div x-show="suspendModalOpen" x-cloak x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="suspendModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md border border-gray-700">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <i data-lucide="pause-circle" class="h-6 w-6 text-yellow-600"></i>
                    </div>
                </div>
                <h3 class="mb-2 text-lg font-medium text-white">Suspend Agent</h3>
                <p class="mb-5 text-sm text-gray-400">
                    Are you sure you want to suspend this agent? They will no longer be able to handle transactions
                    until reactivated.
                </p>
                <div class="flex justify-center gap-4">
                    <button @click="suspendModalOpen = false"
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button @click="$wire.suspendAgent(agentId); suspendModalOpen = false"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg">
                        Suspend Agent
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Agent Modal -->
    <div x-show="activateModalOpen" x-cloak x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="activateModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md border border-gray-700">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <i data-lucide="check-circle" class="h-6 w-6 text-green-600"></i>
                    </div>
                </div>
                <h3 class="mb-2 text-lg font-medium text-white">Activate Agent</h3>
                <p class="mb-5 text-sm text-gray-400">
                    Are you sure you want to activate this agent? This will allow them to handle transactions again.
                </p>
                <div class="flex justify-center gap-4">
                    <button @click="activateModalOpen = false"
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button @click="$wire.activateAgent(agentId); activateModalOpen = false"
                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                        Activate Agent
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Agent Modal -->
    <div x-show="blockModalOpen" x-cloak x-transition
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="background-color: rgba(0,0,0,0.5);">
        <div @click.away="blockModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md border border-gray-700">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i data-lucide="slash" class="h-6 w-6 text-red-600"></i>
                    </div>
                </div>
                <h3 class="mb-2 text-lg font-medium text-white">Block Agent</h3>
                <p class="mb-5 text-sm text-gray-400">
                    Are you sure you want to block this agent? This action is more severe and should be used for serious
                    violations.
                </p>
                <div class="flex justify-center gap-4">
                    <button @click="blockModalOpen = false"
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button @click="$wire.blockAgent(agentId); blockModalOpen = false"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                        Block Agent
                    </button>
                </div>
            </div>
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

            .status-suspended {
                @apply bg-yellow-500/20 text-yellow-400;
            }

            .status-blocked {
                @apply bg-red-500/20 text-red-400;
            }

            .modal-overlay {
                backdrop-filter: blur(1px);
            }
        </style>
    @endpush
</div>
