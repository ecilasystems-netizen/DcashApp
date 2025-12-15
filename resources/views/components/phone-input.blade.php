<?php /* File: `resources/views/components/phone-input.blade.php` - Blade + Alpine */ ?>

<div x-data="phoneInput()" x-init="init()" class="relative">
    <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Phone</label>

    <div class="flex items-center space-x-2">
        <div class="relative">
            <button type="button" @click="open = !open"
                    class="flex items-center gap-2 bg-gray-800/2 border border-gray-600 rounded-lg pl-4 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] p-2">
                <img :src="selected.flag" alt="flag" class="w-5 h-5 object-cover">
                <span x-text="selected.dial_code" class="text-sm text-gray-200"></span>
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false"
                 class="absolute z-50 mt-2 w-56 max-h-56 overflow-auto bg-gray-800 border border-gray-700 rounded shadow-lg">
                <template x-for="c in countries" :key="c.code">
                    <button type="button"
                            @click="select(c)"
                            class="w-full text-left px-3 py-2 hover:bg-gray-700 flex items-center gap-2">
                        <img :src="c.flag" :alt="c.code + ' flag'" class="w-5 h-4 object-cover">
                        <div class="flex-1">
                            <div class="text-sm" x-text="c.name"></div>
                            <div class="text-xs text-gray-400" x-text="c.dial_code"></div>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <div class="flex-1 relative">
            <input id="phone" wire:model="phone" type="tel" placeholder="034 801 234 223"
                   inputmode="numeric" pattern="[0-9\s]*"
                   oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                   class="w-full bg-gray-800\/2 border border-gray-600 rounded-lg pl-4 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362] p-2">
        </div>
    </div>

    <!-- hidden country code for Livewire - add x-ref to dispatch input events to Livewire -->
    <input type="hidden" wire:model="country_code" x-model="selected.dial_code" x-ref="countryCode">

    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    @error('country_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

</div>

<script>
    function phoneInput() {
        return {
            open: false,
            countries: @json(config('countries')),
            selected: @json(config('countries')[0] ?? ['flag'=>'','dial_code'=>'']),
            init() {
                // if Livewire has an initial country_code, set selected accordingly
                const existing = document.querySelector('input[wire\\:model="country_code"], input[name="country_code"]');
                if (existing && existing.value) {
                    const found = this.countries.find(c => c.dial_code === existing.value);
                    if (found) this.selected = found;
                }
            },
            select(c) {
                this.selected = c;
                this.open = false;
                this.$nextTick(() => {
                    // update the hidden input and dispatch an input event so Livewire notices the change
                    const hidden = this.$refs.countryCode;
                    if (hidden) {
                        hidden.value = c.dial_code;
                        hidden.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                    // focus phone field
                    const el = document.getElementById('phone');
                    if (el) el.focus();
                });
            }
        }
    }
</script>
