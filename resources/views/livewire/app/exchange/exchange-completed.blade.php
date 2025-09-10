<div>
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Transaction Status</h1>
            </div>
        </header>
    </x-slot>
    <div class="max-w-md w-full mx-auto text-center">
        <!-- Success Icon -->
        <div class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mx-auto success-animation">
            <i data-lucide="check" class="w-16 h-16 text-green-400"></i>
        </div>

        <!-- Message -->
        <h2 class="text-3xl font-bold text-white mt-6">Payment Successful</h2>
        <p class="text-gray-400 mt-2">Your transaction is being processed and the recipient will be credited
            shortly.</p>

        <!-- Transaction Summary -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 my-8 text-left space-y-4 text-sm">
            @if(session()->has('cashback_amount') && session('cashback_amount') > 0)
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Amount Sent</span>
                    <span class="font-semibold text-gray-400 line-through">
                        {{ number_format(session('baseAmount'), 2) }} {{ session('baseCurrencyCode') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Cashback Reward</span>
                    <span class="font-semibold text-green-400">
                        - {{ number_format(session('cashback_amount'), 2) }} {{ session('baseCurrencyCode') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-white">Total Paid</span>
                    <span class="font-bold text-white">
                        {{ number_format(session('baseAmount') - session('cashback_amount'), 2) }} {{ session('baseCurrencyCode') }}
                    </span>
                </div>
            @else
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Amount Sent</span>
                    <span class="font-semibold text-white">
                        {{ number_format(session('baseAmount'), 2) }} {{ session('baseCurrencyCode') }}
                    </span>
                </div>
            @endif
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Amount Received</span>
                <span
                    class="font-semibold text-[#E1B362]">{{number_format(session('quoteAmount'), 2)}} {{session('quoteCurrencyCode')}}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Recipient</span>
                <span
                    class="font-semibold text-white">{{session('recipientAccountName')}} <br/>{{session('recipientAccountNumber')}} - {{session('recipientBankName')}}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Transaction ID</span>
                <span class="font-mono text-xs text-gray-500">{{session('reference')}}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('exchange.receipt', ['ref' => session('reference'), 'backUrl' => route('dashboard', ['ref' => session('reference')])]) }}"
               class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                View Receipt
            </a>
            <a href="{{route('dashboard')}}"
               class="bg-gray-700 w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:bg-gray-600 transition-all">
                Back to Dashboard
            </a>
        </div>
    </div>

</div>
