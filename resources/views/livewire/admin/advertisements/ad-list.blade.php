{{-- resources/views/livewire/admin/advertisements/ad-list.blade.php --}}
<div>
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Advertisement Management</h1>

            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if(session()->has('message'))
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        <button wire:click="$set('showModal', true)"
                class="bg-[#E1B362] hover:bg-[#d1a352] text-gray-900 font-semibold px-4 py-2 rounded-lg flex items-center gap-2 mb-10">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Advertisement
        </button>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-500/20 text-blue-400 rounded-full">
                        <i data-lucide="image" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Ads</p>
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Advertisement::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-green-500/20 text-green-400 rounded-full">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Active Ads</p>
                        <p class="text-2xl font-bold text-green-400">{{ \App\Models\Advertisement::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 border border-gray-700 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-full">
                        <i data-lucide="eye" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Impressions</p>
                        <p class="text-2xl font-bold text-yellow-400">{{ number_format(\App\Models\Advertisement::sum('impressions')) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative md:flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search advertisements..."
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
            </div>
            <select wire:model.live="perPage"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </select>
        </div>

        <!-- Ads Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-6 py-3">Preview</th>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Impressions</th>
                    <th class="px-6 py-3">Clicks</th>
                    <th class="px-6 py-3">CTR</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($ads as $ad)
                    <tr class="hover:bg-gray-700/30">
                        <td class="px-6 py-4">
                            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}"
                                 class="w-20 h-16 object-cover rounded border border-gray-600">
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-white">{{ $ad->title }}</p>
                            @if($ad->link_url)
                                <a href="{{ $ad->link_url }}" target="_blank"
                                   class="text-xs text-blue-400 hover:underline">
                                    {{ Str::limit($ad->link_url, 40) }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($ad->is_active)
                                <span class="status-pill status-active">Active</span>
                            @else
                                <span class="status-pill status-blocked">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ number_format($ad->impressions) }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ number_format($ad->clicks) }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $ad->impressions > 0 ? number_format(($ad->clicks / $ad->impressions) * 100, 2) : 0 }}%
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                                <div x-show="open" x-cloak @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-600 rounded-lg shadow-lg z-10">
                                    <button wire:click="toggleStatus({{ $ad->id }})"
                                            class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-600 w-full text-left">
                                        <i data-lucide="{{ $ad->is_active ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                                        {{ $ad->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button wire:click="delete({{ $ad->id }})"
                                            wire:confirm="Are you sure you want to delete this advertisement?"
                                            class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-gray-600 w-full text-left">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                            No advertisements found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-700">
                {{ $ads->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
        <div x-data class="fixed inset-0 z-30 flex items-center justify-center p-4 bg-black/50">
            <div @click.away="$wire.showModal = false"
                 class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md border border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Add New Advertisement</h3>
                    <form wire:submit="save">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                                <input wire:model="title" type="text"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Link URL (Optional)</label>
                                <input wire:model="link_url" type="url"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('link_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Image </label>
                                <input wire:model="image" type="file" accept="image/*"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                @error('image') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}"
                                         class="mt-2 w-full h-auto object-cover rounded">
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end gap-4 mt-6">
                            <button type="button" wire:click="$set('showModal', false)"
                                    class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded-lg">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-[#E1B362] hover:bg-[#d1a352] text-gray-900 font-medium py-2 px-4 rounded-lg">
                                Save Advertisement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
