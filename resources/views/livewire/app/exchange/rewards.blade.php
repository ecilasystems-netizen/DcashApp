<div>
    <x-slot name="header">
        <header class="bg-gray-950/95 backdrop-blur-md sticky top-0 z-50 border-b border-gray-800/50">

            <div class="px-4 lg:px-6 py-4 flex justify-between items-center max-w-7xl mx-auto">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}"
                       class="p-2 rounded-xl bg-gray-800/60 hover:bg-gray-700 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5 text-white"></i>
                    </a>
                    <div>
                        <h2 class="font-bold text-lg text-white">Rewards</h2>
                        <p class="text-xs text-gray-400">Earn DCoins</p>
                    </div>
                </div>

                <!-- Desktop Header -->
                <div class="hidden lg:block">
                    <h1 class="text-2xl font-bold text-white">Rewards Center</h1>
                    <p class="text-gray-400 text-sm">Earn DCoins by referring friends</p>
                </div>

                <!-- Rewards Balance -->
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-gray-400">Your Balance</p>
                        <p class="text-sm font-semibold text-white">{{ number_format($totalRewards, 2) }} DCoins</p>
                    </div>
                    <button
                        class="bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500 hover:to-yellow-400 text-black font-bold px-4 py-2 rounded-xl transition-all duration-200 shadow-lg hover:shadow-yellow-500/25">
                        <span class="sm:hidden">{{ number_format($totalRewards, 0) }}</span>
                        <span class="hidden sm:inline">Redeem</span>
                    </button>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="min-h-screen bg-gray-950" x-data="{
                            copiedCode: false,
                            copiedLink: false,
                            copyToClipboard(text, type) {
                                navigator.clipboard.writeText(text).then(() => {
                                    if (type === 'code') {
                                        this.copiedCode = true;
                                        setTimeout(() => this.copiedCode = false, 2000);
                                    } else if (type === 'link') {
                                        this.copiedLink = true;
                                        setTimeout(() => this.copiedLink = false, 2000);
                                    }
                                });
                            }
                        }">
        <div class="lg:py-8 space-y-8">

            <!-- Hero Section -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-yellow-600/20 via-gray-900 to-gray-900 rounded-3xl p-8 lg:p-12 border border-yellow-500/20">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-600/5 to-transparent"></div>
                <div class="relative z-10 grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-yellow-500/20 rounded-2xl">
                                <i data-lucide="gift" class="w-8 h-8 text-yellow-400"></i>
                            </div>
                            <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-semibold">DCoins Commissions</span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">
                            Refer Friends &
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600">Earn DCoins</span>
                        </h2>
                        <p class="text-gray-400 text-lg mb-6">
                            Earn DCoins for every friend you invite to trade. Your can redeem DCoins into any of our
                            supported currencies .
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-white">{{ number_format($totalRewards, 0) }}</p>
                                <p class="text-xs text-gray-400">DCoins Earned</p>
                            </div>
                            <div class="w-px h-12 bg-gray-700"></div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-white">0</p>
                                <p class="text-xs text-gray-400">Friends Referred</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex justify-center">
                        <div class="relative">
                            <div
                                class="w-64 h-64 bg-gradient-to-br from-yellow-500/20 to-transparent rounded-full flex items-center justify-center">
                                <div
                                    class="w-48 h-48 bg-gradient-to-br from-yellow-600/30 to-yellow-400/10 rounded-full flex items-center justify-center">
                                    <i data-lucide="users" class="w-24 h-24 text-yellow-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Share Section -->
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <!-- Referral Code -->
                    <div class="group">
                        <label class="text-xs text-gray-400 mb-1 block">Referral Code</label>
                        <div
                            class="flex items-center bg-gray-800/60 border border-gray-700 rounded-lg p-3 group-hover:border-yellow-500/50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="font-mono text-white text-sm font-semibold truncate">{{ $referralCode }}</p>
                            </div>
                            <button @click="copyToClipboard('{{ $referralCode }}', 'code')"
                                    class="ml-2 p-1.5 bg-yellow-500/20 hover:bg-yellow-500/30 rounded-md transition-colors flex-shrink-0">
                                <i x-show="!copiedCode" data-lucide="copy" class="w-4 h-4 text-yellow-400"></i>
                                <i x-show="copiedCode" data-lucide="check" class="w-4 h-4 text-green-400"></i>
                            </button>
                        </div>
                        <p x-show="copiedCode" x-transition class="text-xs text-green-400 mt-1">Copied!</p>
                    </div>

                    <!-- Referral Link -->
                    <div class="group">
                        <label class="text-xs text-gray-400 mb-1 block">Referral Link</label>
                        <div
                            class="flex items-center bg-gray-800/60 border border-gray-700 rounded-lg p-3 group-hover:border-yellow-500/50 transition-colors">
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <p class="font-mono text-white text-xs truncate">{{ $referralLink }}</p>
                            </div>
                            <button @click="copyToClipboard('{{ $referralLink }}', 'link')"
                                    class="ml-2 p-1.5 bg-yellow-500/20 hover:bg-yellow-500/30 rounded-md transition-colors flex-shrink-0">
                                <i x-show="!copiedLink" data-lucide="copy" class="w-4 h-4 text-yellow-400"></i>
                                <i x-show="copiedLink" data-lucide="check" class="w-4 h-4 text-green-400"></i>
                            </button>
                        </div>
                        <p x-show="copiedLink" x-transition class="text-xs text-green-400 mt-1">Link copied!</p>
                    </div>
                </div>

                <!-- Social Share -->
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-800/50">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                        <i data-lucide="send" class="w-6 h-6 text-yellow-400"></i>
                        Quick Share
                    </h3>

                    <div class="grid grid-cols-4 gap-2">
                        <a href="#"
                           class="group flex flex-col items-center gap-3 p-4 bg-gray-800/40 hover:bg-blue-500/20 rounded-xl transition-all duration-200">
                            <div
                                class="w-12 h-12 bg-blue-500/20 group-hover:bg-blue-500/30 rounded-xl flex items-center justify-center transition-colors">
                                <i data-lucide="send" class="w-6 h-6 text-blue-400"></i>
                            </div>
                            <span class="text-sm text-gray-400 group-hover:text-blue-400">Telegram</span>
                        </a>

                        <a href="#"
                           class="group flex flex-col items-center gap-3 p-4 bg-gray-800/40 hover:bg-green-500/20 rounded-xl transition-all duration-200">
                            <div
                                class="w-12 h-12 bg-green-500/20 group-hover:bg-green-500/30 rounded-xl flex items-center justify-center transition-colors">
                                <i data-lucide="message-circle" class="w-6 h-6 text-green-400"></i>
                            </div>
                            <span class="text-sm text-gray-400 group-hover:text-green-400">WhatsApp</span>
                        </a>

                        <a href="#"
                           class="group flex flex-col items-center gap-3 p-4 bg-gray-800/40 hover:bg-gray-400/20 rounded-xl transition-all duration-200">
                            <div
                                class="w-12 h-12 bg-gray-500/20 group-hover:bg-gray-500/30 rounded-xl flex items-center justify-center transition-colors">
                                <i data-lucide="twitter" class="w-6 h-6 text-gray-400"></i>
                            </div>
                            <span class="text-sm text-gray-400 group-hover:text-gray-300">X</span>
                        </a>

                        <a href="#"
                           class="group flex flex-col items-center gap-3 p-4 bg-gray-800/40 hover:bg-blue-600/20 rounded-xl transition-all duration-200">
                            <div
                                class="w-12 h-12 bg-blue-600/20 group-hover:bg-blue-600/30 rounded-xl flex items-center justify-center transition-colors">
                                <i data-lucide="facebook" class="w-6 h-6 text-blue-500"></i>
                            </div>
                            <span class="text-sm text-gray-400 group-hover:text-blue-500">Facebook</span>
                        </a>

                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class=" backdrop-blur-sm rounded-2xl p-3 ">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-white mb-3">How It Works</h3>
                    <p class="text-gray-400">Start earning in just 3 simple steps</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent rounded-2xl group-hover:from-yellow-500/10 transition-colors"></div>
                        <div class="relative p-6 text-center">
                            <div
                                class="absolute -top-3 -right-3 w-8 h-8 bg-yellow-500 text-black rounded-full flex items-center justify-center font-bold text-sm">
                                1
                            </div>
                            <div
                                class="w-16 h-16 bg-yellow-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="share-2" class="w-8 h-8 text-yellow-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-white mb-2">Share Your Code</h4>
                            <p class="text-gray-400 text-sm">Send your unique referral code to friends and family</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent rounded-2xl group-hover:from-blue-500/10 transition-colors"></div>
                        <div class="relative p-6 text-center">
                            <div
                                class="absolute -top-3 -right-3 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                2
                            </div>
                            <div
                                class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="user-plus" class="w-8 h-8 text-blue-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-white mb-2">Friend Joins</h4>
                            <p class="text-gray-400 text-sm">Your friend signs up using your referral code</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent rounded-2xl group-hover:from-green-500/10 transition-colors"></div>
                        <div class="relative p-6 text-center">
                            <div
                                class="absolute -top-3 -right-3 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                3
                            </div>
                            <div
                                class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="coins" class="w-8 h-8 text-green-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-white mb-2">Earn DCoins</h4>
                            <p class="text-gray-400 text-sm">Get DCoins when your referral make the first trade</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl p-6 border border-gray-800/50">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <i data-lucide="activity" class="w-6 h-6 text-yellow-400"></i>
                    Recent Activity
                </h3>

                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-800/60 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="users" class="w-8 h-8 text-gray-500"></i>
                    </div>
                    <p class="text-gray-400 text-lg mb-2">No referrals yet</p>
                    <p class="text-gray-500 text-sm">Start sharing your code to see your earnings here</p>
                </div>
            </div>
        </div>
    </div>
</div>
