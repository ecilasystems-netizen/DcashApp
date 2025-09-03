<div>
    <x-slot name="header">
        <header
            class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-400">KYC</p>
                    <h2 class="font-bold text-xl text-white">Submitted</h2>
                </div>
                <p class="text-sm font-semibold text-gray-400">Step 4 of 4</p>
            </div>
        </header>
    </x-slot>

    <div class="max-w-lg w-full mx-auto text-center">
        <div
            class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="file-clock" class="w-12 h-12 text-yellow-400"></i>
        </div>
        <h2 class="text-3xl font-bold text-white">Documents Under Review</h2>
        <p class="text-gray-400 mt-2">
            Your documents have been submitted successfully and are now being
            reviewed. This usually takes a few minutes, but can sometimes take
            up to 24 hours.
        </p>
        <p class="text-gray-400 mt-2">
            We will notify you by email once the review is complete.
        </p>

        <div class="mt-8">
            <a href="{{route('dashboard')}}"
               class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
