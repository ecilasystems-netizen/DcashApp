<div>
    @push('styles')
        <style>
            body {
                font-family: "Inter", sans-serif;
            }

            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            .file-upload-label {
                @apply border-2 border-dashed border-gray-600 hover:border-[#E1B362] transition-colors;
            }

            .spinner {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{route('kyc.personal-info')}}" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">KYC</p>
                        <h2 class="font-bold text-xl text-white">Upload ID Card</h2>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-400">Step 2 of 4</p>
            </div>
        </header>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form wire:submit="submit" class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <div class="space-y-6">
                <div>
                    <label for="idType" class="block text-gray-400 mb-2 text-sm">Select ID Type</label>
                    <select
                        wire:model.live="idType"
                        id="idType"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        <option value="">Select ID Type</option>
                        <option value="nin">National ID Card (NIN Slip)</option>
                        <option value="drivers_license">Driver's License</option>
                        <option value="passport">Passport</option>
                        <option value="voters_card">Voter's Card</option>
                    </select>
                    @error('idType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- ID Number field -->
                <div>
                    <label for="idNumber" class="block text-gray-400 mb-2 text-sm">ID Number</label>
                    <input
                        type="text"
                        wire:model="idNumber"
                        id="idNumber"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]"
                        placeholder="Enter your ID number">
                    @error('idNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                @if($idType === 'passport')
                    <div>
                        <p class="font-medium text-white mb-2">Passport Data Page</p>

                        <!-- Loading state for passport -->
                        <div wire:loading wire:target="passportPage" class="relative">
                            <div
                                class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg h-48 bg-gray-700/50">
                                <div
                                    class="spinner w-8 h-8 border-2 border-[#E1B362] border-t-transparent rounded-full mb-2"></div>
                                <p class="text-sm text-gray-400">Processing image...</p>
                            </div>
                        </div>

                        <!-- Loaded state -->
                        <div wire:loading.remove wire:target="passportPage">
                            @if($passportPage)
                                <div class="relative">
                                    <img src="{{ $passportPage->temporaryUrl() }}"
                                         class="w-full h-48 object-cover rounded-lg border border-gray-600"
                                         alt="Passport Data Page Preview">
                                    <button type="button"
                                            wire:click="removePassportPage"
                                            class="absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @else
                                <label
                                    for="passportPage"
                                    class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg cursor-pointer h-48 hover:bg-gray-700/20 transition-colors">
                                    <i data-lucide="upload-cloud" class="w-10 h-10 text-gray-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-300">Upload Data Page</p>
                                    <p class="text-xs text-gray-500">PNG or JPG (max. 5MB)</p>
                                </label>
                                <input type="file"
                                       wire:model="passportPage"
                                       id="passportPage"
                                       class="hidden"
                                       accept="image/*"/>
                            @endif
                        </div>
                        @error('passportPage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Front ID Upload -->
                        <div>
                            <p class="font-medium text-white mb-2">Front of ID</p>

                            <!-- Loading state for front ID -->
                            <div wire:loading wire:target="frontId" class="relative">
                                <div
                                    class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg h-40 bg-gray-700/50">
                                    <div
                                        class="spinner w-6 h-6 border-2 border-[#E1B362] border-t-transparent rounded-full mb-2"></div>
                                    <p class="text-xs text-gray-400">Processing...</p>
                                </div>
                            </div>

                            <!-- Loaded state -->
                            <div wire:loading.remove wire:target="frontId">
                                @if($frontId)
                                    <div class="relative">
                                        <img src="{{ $frontId->temporaryUrl() }}"
                                             class="w-full h-40 object-cover rounded-lg border border-gray-600"
                                             alt="ID Front Preview">
                                        <button type="button"
                                                wire:click="removeFront"
                                                class="absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @else
                                    <label
                                        for="frontId"
                                        class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg cursor-pointer h-40 hover:bg-gray-700/20 transition-colors">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-500 mb-2"></i>
                                        <p class="text-sm font-semibold text-gray-300">Click to upload</p>
                                        <p class="text-xs text-gray-500">PNG or JPG (max. 5MB)</p>
                                    </label>
                                    <input type="file"
                                           wire:model="frontId"
                                           id="frontId"
                                           class="hidden"
                                           accept="image/*"/>
                                @endif
                            </div>
                            @error('frontId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Back ID Upload -->
                        <div>
                            <p class="font-medium text-white mb-2">Back of ID</p>

                            <!-- Loading state for back ID -->
                            <div wire:loading wire:target="backId" class="relative">
                                <div
                                    class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg h-40 bg-gray-700/50">
                                    <div
                                        class="spinner w-6 h-6 border-2 border-[#E1B362] border-t-transparent rounded-full mb-2"></div>
                                    <p class="text-xs text-gray-400">Processing...</p>
                                </div>
                            </div>

                            <!-- Loaded state -->
                            <div wire:loading.remove wire:target="backId">
                                @if($backId)
                                    <div class="relative">
                                        <img src="{{ $backId->temporaryUrl() }}"
                                             class="w-full h-40 object-cover rounded-lg border border-gray-600"
                                             alt="ID Back Preview">
                                        <button type="button"
                                                wire:click="removeBack"
                                                class="absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @else
                                    <label
                                        for="backId"
                                        class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg cursor-pointer h-40 hover:bg-gray-700/20 transition-colors">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-500 mb-2"></i>
                                        <p class="text-sm font-semibold text-gray-300">Click to upload</p>
                                        <p class="text-xs text-gray-500">PNG or JPG (max. 5MB)</p>
                                    </label>
                                    <input type="file"
                                           wire:model="backId"
                                           id="backId"
                                           class="hidden"
                                           accept="image/*"/>
                                @endif
                            </div>
                            @error('backId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <div class="pt-4">
                    <button
                        type="submit"
                        class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Continue</span>
                        <span wire:loading wire:target="submit" class="flex items-center justify-center gap-2">
                                            <div
                                                class="spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full"></div>
                                            Processing...
                                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
