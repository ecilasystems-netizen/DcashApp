<div>
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
                <a href="{{route('dashboard')}}" class="p-2 rounded-full hover:bg-gray-800">
                    <i data-lucide="arrow-left"></i>
                </a>
                <div>
                    <p class="text-xs text-gray-400">Make</p>
                    <h2 class="font-bold text-xl text-white">Deposits</h2>
                </div>
            </div>
        </header>
    </x-slot>


    <!-- Deposit Details -->
    <div class=" lg:py-8 space-y-8">

        <!-- Instructions -->
        <div class="bg-gray-800/2  border-gray-700 rounded-2xl text-center">
            <h3 class="font-semibold text-[#E1B362] mb-4">Instructions</h3>
            <ul class="space-y-3 text-white text-sm list-disc list-inside bg-gray-800 p-5 rounded-lg">
                Make a transfer into the account, your wallet will be credited automatically within 2-5 minutes.
            </ul>
        </div>

        <div class="bg-gray-800/2 border-2 border-dashed border-gray-700 rounded-2xl p-6 text-center">
            <h2 class="text-lg font-semibold text-white mb-4">Your Dedicated Account Details</h2>
            <div class="space-y-5">
                <!-- Account Number -->
                <div>
                    <p class="text-sm text-gray-400">Account Number</p>
                    <div class="flex items-center justify-center gap-4 mt-1">
                        <p id="account-number" class="text-2xl font-bold text-[#E1B362]">{{$accountNumber}}</p>
                        <button data-copy-target="account-number"
                                class="copy-btn p-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                            <i data-lucide="copy" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
                <!-- Bank Name -->
                <div>
                    <p class="text-sm text-gray-400">Bank Name</p>
                    <div class="flex items-center justify-center gap-4 mt-1">
                        <p id="bank-name" class="text-xl font-semibold text-white">{{$bankName}}</p>

                    </div>
                </div>
                <!-- Account Name -->
                <div>
                    <p class="text-sm text-gray-400">Account Name</p>
                    <div class="flex items-center justify-center gap-4 mt-1">
                        <p id="account-name" class="text-xl font-semibold text-white">{{$accountName}}</p>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-center mt-4 text-white">Account Tiers & Limits</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Tier 1 -->
            <div class="bg-gray-800 border border-yellow-500/30 rounded-xl p-3 flex flex-col">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold bg-yellow-500/10 text-yellow-400 py-1 px-2 rounded-full">Current</span>
                    <h4 class="text-sm font-bold text-white">Tier 1</h4>
                </div>
                <p class="text-gray-400 text-xs mt-2">Basic access</p>
                <ul class="mt-2 text-xs space-y-1">
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-gray-400"><i data-lucide="arrow-down-up"
                                                                              class="w-4 h-4"></i><span>Daily</span>
                        </div>
                        <span class="font-semibold text-white">₦100,000</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-gray-400"><i data-lucide="wallet"
                                                                              class="w-4 h-4"></i><span>Max</span></div>
                        <span class="font-semibold text-white">₦300,000</span>
                    </li>
                </ul>
                <button
                    class="mt-3 w-full bg-gray-700 text-gray-400 text-sm font-bold py-2 rounded-lg cursor-not-allowed">
                    Active
                </button>
            </div>

            <!-- Tier 2 -->
            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-3 flex flex-col">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-white">Tier 2</h4>
                </div>
                <p class="text-gray-400 text-xs mt-2">Higher limits</p>
                <ul class="mt-2 text-xs space-y-1">
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-gray-400"><i data-lucide="arrow-down-up"
                                                                              class="w-4 h-4"></i><span>Daily</span>
                        </div>
                        <span class="font-semibold text-white">₦200,000</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-gray-400"><i data-lucide="wallet"
                                                                              class="w-4 h-4"></i><span>Max</span></div>
                        <span class="font-semibold text-white">₦500,000</span>
                    </li>
                </ul>
                <button
                    class="mt-3 w-full bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold py-2 rounded-lg transition-colors">
                    Upgrade
                </button>
            </div>

            <!-- Tier 3 -->
            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-3 flex flex-col">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-white">Tier 3</h4>
                </div>
                <p class="text-gray-400 text-xs mt-2">Power users</p>
                <ul class="mt-2 text-xs space-y-1">
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-gray-400"><i data-lucide="arrow-down-up"
                                                                              class="w-4 h-4"></i><span>Daily</span>
                        </div>
                        <span class="font-semibold text-white">₦5,000,000</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-gray-400"><i data-lucide="wallet"
                                                                              class="w-4 h-4"></i><span>Max</span></div>
                        <span class="font-semibold text-white">Unlimited</span>
                    </li>
                </ul>
                <button
                    class="mt-3 w-full bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold py-2 rounded-lg transition-colors">
                    Upgrade
                </button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>

            document.addEventListener('DOMContentLoaded', () => {
                const copyButtons = document.querySelectorAll('.copy-btn');

                copyButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const targetId = button.dataset.copyTarget;
                        const targetElement = document.getElementById(targetId);
                        const textToCopy = targetElement.textContent;

                        // Create a temporary textarea to hold the text
                        const textArea = document.createElement('textarea');
                        textArea.value = textToCopy;
                        document.body.appendChild(textArea);
                        textArea.select();

                        try {
                            // Use the Clipboard API
                            document.execCommand('copy');

                            // Visual feedback
                            const originalIcon = button.innerHTML;
                            button.innerHTML = `<i data-lucide="check" class="w-5 h-5 text-green-400"></i>`;
                            lucide.createIcons();

                            setTimeout(() => {
                                button.innerHTML = originalIcon;
                                lucide.createIcons();
                            }, 2000);

                        } catch (err) {
                            console.error('Failed to copy text: ', err);
                        }

                        document.body.removeChild(textArea);
                    });
                });
            });
        </script>
    @endpush
</div>
