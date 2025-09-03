<div>
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-800/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center space-x-4">

                    <div>
                        <p class="text-xs text-gray-400">Make</p>
                        <h2 class="font-bold text-xl text-white">Payment</h2>
                    </div>
                </div>

                <!-- Desktop Header -->
                <div class="hidden lg:flex items-center space-x-4">

                    <div>
                        <h1 class="text-2xl font-bold text-white">Make Payment to complete transaction</h1>
                        <p class="text-gray-400 text-sm mt-1">Transfer the exact amount to the account below.</p>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div
        x-data="{
            setupBackHandler() {
                // Handle browser back button
                window.addEventListener('popstate', async (e) => {
                    e.preventDefault();
                    await this.showCancelWarning();
                });

                // Handle page refresh/close
                window.addEventListener('beforeunload', (e) => {
                    // Only show warning if not intentionally cancelling
                    if (!window.intentionallyCancelling) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });

                // Handle all link clicks
                document.addEventListener('click', async (e) => {
                    const link = e.target.closest('a');
                    if (link && !link.hasAttribute('data-allow-navigation')) {
                        e.preventDefault();
                        await this.showCancelWarning();
                    }
                });

                // Push initial state
                history.pushState(null, '', window.location.href);
            },

            async showCancelWarning() {
                const result = await Swal.fire({
                    title: 'Cancel Transaction?',
                    text: 'Are you sure you want to leave this page? Your transaction will be cancelled and cannot be undone.',
                    icon: 'warning',
                    background: '#1f2937',
                    color: '#fff',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#374151',
                    confirmButtonText: 'Yes, cancel transaction',
                    cancelButtonText: 'Stay on page'
                });

                if (result.isConfirmed) {
                    // Set a flag to bypass beforeunload warning
                    window.intentionallyCancelling = true;
                    @this.call('cancelTransaction');
                } else {
                    history.pushState(null, '', window.location.href);
                }
            }
        }"
        x-init="setupBackHandler()"
    >

        <div class="max-w-4xl mx-auto">
            <!-- Countdown Timer -->
            <div
                x-data="{
                deadline: {{ $deadline }},
                minutes: '00',
                seconds: '00',
                timer: null,

                startTimer() {
                    this.updateTimer();
                    this.timer = setInterval(() => this.updateTimer(), 1000);
                },

                updateTimer() {
                    const now = Date.now();
                    const distance = this.deadline - now;

                    if (distance <= 0) {
                        clearInterval(this.timer);
                        window.location.href = {{ json_encode(route('dashboard')) }};
                        return;
                    }

                    // Calculate time units
                    this.minutes = Math.floor(distance / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Pad with zeros
                    this.minutes = String(this.minutes).padStart(2, '0');
                    this.seconds = String(this.seconds).padStart(2, '0');
                }
            }"
                x-init="startTimer()"
                class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg p-4 text-center mb-8"
            >
                <p class="font-semibold">This transaction will expire in:</p>
                <p class="text-2xl font-bold text-red-300">
                    <span x-text="minutes">15</span>:<span x-text="seconds">00</span>
                </p>
            </div>


            <!-- Bank Account Details -->
            <div class="bg-gray-950 border border-gray-900 rounded-lg p-6 mb-8">
                @if(!empty($exchangeData))
                    <h4 class="font-bold text-white mb-4">Exchange Details</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">You Send</span>
                            <span class="flex items-center gap-2 font-semibold text-white">
                            {{ number_format($exchangeData['baseAmount'], 2) }} {{ $exchangeData['baseCurrencyCode'] ?? '' }}
                            <img src="{{ asset('storage/' . $exchangeData['baseCurrencyFlag']) ?? ''}}"
                                 class="w-6 h-6 rounded-full" alt="">
                        </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Exchange Rate</span>
                            <span class="font-semibold text-white">
                    1 {{ $exchangeData['baseCurrencyCode'] ?? '' }} = {{ number_format($exchangeData['exchangeRate'], 2) }} {{ $exchangeData['quoteCurrencyCode'] ?? '' }}
                </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Processing Fee</span>
                            <span class="font-semibold text-white">
                    {{ number_format($exchangeData['processingFee'] ?? 0, 2) }} {{ $exchangeData['baseCurrencyCode'] ?? '' }}
                </span>
                        </div>
                        <hr class="border-gray-800">
                        <div class="flex justify-between items-center text-base">
                            <span class="text-gray-300">You Receive</span>
                            <span class="flex items-center gap-2 font-bold text-sm text-[#E1B362]">
                        {{ number_format($exchangeData['quoteAmount'], 2) }} {{ $exchangeData['quoteCurrencyCode'] ?? '' }}
                            <img src="{{asset('storage/' .$exchangeData['quoteCurrencyFlag']) ?? ''}}"
                                 class="w-6 h-6 rounded-full" alt="">
                        </span>
                        </div>
                    </div>
                @endif
                <hr class="border-gray-800 mt-6 pt-2">

                @if($baseCurrencyType === 'fiat')

                    <h4 class="font-bold text-white mb-4 text-center md:text-left">Transfer to this Account</h4>

                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        <!-- Text Details -->
                        <div class="w-full md:flex-1 space-y-4 text-sm">

                            <hr class="border-gray-800">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Bank Name</span>
                                <span class="font-semibold text-white">{{$companyBankAccount->bank_name}}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Account Name</span>
                                <span class="font-semibold text-white">{{$companyBankAccount->account_name}}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Account Number</span>
                                <div class="flex items-center gap-2">
                            <span id="accountNumber"
                                  class="font-semibold text-white">{{$companyBankAccount->account_number}}</span>
                                    <button id="copyButton" class="text-gray-400 hover:text-[#E1B362]">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- QR Code -->
                        @if($companyBankAccount->bank_account_qr_code)
                            <div class="md:w-auto flex flex-col items-center ">
                                <img src="{{ Storage::url($companyBankAccount->bank_account_qr_code) }}" alt="QR Code"
                                     style="width:150px;max-width:100%;"
                                     class="p-2 bg-gray-700 rounded-lg mt-0 md:mt-10"/>
                                <p class="text-xs text-gray-400 mt-2">Scan to pay</p>
                            </div>
                        @endif
                    </div>
                @else

                    <h4 class="font-bold text-white mb-4 text-center md:text-left">Transfer Crypto Wallet below</h4>

                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        <!-- Text Details -->
                        <div class="w-full md:flex-1 space-y-4 text-sm">

                            <hr class="border-gray-800">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Crypto Name</span>
                                <span class="font-semibold text-white">{{$companyBankAccount->crypto_name}}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Network Type</span>
                                <span class="font-semibold text-white">{{$companyBankAccount->crypto_network}}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Wallet Address</span>
                                <div class="flex items-center gap-2">
                            <span id="accountNumber"
                                  class="font-semibold text-white">{{$companyBankAccount->crypto_wallet_address}}</span>
                                    <button id="copyButton" class="text-gray-400 hover:text-[#E1B362]">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- QR Code -->
                        @if($companyBankAccount->crypto_qr_code)
                            <div class="md:w-auto flex flex-col items-center ">
                                <img src="{{ asset('storage/'.$companyBankAccount->crypto_qr_code) }}" alt="QR Code"
                                     style="width:150px;max-width:100%;"
                                     class="p-2 bg-gray-700 rounded-lg mt-0 md:mt-10"/>
                                <p class="text-xs text-gray-400 mt-2">Scan to pay</p>
                            </div>
                        @endif
                    </div>
                @endif


            </div>

            <form wire:submit.prevent="saveTransaction">
                <!-- Upload Payment Slip -->
                <div class="bg-gray-950 border border-gray-800 rounded-lg p-6">
                    <h4 class="font-bold text-white mb-2">Upload Proof of Payment</h4>
                    <p class="text-sm text-gray-400 mb-6">Upload a screenshot or photo of your payment receipt.</p>

                    <label for="paymentSlip"
                           class="file-upload-label flex flex-col items-center justify-center p-6 rounded-lg cursor-pointer">
                        <i data-lucide="upload-cloud" class="w-10 h-10 text-gray-500 mb-2"></i>
                        <p class="font-semibold">Click to upload files</p>
                        <p class="text-xs text-gray-700">PNG, JPG or PDF, max 5MB</p>
                    </label>
                    <div>
                        <input type="file"
                               wire:model="newPaymentSlip"
                               id="paymentSlip"
                               class="hidden"
                               accept="image/*">

                        <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                            @if($paymentSlips)
                                @foreach($paymentSlips as $index => $slip)
                                    <div class="relative">
                                        <img src="{{ $slip->temporaryUrl() }}"
                                             class="w-full h-24 object-cover rounded-lg border border-gray-600">
                                        <button type="button"
                                                wire:click="removeFile({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-700">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>


                </div>

                <div class="pt-1 mt-1 flex gap-4">
                    <button type="submit"
                            class="brand-gradient flex-1 text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-center"
                            @if(!$paymentSlips) disabled @endif>
                        Submit
                    </button>
                    <button type="button"
                            x-data
                            @click="async () => {
                                const result = await Swal.fire({
                                    title: 'Cancel Transaction?',
                                    text: 'Are you sure you want to cancel this transaction? This action cannot be undone.',
                                    icon: 'warning',
                                    background: '#1f2937',
                                    color: '#fff',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc2626',
                                    cancelButtonColor: '#374151',
                                    confirmButtonText: 'Yes, cancel it',
                                    cancelButtonText: 'No, keep it'
                                });

                                if (result.isConfirmed) {
                                    // Set flag before cancelling to bypass beforeunload warning
                                    window.intentionallyCancelling = true;
                                    $wire.cancelTransaction();
                                }
                            }"
                            class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg font-semibold text-base hover:bg-red-700 transition-all text-center">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

    </div>
