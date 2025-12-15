<div>
    @push('styles')
        <style>
            .receipt-container {
                transform: translateZ(0);
                backface-visibility: hidden;
                -webkit-font-smoothing: subpixel-antialiased;
            }
        </style>
    @endpush

    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ $backUrl }}" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Wallet Transaction</p>
                        <h2 class="font-bold text-xl text-white">Receipt</h2>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="p-1 lg:p-0 lg:py-8">
            @if($transaction)
                <div id="receiptContent"
                     class="max-w-sm mx-auto bg-gray-900/80 border border-gray-700 rounded-lg shadow-lg receipt-container">
                    <!-- Receipt Header -->
                    <div class="p-6 text-center border-b border-gray-700">
                        <img
                            src="{{asset('storage/logo-with-text-white.png')}}"
                            alt="Dcash Logo"
                            class="w-26 h-12 mx-auto mb-4"/>
                        <p class="text-xl font-bold text-white mt-1">
                            {{ $transaction->direction === 'credit' ? '+' : '-' }}
                            {{ number_format($transaction->amount, 2) }} {{ $transaction->wallet->currency->code }}
                        </p>
                        <p class="text-sm text-gray-400">
                            {{ ucfirst($transaction->type) }} {{ $transaction->direction === 'credit' ? 'to' : 'from' }}
                            your wallet
                        </p>
                    </div>

                    <!-- Receipt Details -->
                    <div class="p-6 text-sm">
                        <ul class="space-y-4">
                            <li class="flex justify-between">
                                <span class="text-gray-400">Status</span>
                                <span class="font-medium flex items-center gap-1.5 {{
                                    match($transaction->status) {
                                        'completed' => 'text-green-400',
                                        'pending' => 'text-yellow-400',
                                        'failed' => 'text-red-400',
                                        'rejected' => 'text-red-400',
                                        default => 'text-white'
                                    }
                                }}">
                                    <i data-lucide="{{
                                        match($transaction->status) {
                                            'completed' => 'check-circle',
                                            'pending' => 'clock',
                                            'failed' => 'x-circle',
                                            'rejected' => 'x-octagon',
                                            default => 'help-circle'
                                        }
                                    }}" class="w-4 h-4"></i>
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-400">Transaction Type</span>
                                <span class="font-medium text-white">{{ ucfirst($transaction->type) }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-400">Direction</span>
                                <span class="font-medium text-white">{{ ucfirst($transaction->direction) }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-400">Amount</span>
                                <span
                                    class="font-medium text-white">{{ number_format($transaction->amount, 2) }} {{ $transaction->wallet->currency->code }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-400">Fee</span>
                                <span
                                    class="font-medium text-white">{{ number_format($transaction->charge, 2) }} {{ $transaction->wallet->currency->code }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-400">Description</span>
                                <span
                                    class="font-medium text-white text-right">{{ $transaction->description ?? 'N/A' }}</span>
                            </li>
                            <li class="flex justify-between items-center" x-data="{ copied: false }">
                                <span class="text-gray-400">Transaction ID</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs text-gray-300">{{ $transaction->reference }}</span>
                                    <button
                                        @click="navigator.clipboard.writeText('{{ $transaction->reference }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                        class="text-gray-400 hover:text-white transition-colors focus:outline-none"
                                        title="Copy transaction ID">
                                        <i x-show="!copied" data-lucide="copy" class="w-4 h-4"></i>
                                        <i x-show="copied" data-lucide="check" class="w-4 h-4 text-green-400"
                                           style="display: none;"></i>
                                    </button>
                                </div>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-400">Date & Time</span>
                                <span
                                    class="font-medium text-white">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Receipt Footer -->
                    <div class="p-6 text-center border-t border-gray-700">
                        <p class="text-xs text-gray-500">
                            Thank you for using Dcash.
                        </p>

                        <a href="https://www.trustpilot.com/review/dcashwallet.com"
                           target="_blank"
                           class="inline-flex items-center text-sm text-green-500 hover:text-green-400 transition-colors">
                            <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                            Rate us on Trustpilot
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="max-w-sm mx-auto mt-6 flex justify-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-4 py-2 bg-gradient-to-r from-[#E1B362] to-[#D4A853] hover:opacity-90 text-gray-900 font-bold rounded-lg transition-colors">
                        <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                        Back To Dashboard
                    </a>
                </div>
            @else
                <div class="text-center py-20">
                    <p class="text-white">Transaction details could not be loaded.</p>
                </div>
            @endif
        </div>
    </div>
</div>
