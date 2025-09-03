<div x-data="{ contentType: @entangle('contentType').defer, imagePreview: null, sliderPreviews: [] }">

    @push('styles')
        <style>
            .file-upload-label {
                border: 2px dashed #4b5563;
                transition: all 0.2s ease-in-out;
            }

            .file-upload-label:hover {
                border-color: #e1b362;
                background-color: #374151;
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
                transition: 0.4s;
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
                transition: 0.4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background-color: #10b981;
            }

            input:checked + .slider:before {
                transform: translateX(16px);
            }
        </style>
    @endpush

    <x-slot name="header">
        <header class="bg-gray-800/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.announcements') }}" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-white">Create New Announcement</h1>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="p-6">
        @if (session('success'))
            <div class="max-w-4xl mx-auto mb-4 bg-green-500 text-white p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <form wire:submit.prevent="save" class="space-y-8">
                <!-- Announcement Details -->
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="font-bold text-white text-lg mb-6">
                        Announcement Details
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-400 mb-2">Title</label>
                            <input
                                type="text"
                                id="title"
                                wire:model.defer="title"
                                placeholder="e.g., New Referral Program"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="contentType" class="block text-sm font-medium text-gray-400 mb-2">Content
                                Type</label>
                            <select
                                id="contentType"
                                wire:model="contentType"
                                x-model="contentType"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                <option value="image">Single Image</option>
                                <option value="video">Single Video</option>
                                <option value="slider">Image Slider</option>
                            </select>
                            @error('contentType') <span
                                class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Conditional Content Uploads -->
                        <div x-show="contentType === 'image'" x-transition>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Upload Image</label>
                            <input
                                type="file"
                                wire:model="image"
                                @change="imagePreview = URL.createObjectURL($event.target.files[0])"
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600"/>
                            @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <template x-if="imagePreview">
                                <img
                                    :src="imagePreview"
                                    class="mt-4 rounded-lg max-h-64 object-contain bg-gray-900/50"
                                />
                            </template>
                        </div>

                        <div x-show="contentType === 'video'" x-transition>
                            <label for="videoUrl" class="block text-sm font-medium text-gray-400 mb-2">Video URL</label>
                            <input
                                type="text"
                                id="videoUrl"
                                wire:model.defer="videoUrl"
                                placeholder="e.g., https://youtube.com/watch?v=..."
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                            @error('videoUrl') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div x-show="contentType === 'slider'" x-transition>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Upload Images for Slider
                                (Multiple)</label>
                            <input
                                type="file"
                                multiple
                                wire:model="sliderImages"
                                @change="sliderPreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))"
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600"/>
                            @error('sliderImages.*') <span
                                class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <div
                                class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"
                                x-show="sliderPreviews.length > 0">
                                <template x-for="src in sliderPreviews" :key="src">
                                    <img
                                        :src="src"
                                        class="w-full h-24 object-cover rounded-lg"/>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action & Status -->
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="font-bold text-white text-lg mb-6">Optional Settings</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="ctaText" class="block text-sm font-medium text-gray-400 mb-2">Button Text
                                (Optional)</label>
                            <input
                                type="text"
                                id="ctaText"
                                wire:model.defer="ctaText"
                                placeholder="e.g., Learn More"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                            @error('ctaText') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="ctaLink" class="block text-sm font-medium text-gray-400 mb-2">Button Link
                                (URL)</label>
                            <input
                                type="url"
                                id="ctaLink"
                                wire:model.defer="ctaLink"
                                placeholder="https://..."
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"/>
                            @error('ctaLink') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <div>
                                <p class="font-medium text-white">Status</p>
                                <p class="text-sm text-gray-400">
                                    Set to active to display to users immediately.
                                </p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" wire:model.defer="isActive"/>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Publication
                                Status</label>
                            <select
                                id="status"
                                wire:model.defer="status"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <button
                        type="button"
                        wire:click="cancel"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="brand-gradient text-white font-semibold py-2 px-4 rounded-lg">
                        Save Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
