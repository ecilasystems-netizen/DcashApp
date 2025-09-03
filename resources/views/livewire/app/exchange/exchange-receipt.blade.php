<div>

    @push('scripts')
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            // Wait for both DOM and html2canvas to be ready
            window.addEventListener('load', () => {
                if (typeof html2canvas === 'undefined') {
                    console.error('html2canvas not loaded');
                    return;
                }

                // Capture receipt as image
                async function captureReceipt() {
                    const element = document.getElementById('receiptContent');
                    if (!element) {
                        console.error('Receipt element not found');
                        return null;
                    }

                    try {
                        // Wait for any images to load
                        await Promise.all(Array.from(element.getElementsByTagName('img'))
                            .map(img => img.complete ? Promise.resolve() : new Promise(resolve => img.onload = resolve)));

                        const canvas = await html2canvas(element, {
                            backgroundColor: '#1f2937',
                            scale: 2,
                            logging: true,
                            useCORS: true,
                            allowTaint: true,
                            foreignObjectRendering: true
                        });
                        return canvas.toDataURL('image/png');
                    } catch (error) {
                        console.error('Error capturing receipt:', error);
                        return null;
                    }
                }

                // Share functionality
                window.addEventListener('captureAndShare', async () => {
                    try {
                        const dataUrl = await captureReceipt();
                        if (!dataUrl) {
                            alert('Failed to generate receipt image');
                            return;
                        }

                        const blob = await fetch(dataUrl).then(r => r.blob());
                        const file = new File([blob], `dcash-receipt-${Date.now()}.png`, {type: 'image/png'});

                        if (navigator.share && navigator.canShare({files: [file]})) {
                            await navigator.share({
                                title: 'Dcash Exchange Receipt',
                                text: 'Check out my exchange receipt from Dcash!',
                                files: [file]
                            });
                            return;
                        }

                        // Show sharing modal for desktop
                        const shareModal = document.createElement('div');
                        shareModal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                        shareModal.innerHTML = `
                            <div class="bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
                                <h3 class="text-lg font-bold text-white mb-4">Share Receipt</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <button class="share-btn flex items-center justify-center gap-2 p-3 bg-[#1DA1F2] rounded-lg text-white" data-type="twitter">
                                        <i data-lucide="twitter" class="w-5 h-5"></i>
                                        Twitter
                                    </button>
                                    <button class="share-btn flex items-center justify-center gap-2 p-3 bg-[#25D366] rounded-lg text-white" data-type="whatsapp">
                                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                                        WhatsApp
                                    </button>
                                    <button class="share-btn flex items-center justify-center gap-2 p-3 bg-[#EA4335] rounded-lg text-white" data-type="email">
                                        <i data-lucide="mail" class="w-5 h-5"></i>
                                        Email
                                    </button>
                                    <button class="share-btn flex items-center justify-center gap-2 p-3 bg-gray-600 rounded-lg text-white" data-type="copy">
                                        <i data-lucide="copy" class="w-5 h-5"></i>
                                        Copy Link
                                    </button>
                                </div>
                                <button class="mt-4 w-full p-2 border border-gray-600 rounded-lg text-gray-400 hover:bg-gray-700" onclick="this.parentElement.parentElement.remove()">
                                    Cancel
                                </button>
                            </div>
                        `;

                        document.body.appendChild(shareModal);
                        lucide.createIcons();

                        shareModal.querySelectorAll('.share-btn').forEach(btn => {
                            btn.addEventListener('click', async () => {
                                const type = btn.dataset.type;
                                const shareUrl = window.location.href;

                                switch (type) {
                                    case 'twitter':
                                        window.open(`https://twitter.com/intent/tweet?text=Check out my exchange receipt from Dcash!&url=${encodeURIComponent(shareUrl)}`, '_blank');
                                        break;
                                    case 'whatsapp':
                                        window.open(`https://wa.me/?text=Check out my exchange receipt from Dcash! ${encodeURIComponent(shareUrl)}`, '_blank');
                                        break;
                                    case 'email':
                                        window.location.href = `mailto:?subject=Dcash Exchange Receipt&body=Check out my exchange receipt from Dcash! ${encodeURIComponent(shareUrl)}`;
                                        break;
                                    case 'copy':
                                        await navigator.clipboard.writeText(shareUrl);
                                        alert('Link copied to clipboard!');
                                        break;
                                }
                                shareModal.remove();
                            });
                        });
                    } catch (error) {
                        console.error('Error sharing:', error);
                        alert('Failed to share receipt');
                    }
                });

                // Wire up Livewire event
                Livewire.on('captureAndShare', () => {
                    window.dispatchEvent(new CustomEvent('captureAndShare'));
                });
            });
        </script>
    @endpush
    @push('styles')
        <style>

            @media (min-width: 1024px) {
                body {
                    padding-bottom: 0;
                }
            }

            @media print {
                body {
                    padding-bottom: 0;
                    background-color: #1f2937 !important; /* bg-gray-800 */
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }

                .no-print {
                    display: none;
                }

                main {
                    margin-left: 0 !important;
                }

                .receipt-container {
                    box-shadow: none !important;
                    border: none !important;
                }
            }

            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            .receipt-container {
                transform: translateZ(0);
                backface-visibility: hidden;
                -webkit-font-smoothing: subpixel-antialiased;
            }
        </style>
    @endpush
    <x-slot name="header">
        <header
            class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80 no-print">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button onclick="window.location.href='{{ $backUrl }}'" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </button>
                    <div>
                        <p class="text-xs text-gray-400">Transaction</p>
                        <h2 class="font-bold text-xl text-white">Receipt</h2>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="$dispatch('captureAndShare')"
                            id="shareButton"
                            class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600 transition-colors">
                        <i data-lucide="share-2" class="w-5 h-5"></i>
                    </button>
                    <button wire:click="$dispatch('captureAndDownload')"
                            id="downloadButton"
                            class="p-1 rounded-lg brand-gradient hover:opacity-90 transition-opacity">
                        <i data-lucide="download" class="w-5 h-5"></i>
                    </button>

                </div>
            </div>
        </header>
    </x-slot>

    <div class="max-w-2xl mx-auto ">


        <div class="p-1 lg:p-0 lg:py-8">

            <div
                id="receiptContent"
                class="max-w-sm mx-auto bg-gray-900/80 border border-gray-700 rounded-lg shadow-lg receipt-container">
                <!-- Receipt Header -->
                <div class="p-6 text-center border-b border-gray-700">
                    <img
                        src="/imgs/logo-only.png"
                        alt="Dcash Logo"
                        class="w-12 h-12 mx-auto mb-4"/>

                    <p class="text-xl font-bold text-white mt-1">{{ number_format($transactionData['quoteAmount'], 2) .' '.$transactionData['quoteCurrencyCode']}}</p>
                    <p class="text-sm text-gray-400">sent from {{strtoupper($transactionData['senderName'])}}</p>
                </div>

                <!-- Receipt Details -->
                <div class="p-6 text-sm">
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-gray-400">Status</span>
                            <span class="font-medium flex items-center gap-1.5 {{
                                match($transactionData['status']) {
                                    'completed' => 'text-green-400',
                                    'pending_payment' => 'text-yellow-400',
                                    'pending_confirmation' => 'text-yellow-400',
                                    'processing' => 'text-blue-400',
                                    'failed' => 'text-red-400',
                                    'cancelled' => 'text-gray-400',
                                    default => 'text-white'
                                }
                            }}">
                                <i data-lucide="{{
                                    match($transactionData['status']) {
                                        'completed' => 'check-circle',
                                        'pending_payment', 'pending_confirmation' => 'clock',
                                        'processing' => 'refresh-cw',
                                        'failed' => 'x-circle',
                                        'cancelled' => 'slash',
                                        default => 'help-circle'
                                    }
                                }}" class="w-4 h-4"></i>
                                {{ucwords(str_replace('_', ' ', $transactionData['status']))}}
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-400">Transaction Type</span>
                            <span class="flex items-center gap-2">
                                <img src="{{asset('storage/'.$transactionData['baseCurrencyFlag']) ?? ''}}"
                                     class="w-6 h-6 rounded-full"
                                     alt="">
                                <i data-lucide="repeat" class="w-4 h-4 text-gray-400"></i>
                                <img src="{{asset('storage/'.$transactionData['quoteCurrencyFlag']) ?? ''}}"
                                     class="w-6 h-6 rounded-full"
                                     alt="">
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-400">You Sent</span>
                            <span
                                class="font-medium text-white">{{ number_format($transactionData['baseAmount'], 2) .' '.$transactionData['baseCurrencyCode']}}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-400">Exchange Rate</span>
                            <span class="font-medium text-white"
                            >{{number_format($transactionData['exchangeRate'], 2)}}</span
                            >
                        </li>
                        <li class="flex justify-between items-start">
                            <span class="text-gray-400">Recipient</span>
                            <div class="text-right">
                                <p class="font-medium text-white">{{$transactionData['recipientAccountName']}}</p>
                                <p class="font-medium text-white">{{$transactionData['recipientAccountNumber']}}</p>
                                <p class="font-medium text-white">({{$transactionData['recipientBankName']}})</p>
                            </div>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-400">Fee</span>
                            <span class="font-medium text-white">0.00 </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-400">Transaction ID</span>
                            <span class="font-mono text-xs text-gray-300"
                            >{{$transactionData['reference']}}</span
                            >
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-400">Date & Time</span>
                            <span class="font-medium text-white"
                            >{{$transactionData['transactionDate']}}</span
                            >
                        </li>
                    </ul>
                </div>
                <!-- Receipt Footer -->
                <div class="p-6 text-center border-t border-gray-700">
                    <p class="text-xs text-gray-500">
                        Thank you for using Dcash Exchange.
                    </p>
                </div>
            </div>


            <!-- Add action buttons -->
            <div class="max-w-sm mx-auto mt-6 flex justify-center space-x-4">
                <a href="{{route('dashboard')}}"
                   class="flex items-center px-4 py-2 brand-gradient hover:opacity-90 text-white rounded-lg transition-colors">
                    <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                    Back To Dashboard
                </a>

            </div>

        </div>
    </div>

</div>
