<div x-data="{
isModalOpen: false,
    isDeleteModalOpen: false,
    announcementToDelete: null,
    announcement: null,
    formatYoutubeUrl(url) {
        if(!url) return '';

        // Handle youtube.com/watch?v= format
        if(url.includes('youtube.com/watch?v=')) {
            const videoId = new URL(url).searchParams.get('v');
            return `https://www.youtube.com/embed/${videoId}`;
        }

        // Handle youtu.be/ format
        if(url.includes('youtu.be/')) {
            const videoId = url.split('youtu.be/')[1].split('?')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }

        // If it's already an embed URL, return as is
        if(url.includes('youtube.com/embed/')) {
            return url;
        }

        // Default fallback
        return url;
    }
}">
    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Pop-up Announcements</h1>
                <a href="{{ route('admin.announcements.create') }}"
                   class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span class="hidden md:inline">Create Announcement</span>
                </a>
            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 bg-green-500 text-white p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Section with colored icons -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-500/20 rounded-lg">
                        <i data-lucide="megaphone" class="w-5 h-5 text-purple-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Total Announcements</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ $announcements->total() }}</div>
            </div>
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-green-500/20 rounded-lg">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Active Announcements</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ $announcements->where('is_active', true)->count() }}</div>
            </div>
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-500/20 rounded-lg">
                        <i data-lucide="eye" class="w-5 h-5 text-blue-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Total Views</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ number_format($announcements->sum('views')) }}</div>
            </div>
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-amber-500/20 rounded-lg">
                        <i data-lucide="mouse-pointer-click" class="w-5 h-5 text-amber-400"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase">Total Clicks</div>
                </div>
                <div class="text-white text-2xl font-bold">{{ number_format($announcements->sum('clicks')) }}</div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative flex-grow">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input type="text" placeholder="Search by title..." wire:model.live="search"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
            </div>
            <select wire:model.live="status"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
            </select>
        </div>

        <!-- Announcements Table -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-700/50 text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Views / Clicks</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @forelse ($announcements as $announcement)
                        <tr class="hover:bg-gray-700/30">
                            <td class="px-6 py-4 font-semibold text-white">{{ $announcement->title }}</td>
                            <td class="px-6 py-4">{{ ucfirst($announcement->content_type) }}</td>
                            <td class="px-6 py-4">{{ number_format($announcement->views) }}
                                / {{ number_format($announcement->clicks) }}
                                <span class="text-xs text-gray-400">({{ $announcement->click_through_rate }}%)</span>
                            </td>
                            <td class="px-6 py-4">
                                <label class="switch">
                                    <input type="checkbox" wire:click="toggleActive({{ $announcement->id }})"
                                        {{ $announcement->is_active ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    @click="isModalOpen = true; announcement = {
                                                    id: {{ $announcement->id }},
                                                    title: '{{ $announcement->title }}',
                                                    type: '{{ $announcement->content_type }}',
                                                    content: {{ json_encode($announcement->content) }},
                                                    ctaText: '{{ $announcement->cta_text }}',
                                                    ctaLink: '{{ $announcement->cta_link }}'
                                                }"
                                    class="text-xs bg-gray-700 hover:bg-gray-600 font-semibold py-1 px-3 rounded-lg mr-2">
                                    Preview
                                </button>
                                <a href="#" class="text-xs text-[#E1B362] hover:underline mr-2">Edit</a>
                                <button
                                    @click="isDeleteModalOpen = true; announcementToDelete = {{ $announcement->id }}"
                                    class="text-xs text-red-500 hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">No announcements found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>

    <!-- Announcement Preview Modal -->
    <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="display: none;">
        <div @click.away="isModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl border border-gray-700 max-h-[90vh] flex flex-col"
             x-data="{ currentSlide: 0 }">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-lg font-bold text-white" x-text="announcement?.title"></h3>
            </div>
            <div class="p-4 flex-1 overflow-y-auto">
                <!-- Single Image -->
                <template x-if="announcement?.type === 'image'">
                    <img :src="'/storage/' + announcement.content.path"
                         class="w-full h-auto rounded-lg object-contain max-h-[70vh]">
                </template>
                <!-- Single Video -->
                <template x-if="announcement?.type === 'video'">
                    <div class="aspect-w-16 aspect-h-14">
                        <iframe :src="formatYoutubeUrl(announcement.content.url)"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                class="w-full h-full rounded-lg"></iframe>
                    </div>
                </template>
                <!-- Image Slider -->
                <template x-if="announcement?.type === 'slider'">
                    <div class="relative"
                         x-init="
                            setInterval(() => {
                                if (announcement?.content?.paths?.length > 1) {
                                    currentSlide = (currentSlide < announcement.content.paths.length - 1) ? currentSlide + 1 : 0;
                                }
                            }, 5000);
                         ">
                        <template x-for="(path, index) in announcement.content.paths" :key="index">
                            <div x-show="currentSlide === index"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                                <img :src="'/storage/' + path"
                                     class="w-full h-auto rounded-lg object-contain max-h-[60vh]">
                            </div>
                        </template>
                        <button x-show="announcement?.content?.paths?.length > 1"
                                @click="currentSlide = (currentSlide > 0) ? currentSlide - 1 : announcement.content.paths.length - 1"
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-gray-900/50 p-2 rounded-full text-white hover:bg-gray-900">
                            <i data-lucide="chevron-left"></i>
                        </button>
                        <button x-show="announcement?.content?.paths?.length > 1"
                                @click="currentSlide = (currentSlide < announcement.content.paths.length - 1) ? currentSlide + 1 : 0"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-900/50 p-2 rounded-full text-white hover:bg-gray-900">
                            <i data-lucide="chevron-right"></i>
                        </button>
                        <!-- Slide indicators -->
                        <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1.5">
                            <template x-for="(_, index) in announcement?.content?.paths || []" :key="index">
                                <button @click="currentSlide = index"
                                        class="w-2 h-2 rounded-full transition-colors duration-300"
                                        :class="currentSlide === index ? 'bg-white' : 'bg-gray-500'">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- CTA Button -->
                <div x-show="announcement?.ctaText && announcement?.ctaLink" class="mt-4 text-center">
                    <a :href="announcement.ctaLink" target="_blank"
                       class="brand-gradient inline-block text-white font-semibold py-2 px-6 rounded-lg"
                       x-text="announcement.ctaText">
                    </a>
                </div>
            </div>
            <div class="p-4 flex justify-end border-t border-gray-700">
                <button @click="isModalOpen = false"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">Close
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="isDeleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 flex items-center justify-center modal-overlay p-4"
         style="display: none;">
        <div @click.away="isDeleteModalOpen = false"
             class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md border border-gray-700 flex flex-col">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                    </div>
                </div>
                <h3 class="mb-2 text-lg font-medium text-white">Confirm Deletion</h3>
                <p class="mb-5 text-sm text-gray-400">
                    Are you sure you want to delete this announcement? This action cannot be undone.
                </p>
                <div class="flex justify-center gap-4">
                    <button @click="isDeleteModalOpen = false"
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button @click="isDeleteModalOpen = false; $wire.delete(announcementToDelete)"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Custom scrollbar for webkit browsers */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #1f2937; /* bg-gray-800 */
            }

            ::-webkit-scrollbar-thumb {
                background: #4b5563; /* bg-gray-600 */
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #6b7280; /* bg-gray-500 */
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
                background-color: #10B981;
            }

            input:checked + .slider:before {
                transform: translateX(16px);
            }

            .modal-overlay {
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(1px);
            }
        </style>
    @endpush
</div>
