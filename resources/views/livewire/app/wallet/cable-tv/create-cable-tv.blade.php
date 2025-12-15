<div>
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{route('dashboard')}}" class="p-2 rounded-full hover:bg-gray-800">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Pay</p>
                        <h2 class="font-bold text-xl text-white">Cable Tv</h2>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>


    <div class=" flex items-center justify-center p-4">
        <div class="max-w-2xl w-full text-center space-y-8">
            <!-- Animated Gear Icon -->
            <div class="relative w-32 h-32 mx-auto">
                <div class="absolute inset-0 animate-spin-slow">
                    <svg class="w-full h-full text-[#E1B362]" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="absolute inset-0 animate-ping-slow opacity-75">
                    <svg class="w-full h-full text-[#E1B362]" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h1 class="text-4xl font-bold text-white">System Maintenance</h1>
                <p class="text-gray-400 text-lg">We're currently performing scheduled maintenance, please check back
                    later.</p>
            </div>

            <!-- Progress Bar -->
            <div class="max-w-md mx-auto w-full bg-gray-800 rounded-full h-2 overflow-hidden">
                <div class="brand-gradient h-full w-2/3 animate-progress"></div>
            </div>


        </div>
    </div>

    @push('styles')
        <style>
            .animate-spin-slow {
                animation: spin 10s linear infinite;
            }

            .animate-ping-slow {
                animation: ping 3s cubic-bezier(0, 0, 0.2, 1) infinite;
            }

            .animate-progress {
                animation: progress 2s ease-in-out infinite;
            }

            @keyframes progress {
                0% {
                    transform: translateX(-100%);
                }
                100% {
                    transform: translateX(100%);
                }
            }

            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }
        </style>
    @endpush
</div>
