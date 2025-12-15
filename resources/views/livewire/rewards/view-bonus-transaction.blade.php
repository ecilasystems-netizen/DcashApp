<div x-data="{ confirmClaim: false }">

    @push('scripts')
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            window.addEventListener('load', () => {
                if (typeof html2canvas === 'undefined') {
                    console.error('html2canvas not loaded');
                    return;
                }

                async function waitImages(el) {
                    await Promise.all(Array.from(el.getElementsByTagName('img'))
                        .map(img => img.complete ? Promise.resolve() : new Promise(resolve => img.onload = resolve)));
                }

                async function captureElement(id, backgroundColor = '#1f2937') {
                    const el = document.getElementById(id);
                    if (!el) return null;
                    await waitImages(el);
                    try {
                        const canvas = await html2canvas(el, {
                            backgroundColor,
                            scale: 2,
                            useCORS: true,
                            allowTaint: true,
                            logging: false,
                            foreignObjectRendering: true
                        });
                        return canvas.toDataURL('image/png');
                    } catch (e) {
                        console.error(e);
                        return null;
                    }
                }

                async function handleShare() {
                    const dataUrl = await captureElement('bonusReceiptContent');
                    if (!dataUrl) return alert('Failed to generate image');
                    const blob = await fetch(dataUrl).then(r => r.blob());
                    const file = new File([blob], `dcash-bonus-${Date.now()}.png`, {type: 'image/png'});

                    if (navigator.share && navigator.canShare && navigator.canShare({files: [file]})) {
                        try {
                            await navigator.share({
                                title: 'Dcash Bonus Receipt',
                                text: 'My bonus receipt from Dcash',
                                files: [file]
                            });
                            return;
                        } catch (err) {
                            console.error('Web Share failed', err);
                        }
                    }

                    // Desktop fallback: open share modal with download link
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                    modal.innerHTML = `
                                                <div class="bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
                                                    <h3 class="text-lg font-bold text-white mb-4">Share Bonus Receipt</h3>
                                                    <div class="grid grid-cols-1 gap-3">
                                                        <a id="downloadLink" class="block text-center p-3 bg-blue-600 rounded text-white">Download Image</a>
                                                        <button id="copyLinkBtn" class="w-full p-3 bg-gray-700 rounded text-white">Copy Page Link</button>
                                                        <button id="closeShare" class="mt-3 w-full p-2 border border-gray-600 rounded text-gray-400">Cancel</button>
                                                    </div>
                                                </div>
                                            `;
                    document.body.appendChild(modal);
                    const dl = modal.querySelector('#downloadLink');
                    dl.href = dataUrl;
                    dl.download = `dcash-bonus-${Date.now()}.png`;

                    modal.querySelector('#copyLinkBtn').addEventListener('click', async () => {
                        await navigator.clipboard.writeText(window.location.href);
                        alert('Link copied to clipboard');
                    });
                    modal.querySelector('#closeShare').addEventListener('click', () => modal.remove());
                }

                async function handleDownload() {
                    const dataUrl = await captureElement('bonusReceiptContent', '#ffffff');
                    if (!dataUrl) return alert('Failed to generate image');
                    const a = document.createElement('a');
                    a.href = dataUrl;
                    a.download = `dcash-bonus-${Date.now()}.png`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                }

                Livewire.on('captureAndShare', () => window.requestAnimationFrame(handleShare));
                Livewire.on('captureAndDownload', () => window.requestAnimationFrame(handleDownload));
            });
        </script>
    @endpush

    @push('styles')
        <style>
            @media print {
                body {
                    background-color: #1f2937 !important;
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }

                .no-print {
                    display: none;
                }

                .receipt-container {
                    box-shadow: none !important;
                    border: none !important;
                }
            }

            .receipt-container {
                transform: translateZ(0);
                backface-visibility: hidden;
                -webkit-font-smoothing: subpixel-antialiased;
            }
        </style>
    @endpush

    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80 no-print">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button onclick="window.location.href='{{ $backUrl }}'" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </button>
                    <div>
                        <p class="text-xs text-gray-400">Rewards</p>
                        <h2 class="font-bold text-xl text-white">Bonus Receipt</h2>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="$dispatch('captureAndShare')" id="shareButton"
                            class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600">
                        <i data-lucide="share-2" class="w-5 h-5 text-white"></i>
                    </button>
                    <button wire:click="$dispatch('captureAndDownload')" id="downloadButton"
                            class="p-1 rounded-lg bg-yellow-500">
                        <i data-lucide="download" class="w-5 h-5 text-white"></i>
                    </button>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="max-w-2xl mx-auto p-6">
        <div id="bonusReceiptContent"
             class="max-w-sm mx-auto bg-gray-900/80 border border-gray-700 rounded-lg shadow-lg receipt-container">
            <div class="p-4 text-center border-b border-gray-700">
                <img src="{{ asset('storage/logo-with-text-white.png') }}" alt="Dcash Logo"
                     class="w-20 h-10 mx-auto mb-2"/>
                <p class="text-lg font-bold text-white"> {{ number_format($bonusData['bonus_amount'], 2) }} DCOINS</p>
                <p class="text-xs text-gray-400">Reward: {{ ucfirst($bonusData['notes']) }}</p>
            </div>

            <div class="p-4 text-xs">
                <ul class="space-y-3">
                    <li class="flex justify-between">
                        <span class="text-gray-400">Type</span>
                        <span class="text-white capitalize">{{ $bonusData['type'] }}</span>
                    </li>


                    <li class="flex justify-between items-start">
                        <span class="text-gray-400">To</span>
                        <div class="text-right">
                            <p class="text-white font-medium">{{ $bonusData['sender_name'] }}</p>
                            <p class="text-xs text-gray-400 break-all">{{ $bonusData['user_email'] }}</p>
                        </div>
                    </li>

                    @if($bonusData['is_referral_bonus'] && isset($bonusData['referral_details']))
                        <li class="flex justify-between items-start">
                            <span class="text-gray-400">Referral</span>
                            <div class="text-right">
                                <p class="text-white">{{ $bonusData['referral_details']['referrer_name'] ?? '-' }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $bonusData['referral_details']['referral_code'] ?? '-' }}</p>
                            </div>
                        </li>
                    @endif

                    @if($bonusData['notes'])
                        <li>
                            <span class="text-gray-400">Notes</span>
                            <p class="text-white text-sm mt-1">{{ $bonusData['notes'] }}</p>
                        </li>
                    @endif

                    <li class="flex justify-between">
                        <span class="text-gray-400">Created</span>
                        <span class="text-white text-xs">{{ $bonusData['created_at'] }}</span>
                    </li>

                    <li class="flex justify-between">
                        <span class="text-gray-400">Reference</span>
                        <span class="font-mono text-gray-300 text-xs">{{ $bonusData['id'] }}</span>
                    </li>
                </ul>
            </div>

            <!-- Receipt Footer -->
            <div class="p-4 text-center border-t border-gray-700">
                @if($randomAd)
                    <div class="mb-3 rounded-lg overflow-hidden">
                        @if($randomAd->link_url)
                            <a href="{{ route('ad.click', $randomAd->id) }}" target="_blank">
                                <img src="{{ asset('storage/' . $randomAd->image_path) }}"
                                     alt="{{ $randomAd->title }}"
                                     class="w-full h-auto object-cover">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $randomAd->image_path) }}" alt="{{ $randomAd->title }}"
                                 class="w-full h-auto object-cover">
                        @endif
                    </div>
                @else
                    <div
                        class="mb-3 bg-gray-800/50 border-2 border-dashed border-gray-600 rounded-lg p-6 flex flex-col items-center justify-center">
                        <i data-lucide="image" class="w-10 h-10 text-gray-500 mb-1"></i>
                        <p class="text-[10px] text-gray-500">Advertisement Space</p>
                    </div>
                @endif

                <p class="text-[10px] text-gray-500 mb-2">
                    Thank you for using Dcash Exchange.
                </p>
                <a href="https://www.trustpilot.com/review/dcashwallet.com" target="_blank"
                   class="inline-flex items-center text-xs text-green-500 hover:text-green-400 transition-colors">
                    <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                    Rate us on Trustpilot
                </a>
            </div>
        </div>

        <div
            class="max-w-sm mx-auto mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 no-print">

            <a href="{{route('rewards.redeem')}}"
               class="flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                Redeem
            </a>


            <a href="{{route('dashboard')}}"
               class="flex items-center justify-center px-4 py-2 brand-gradient hover:opacity-90 text-white rounded-lg transition-colors">
                <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                Go Back
            </a>

        </div>
    </div>


</div>
