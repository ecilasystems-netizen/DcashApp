<div>
    <x-slot name="header">
        <header
            class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{route('dashboard')}}" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">KYC Information</p>
                        <h2 class="font-bold text-xl text-white">Start</h2>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>
    <div class="max-w-lg w-full mx-auto text-center">

        {{--        notification component  --}}
        @include('components.notifications')


        <div
            class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="shield-check" class="w-12 h-12 text-blue-400"></i>
        </div>
        <h2 class="text-3xl font-bold text-white">Verify Your Identity</h2>
        <p class="text-gray-400 mt-2">
            To secure your account and unlock higher transaction limits, we need
            to verify your identity. The process is fast and secure.
        </p>

        <div
            class="bg-gray-800 border border-gray-700 rounded-lg p-6 my-8 text-left space-y-4 text-sm">
            <h3 class="font-semibold text-white text-base mb-4">
                What you will need:
            </h3>
            <div class="flex items-start gap-4">
                <i
                    data-lucide="scan-face"
                    class="w-8 h-8 text-[#E1B362] mt-1"></i>
                <div>
                    <p class="font-medium text-white">
                        A clear photo of your face
                    </p>
                    <p class="text-gray-400">
                        Make sure you are in a well-lit room.
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <i
                    data-lucide="contact"
                    class="w-8 h-8 text-[#E1B362] mt-1"></i>
                <div>
                    <p class="font-medium text-white">
                        A valid government-issued ID
                    </p>
                    <p class="text-gray-400">
                        e.g., National ID Card, Driver's License, or Passport.
                    </p>
                </div>
            </div>
        </div>

        <!-- Button to start the verification process -->
        <button
            wire:click="startVerification"
            class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <span>Start Verification</span>
            <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </button>
    </div>

</div>
