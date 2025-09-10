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
                        window.intentionallyCancelling = true; // Bypass beforeunload warning
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
                <p class="font-semibold">Complete the transaction in:</p>
                <p class="text-2xl font-bold text-red-300">
                    <span x-text="minutes">15</span>:<span x-text="seconds">00</span> mins
                </p>
            </div>


            <!-- Bank Account Details -->
            <div class="bg-gray-950 border border-gray-900 rounded-lg p-6 mb-8">
                @if(!empty($exchangeData))
                    <h4 class="font-bold text-white mb-4">Exchange Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">You Send</span>
                            <span class="flex items-center gap-2 font-semibold text-gray-400 line-through">
                                    {{ number_format($exchangeData['baseAmount'], 2) }} {{ $exchangeData['baseCurrencyCode'] ?? '' }}
                                    <img src="{{ asset('storage/' . $exchangeData['baseCurrencyFlag']) ?? ''}}"
                                         class="w-5 h-5 rounded-full" alt="">
                                </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Cashback (0.1%)</span>
                            <span class="font-semibold text-red-400">
                                    - {{ number_format($exchangeData['baseAmount'] * 0.001, 2) }} {{ $exchangeData['baseCurrencyCode'] ?? '' }}
                                </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300 font-bold">Total to Send</span>
                            <span class="flex items-center gap-3 font-bold text-green-400 text-lg">
                                    {{ number_format($exchangeData['baseAmount'] - ($exchangeData['baseAmount'] * 0.001), 2) }} {{ $exchangeData['baseCurrencyCode'] ?? '' }}
                                    <img src="{{ asset('storage/' . $exchangeData['baseCurrencyFlag']) ?? ''}}"
                                         class="w-5 h-5 rounded-full" alt="">
                                </span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-base">
                        <span class="text-gray-300">You Receive</span>
                        <span class="flex items-center gap-2 font-bold text-[#E1B362] text-lg">
                        {{ number_format($exchangeData['quoteAmount'], 2) }} {{ $exchangeData['quoteCurrencyCode'] ?? '' }}
                            <img src="{{asset('storage/' .$exchangeData['quoteCurrencyFlag']) ?? ''}}"
                                 class="w-5 h-5 rounded-full" alt="">
                        </span>
                    </div>

                    <hr class="border-gray-800">
                    <div class="text-sm space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Exchange Rate</span>
                            <span class="font-semibold text-gray-600">
                    1 {{ $exchangeData['baseCurrencyCode'] ?? '' }} = {{ number_format($exchangeData['exchangeRate'], 2) }} {{ $exchangeData['quoteCurrencyCode'] ?? '' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Processing Fee</span>
                            <span class="font-semibold text-gray-600">
                    {{ number_format($exchangeData['processingFee'] ?? 0, 2) }} {{ $exchangeData['baseCurrencyCode'] ?? '' }}</span>
                        </div>
                    </div>

                @endif
            </div>

            <hr class="border-gray-800 mt-6 pt-2">

            @if($baseCurrencyType === 'fiat')

                <div x-data="{ activeTab: 0 }">
                    <h4 class="font-bold text-white mb-4 text-center md:text-left">Transfer to this Account</h4>

                    @if(count($companyBankAccounts) > 1)
                        <!-- Tab Headers -->
                        <div class="flex border-b border-gray-700 mb-4 justify-center md:justify-start">
                            @foreach($companyBankAccounts as $index => $account)
                                <button
                                    @click="activeTab = {{ $index }}"
                                    :aria-pressed="activeTab === {{ $index }}"
                                    :class="{
                                            'border-b-2 border-[#E1B362] text-[#E1B362] bg-gray-900 shadow-md': activeTab === {{ $index }},
                                            'text-gray-400 hover:text-white hover:bg-gray-800': activeTab !== {{ $index }}
                                        }"
                                    class="px-4 py-2 font-semibold text-sm focus:outline-none transition-all rounded-t-lg flex items-center gap-2"
                                    role="tab"
                                    type="button">
                                    <span class="truncate">{{ $account->tab_name }}</span>
                                    <i x-show="activeTab === {{ $index }}" x-cloak data-lucide="check"
                                       class="w-4 h-4 text-[#E1B362]"></i>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Tab Content -->
                    @foreach($companyBankAccounts as $index => $account)
                        <div x-show="activeTab === {{ $index }}" x-cloak>
                            <div class="flex flex-col md:flex-row gap-6 items-center">
                                <!-- Text Details -->
                                <div class="w-full md:flex-1 space-y-4 text-sm">
                                    <hr class="border-gray-800">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-400">Bank Name</span>
                                        <span class="font-semibold text-white">{{ $account->bank_name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-400">Account Name</span>
                                        <span class="font-semibold text-white">{{ $account->account_name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center" x-data="{ copied: false }">
                                        <span class="text-gray-400">Account Number</span>
                                        <div class="flex items-center gap-2">
                                                <span
                                                    class="font-semibold text-white">{{ $account->account_number }}</span>
                                            <button
                                                @click="navigator.clipboard.writeText('{{ $account->account_number }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                                class="text-gray-400 hover:text-[#E1B362] transition-colors"
                                                title="Copy account number">
                                                <i x-show="!copied" data-lucide="copy" class="w-4 h-4"></i>
                                                <i x-show="copied" x-cloak data-lucide="check"
                                                   class="w-4 h-4 text-green-400"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- QR Code -->
                                @if($account->bank_account_qr_code)
                                    <div class="md:w-auto flex flex-col items-center">
                                        <img src="{{ Storage::url($account->bank_account_qr_code) }}" alt="QR Code"
                                             style="width:150px;max-width:100%;"
                                             class="p-2 bg-gray-700 rounded-lg mt-0 md:mt-10"/>
                                        <p class="text-xs text-gray-400 mt-2">Scan to pay</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
                <p class="text-sm text-gray-400 mb-6">You can upload multiple proof of payments at once.</p>

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
                           accept="image/*"
                           x-data
                           x-on:change="
                                  if ($event.target.files[0] && $event.target.files[0].size > 5242880) {
                                      $event.target.value = '';
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'File too large',
                                          text: 'The selected file exceeds 5MB. Please choose a smaller file.',
                                          background: '#1f2937',
                                          color: '#fff',
                                          confirmButtonColor: '#E1B362'
                                      });
                                  }
                              ">

                    <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Loading Skeleton -->
                        <div wire:loading wire:target="newPaymentSlip">
                            <div
                                class="w-full h-24 bg-gray-800 rounded-lg border border-gray-600 flex flex-col items-center justify-center">
                                <div
                                    class="w-8 h-8 border-4 border-t-[#E1B362] border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin mb-2"></div>
                                <span class="text-xs text-gray-400">Uploading...</span>
                            </div>
                        </div>

                        <!-- Existing Previews -->
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
                <button type="submit"
                        @click="window.intentionallyCancelling = true"
                        class="brand-gradient flex-1 text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-center"
                        @if(!$paymentSlips) disabled @endif>
                    Submit
                </button>
            </div>
        </form>

        <div>
            <div class="mt-6 bg-yellow-900/20 rounded-lg p-4">
                @if($exchangeData['baseCurrencyCode'] === 'PHP')
                    <div class="text-xs">
                        <div class="flex items-start space-x-3 mb-3">
                            <i data-lucide="alert-triangle"
                               class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                            <h3 class="text-sm font-semibold text-yellow-300">Important notice</h3>
                        </div>

                        <ul class="text-xs space-y-2 text-yellow-100">
                            <li class="flex items-center space-x-2">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-400"></i>
                                <span>Transfers are non-refundable.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="hash" class="w-4 h-4 text-yellow-400"></i>
                                <span>Please make sure you send the exact amount.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="clock" class="w-4 h-4 text-yellow-400"></i>
                                <span>Check your daily transfer limit before proceeding.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="credit-card" class="w-4 h-4 text-yellow-400"></i>
                                <span>Review transfer fees in advance.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="refresh-ccw" class="w-4 h-4 text-yellow-400"></i>
                                <span>Know the expected transfer processing time.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-yellow-400"></i>
                                <span>Wait for confirmation before exiting the app.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="file-text" class="w-4 h-4 text-yellow-400"></i>
                                <span>Save the order number for future reference.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-yellow-400"></i>
                                <span>Be wary of unexpected money requests.</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i data-lucide="link-2" class="w-4 h-4 text-yellow-400"></i>
                                <span>Avoid phishing scams and suspicious links.</span>
                            </li>
                        </ul>

                        <div class="mt-3 space-y-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-yellow-100">•</span>
                                <img src="{{ asset('storage/images/instapay.png') }}" alt="Instapay"
                                     class="h-5 w-20">
                                <span class="text-yellow-100">transfers only</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-yellow-100">•</span>
                                <img src="{{ asset('storage/images/pesonet.png') }}" alt="PESONet" class="h-5 w-20">
                                <span class="text-yellow-100">transfers only</span>
                            </div>
                        </div>
                    </div>

                @elseif($exchangeData['baseCurrencyCode'] === 'USDT')
                    <div class="flex items-start space-x-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <div class="text-xs">
                            <h3 class="text-sm font-semibold text-yellow-300 mb-1">Important notice</h3>
                            <p class="text-yellow-100">Please confirm that you are depositing USDT to this address
                                on the TRC20
                                network. Mismatched address information may result in the permanent loss of your
                                assets.</p>
                        </div>
                    </div>

                @else
                    <div>
                        <div class="flex items-start space-x-3 mb-3">
                            <i data-lucide="alert-triangle"
                               class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                            <h3 class="text-sm font-semibold text-yellow-300">Important notice</h3>
                        </div>

                        <ul class="text-xs space-y-2 text-yellow-100">
                            <li class="flex items-start space-x-2">
                                <i data-lucide="user-check"
                                   class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Verify recipient details; transfers are non-refundable.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="clock" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Check your daily transfer limit before proceeding.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="credit-card"
                                   class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Review transfer fees in advance.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="refresh-ccw"
                                   class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Know the expected transfer processing time.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="check-circle"
                                   class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Wait for confirmation before exiting the app.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="file-text" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Save the order number for future reference.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="alert-circle"
                                   class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Be wary of unexpected money requests.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i data-lucide="link-2" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                                <span>Avoid phishing scams and suspicious links.</span>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @if(!empty($exchangeData) && ($exchangeData['baseAmount'] ?? 0) > 0)
        <div
            x-data="{ showCashbackModal: true }"
            x-show="showCashbackModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-cloak
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showCashbackModal = false"></div>

            <!-- Modal Content -->
            <div
                x-show="showCashbackModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="relative w-full max-w-md p-8 text-center bg-gradient-to-br from-gray-900 to-gray-800 border border-yellow-500/30 rounded-2xl shadow-2xl"
            >
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-yellow-500/10 mb-6">
                    <i data-lucide="gift" class="w-12 h-12 text-yellow-400"></i>
                </div>

                <!-- Title -->
                <h3 class="text-3xl font-bold text-white">Congratulations!</h3>
                <p class="mt-2 text-gray-300">You've earned a special cashback reward.</p>

                <!-- Cashback Amount -->
                <div class="my-8">
                    <p class="text-lg text-yellow-400">You get <span class="font-bold">0.1%</span> back</p>
                    <p class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-500 py-2">
                        {{ number_format($exchangeData['baseAmount'] * 0.001, 2) }}
                        <span class="text-3xl">{{ $exchangeData['baseCurrencyCode'] }}</span>
                    </p>
                </div>


                <!-- Payment Details -->
                <div class="mt-6 text-left text-sm space-y-2 border-t border-gray-700 pt-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Original Amount:</span>
                        <span class="font-medium text-gray-400 line-through">
                                {{ number_format($exchangeData['baseAmount'], 2) }} {{ $exchangeData['baseCurrencyCode'] }}
                            </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Cashback Reward (0.1%):</span>
                        <span class="font-medium text-green-400">
                                - {{ number_format($exchangeData['baseAmount'] * 0.001, 2) }} {{ $exchangeData['baseCurrencyCode'] }}
                            </span>
                    </div>
                    <div class="flex justify-between text-base mt-2 pt-2 border-t border-gray-700">
                        <span class="font-bold text-white">You Now Pay:</span>
                        <span class="font-extrabold text-yellow-400">
                                {{ number_format($exchangeData['baseAmount'] - ($exchangeData['baseAmount'] * 0.001), 2) }} {{ $exchangeData['baseCurrencyCode'] }}
                            </span>
                    </div>
                </div>
                <!-- Close Button -->
                <button
                    @click="showCashbackModal = false"
                    class="mt-8 w-full bg-yellow-500 text-gray-900 font-bold py-3 px-4 rounded-lg hover:bg-yellow-600 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 focus:ring-offset-gray-900"
                >
                    Proceed!
                </button>
            </div>
        </div>
    @endif

</div>
