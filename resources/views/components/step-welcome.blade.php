<div x-show="step === 1"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-10"
     x-transition:enter-end="opacity-100 translate-x-0"
     class="text-center py-4">

    <!-- Animated Wallet Icon -->
    <div class="relative w-24 h-24 mx-auto mb-6">
        <div
            class="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/20 to-green-600/20 animate-ping"></div>
        <div
            class="absolute inset-2 rounded-full bg-gradient-to-br from-green-400/30 to-green-600/30 animate-pulse"></div>
        <div
            class="absolute inset-3 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-110 transition-transform duration-300">
            <i data-lucide="wallet" class="w-10 h-10 text-white"></i>
        </div>
        <div
            class="absolute -top-1 -right-1 w-9 h-9 bg-green-700 rounded-full flex items-center justify-center animate-bounce border-2 border-gray-900">
            <span class="text-white text-sm font-bold">₦</span>
        </div>
    </div>

    <h3 class="text-2xl font-bold text-white mb-3">Welcome to Your Naira Wallet</h3>
    <p class="text-gray-400 mb-8 px-4">
        Create your virtual Nigerian bank account to enjoy seamless deposits, instant transfers,
        and convenient bill payments.
    </p>

    <!-- Features Grid -->
    <div class="grid grid-cols-2 gap-3 mb-8">
        <div
            class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 border border-blue-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
            <div
                class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                <i data-lucide="building-2" class="w-6 h-6 text-blue-400"></i>
            </div>
            <h4 class="font-semibold text-white text-sm mb-1">Bank Transfers</h4>
            <p class="text-xs text-gray-400">To any Nigerian bank</p>
        </div>

        <div
            class="bg-gradient-to-br from-purple-500/10 to-purple-600/10 border border-purple-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
            <div
                class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                <i data-lucide="smartphone" class="w-6 h-6 text-purple-400"></i>
            </div>
            <h4 class="font-semibold text-white text-sm mb-1">Bill Payments</h4>
            <p class="text-xs text-gray-400">Airtime, data & utilities</p>
        </div>

        <div
            class="bg-gradient-to-br from-green-500/10 to-green-600/10 border border-green-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
            <div
                class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                <i data-lucide="credit-card" class="w-6 h-6 text-green-400"></i>
            </div>
            <h4 class="font-semibold text-white text-sm mb-1">Virtual Account</h4>
            <p class="text-xs text-gray-400">Instant activation</p>
        </div>

        <div
            class="bg-gradient-to-br from-yellow-500/10 to-amber-600/10 border border-yellow-500/20 rounded-xl p-4 text-center hover:scale-105 transition-transform">
            <div
                class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center mb-3 mx-auto">
                <i data-lucide="shield-check" class="w-6 h-6 text-yellow-400"></i>
            </div>
            <h4 class="font-semibold text-white text-sm mb-1">Bank-Level Security</h4>
            <p class="text-xs text-gray-400">Protected & encrypted</p>
        </div>
    </div>

    <button @click="step = 2"
            class="w-full py-3.5 bg-gradient-to-r from-[#E1B362] to-amber-500 hover:from-amber-500 hover:to-[#E1B362] text-white rounded-xl font-bold transition-all shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2">
        Get Started
        <i data-lucide="arrow-right" class="w-5 h-5"></i>
    </button>
</div>
