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
