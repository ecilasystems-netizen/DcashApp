<div>
    <x-slot name="header">
        <header class="bg-gray-950/95 backdrop-blur-md sticky top-0 z-50 border-b border-gray-800/50">

            <div class="px-4 lg:px-6 py-4 flex justify-between items-center max-w-7xl mx-auto">


                <div class="flex items-center gap-4">
                    <a href="{{ route('rewards') }}" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">Rewards</p>
                        <h2 class="font-bold text-xl text-white">Earn DCoins</h2>
                    </div>
                </div>

                <!-- Rewards Balance -->
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-gray-400">Your Balance</p>
                        <p class="text-sm font-semibold text-white">{{ number_format($totalRewards, 2) }} Dcoins</p>
                    </div>

                    <div class="flex flex-col items-center justify-center text-sm font-semibold text-white sm:hidden">
                        <span class="font-semibold text-white">{{ number_format($totalRewards, 0) }}</span>
                        <span class="text-xs text-gray-400">DCoins</span>
                    </div>

                    <a href="{{ route('rewards.redeem') }}"
                       class="bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500 hover:to-yellow-400 text-black font-bold px-4 py-2 rounded-xl transition-all duration-200 shadow-lg hover:shadow-yellow-500/25">
                        <span class="sm:inline">Redeem</span>
                    </a>
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

            <!-- Redemption Success Message -->
            @if(session('redemption_success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-green-300 font-semibold">{{ session('redemption_success.message') }}</p>
                            <div class="mt-2 space-y-1 text-sm text-green-400">
                                <p>Reference: <span
                                        class="font-mono">{{ session('redemption_success.reference') }}</span></p>
                                <p>Amount: {{ number_format((float)session('redemption_success.amount'), 2) }} DCoins →
                                    {{ session('redemption_success.currency') }} {{ number_format((float)session('redemption_success.equivalent_amount'), 2) }}
                                </p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-green-400 hover:text-green-300">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            @endif

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
                                <p class="text-2xl font-bold text-white">{{ $totalRewards }}</p>
                                <p class="text-xs text-gray-400">Balance</p>
                            </div>
                            <div class="w-px h-12 bg-gray-700"></div>

                            <div class="text-center">
                                <p class="text-lg font-bold text-white">{{ number_format($totalEarned, 0) }}</p>
                                <p class="text-xs text-gray-400">Earned</p>
                            </div>
                            <div class="w-px h-12 bg-gray-700"></div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-white">{{ $totalRedeemed }}</p>
                                <p class="text-xs text-gray-400">Redeemed</p>
                            </div>

                            {{--                            <div class="w-px h-12 bg-gray-700"></div>--}}
                            {{--                            <div class="text-center">--}}
                            {{--                                <p class="text-2xl font-bold text-white">{{ $referralCount }}</p>--}}
                            {{--                                <p class="text-xs text-gray-400">Referred</p>--}}
                            {{--                            </div>--}}
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

                    {{--                    <!-- Referral Link -->--}}
                    {{--                    <div class="group">--}}
                    {{--                        <label class="text-xs text-gray-400 mb-1 block">Referral Link</label>--}}
                    {{--                        <div--}}
                    {{--                            class="flex items-center bg-gray-800/60 border border-gray-700 rounded-lg p-3 group-hover:border-yellow-500/50 transition-colors">--}}
                    {{--                            <div class="flex-1 min-w-0 overflow-hidden">--}}
                    {{--                                <p class="font-mono text-white text-xs truncate">{{ $referralLink }}</p>--}}
                    {{--                            </div>--}}
                    {{--                            <button @click="copyToClipboard('{{ $referralLink }}', 'link')"--}}
                    {{--                                    class="ml-2 p-1.5 bg-yellow-500/20 hover:bg-yellow-500/30 rounded-md transition-colors flex-shrink-0">--}}
                    {{--                                <i x-show="!copiedLink" data-lucide="copy" class="w-4 h-4 text-yellow-400"></i>--}}
                    {{--                                <i x-show="copiedLink" data-lucide="check" class="w-4 h-4 text-green-400"></i>--}}
                    {{--                            </button>--}}
                    {{--                        </div>--}}
                    {{--                        <p x-show="copiedLink" x-transition class="text-xs text-green-400 mt-1">Link copied!</p>--}}
                    {{--                    </div>--}}
                </div>

                <div
                    class=" flex flex-wrap gap-2">


                    <a href="https://t.me/share/url?url={{ urlencode($referralLink ?? '') }}&text={{ urlencode('Join me and earn DCoins!') }}"
                       target="_blank"
                       rel="noopener"
                       class="group flex flex-col items-center gap-2 p-4 bg-gray-800/40 hover:bg-blue-500/20 rounded-xl transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-blue-500/20 group-hover:bg-blue-500/30 rounded-xl flex items-center justify-center transition-colors">
                            <i data-lucide="send" class="w-6 h-6 text-blue-400"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-blue-400">Telegram</span>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($referralLink ?? '') }}"
                       target="_blank"
                       rel="noopener"
                       class="group flex flex-col items-center gap-2 p-4 bg-gray-800/40 hover:bg-green-500/20 rounded-xl transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-green-500/20 group-hover:bg-green-500/30 rounded-xl flex items-center justify-center transition-colors">
                            <i data-lucide="message-circle" class="w-6 h-6 text-green-400"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-green-400">WhatsApp</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($referralLink ?? '') }}&text={{ urlencode('Join me and earn DCoins!') }}"
                       target="_blank"
                       rel="noopener"
                       class="group flex flex-col items-center gap-2 p-4 bg-gray-800/40 hover:bg-gray-400/20 rounded-xl transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-gray-500/20 group-hover:bg-gray-500/30 rounded-xl flex items-center justify-center transition-colors">
                            <i data-lucide="twitter" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-gray-300">X</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink ?? '') }}"
                       target="_blank"
                       rel="noopener"
                       class="group flex flex-col items-center gap-2 p-4 bg-gray-800/40 hover:bg-blue-600/20 rounded-xl transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-blue-600/20 group-hover:bg-blue-600/30 rounded-xl flex items-center justify-center transition-colors">
                            <i data-lucide="facebook" class="w-6 h-6 text-blue-500"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-blue-500">Facebook</span>
                    </a>
                </div>
            </div>

            <!-- how it works -->
            <div class="backdrop-blur-sm rounded-2xl p-1 sm:p">
                <div class="text-center mb-6 sm:mb-10">
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">How It Works</h3>
                    <p class="text-xs sm:text-sm text-gray-400">Start earning in just 3 simple steps</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
                    <!-- Step 1 -->
                    <div
                        class="relative group bg-gradient-to-br from-yellow-600/20 via-gray-900 to-gray-900 rounded-3xl  p-3 sm:p-6 flex flex-col items-center text-center">
                        <div
                            class="absolute -top-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-yellow-500 text-black rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                            1
                        </div>
                        <div
                            class="w-11 h-11 sm:w-16 sm:h-16 bg-yellow-500/20 rounded-lg flex items-center justify-center mb-3 sm:mb-4">
                            <i data-lucide="share-2" class="w-5 h-5 sm:w-8 sm:h-8 text-yellow-400"></i>
                        </div>
                        <h4 class="text-sm sm:text-lg font-semibold text-white mb-1">Share Your Code</h4>
                        <p class="text-xs sm:text-sm text-gray-400">Send your referral code to friends</p>
                    </div>

                    <!-- Step 2 -->
                    <div
                        class="relative group bg-gradient-to-br from-yellow-600/20 via-gray-900 to-gray-900 rounded-3xl  p-3 sm:p-6 flex flex-col items-center text-center">
                        <div
                            class="absolute -top-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                            2
                        </div>
                        <div
                            class="w-11 h-11 sm:w-16 sm:h-16 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3 sm:mb-4">
                            <i data-lucide="user-plus" class="w-5 h-5 sm:w-8 sm:h-8 text-blue-400"></i>
                        </div>
                        <h4 class="text-sm sm:text-lg font-semibold text-white mb-1">Friend Joins</h4>
                        <p class="text-xs sm:text-sm text-gray-400">They sign up with your code</p>
                    </div>

                    <!-- Step 3 -->
                    <div
                        class="relative group bg-gradient-to-br from-yellow-600/20 via-gray-900 to-gray-900 rounded-3xl  p-3 sm:p-6 flex flex-col items-center text-center">
                        <div
                            class="absolute -top-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                            3
                        </div>
                        <div
                            class="w-11 h-11 sm:w-16 sm:h-16 bg-green-500/20 rounded-lg flex items-center justify-center mb-3 sm:mb-4">
                            <i data-lucide="coins" class="w-5 h-5 sm:w-8 sm:h-8 text-green-400"></i>
                        </div>
                        <h4 class="text-sm sm:text-lg font-semibold text-white mb-1">Earn DCoins</h4>
                        <p class="text-xs sm:text-sm text-gray-400">Get DCoins after their first trade</p>
                    </div>
                </div>
            </div>


            {{-- Blade: responsive Bonuses section (replace existing Bonuses block) --}}
            <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl border border-gray-800/50 overflow-hidden mb-6">
                <div class="p-4 sm:p-6 border-b border-gray-800/50">
                    <h3 class="text-lg sm:text-xl font-bold text-white mb-1 flex items-center gap-3">
                        <i data-lucide="award" class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400"></i>
                        Bonuses
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400">All bonuses awarded to your account</p>
                </div>

                @if($bonuses && $bonuses->isNotEmpty())
                    <div class="p-3 sm:p-4 grid gap-3 sm:gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($bonuses as $bonus)
                            <article
                                class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-800/40 rounded-xl border border-gray-800/40 hover:shadow-lg transition-shadow"
                                role="article" aria-labelledby="bonus-title-{{ $bonus->id }}">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500/20 to-yellow-600/10 rounded-full flex items-center justify-center">
                                        <i data-lucide="gift" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400"></i>
                                    </div>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h4 id="bonus-title-{{ $bonus->id }}"
                                        class="font-semibold text-white text-sm sm:text-base truncate">
                                        {{ ucfirst($bonus->type ?? 'Bonus') }}
                                        @if($bonus->is_referral_bonus)
                                            <span class="text-xs text-gray-400 ml-1">(Referral)</span>
                                        @endif
                                    </h4>

                                    <p class="text-xs sm:text-sm text-gray-400 mt-1 truncate"
                                       title="{{ $bonus->notes ?? '' }}">
                                        {{ $bonus->notes ?? '—' }}
                                    </p>

                                    <div
                                        class="mt-2 flex items-center justify-between text-xs sm:text-sm text-gray-300">
                                        <time datetime="{{ $bonus->created_at->toIso8601String() }}">
                                            {{ $bonus->created_at->format('M d, Y') }}
                                        </time>

                                        <div class="font-bold text-yellow-400">
                                            {{ number_format($bonus->bonus_amount, 0) }} DCoins
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-gray-400">
                        <div class="mb-3">
                            <i data-lucide="gift" class="w-8 h-8 text-yellow-400 inline-block"></i>
                        </div>
                        <p class="font-semibold">No bonuses yet</p>
                        <p class="text-sm text-gray-500 mt-1">Earn DCoins by referring friends or completing eligible
                            actions.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl border border-gray-800/50 overflow-hidden">
                <div class="p-6 border-b border-gray-800/50">
                    <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-3">
                        <i data-lucide="activity" class="w-6 h-6 text-yellow-400"></i>
                        Your Referrals
                    </h3>
                    <p class="text-gray-400">People who joined using your referral code</p>
                </div>

                @if($referrals->isNotEmpty())
                    <!-- Mobile Referral List -->
                    <div class="lg:hidden divide-y divide-gray-800/50">
                        @foreach($referrals as $referral)
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-full flex items-center justify-center">
                                            <span class="text-yellow-400 font-bold text-sm">
                                                {{ strtoupper(substr($referral->fname ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white text-sm">
                                                {{ $referral->fname }} {{ $referral->lname }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $referral->username }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if(\App\Models\ReferralBonus::where('referred_user_id', $referral->id)->exists())
                                            <p class="font-bold text-green-700 text-xs">
                                                +100 DCoins
                                            </p>
                                        @else
                                            <p class="font-bold text-yellow-400 text-xs">
                                                Pending
                                            </p>
                                        @endif

                                        <p class="text-xs text-gray-500">
                                            {{ $referral->created_at->format('M d') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800/30">
                            <tr class="text-xs text-gray-400 uppercase tracking-wider">
                                <th class="py-4 px-6 text-left font-semibold">User</th>
                                <th class="py-4 px-6 text-center font-semibold">Join Date</th>
                                <th class="py-4 px-6 text-center font-semibold">Status</th>
                                <th class="py-4 px-6 text-right font-semibold">Reward Earned</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/30">
                            @foreach($referrals as $referral)
                                <tr class="hover:bg-gray-800/20 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-full flex items-center justify-center">
                                                    <span class="text-yellow-400 font-bold text-sm">
                                                        {{ strtoupper(substr($referral->fname ?? 'U', 0, 1)) }}
                                                    </span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white">
                                                    {{ $referral->fname }} {{ $referral->lname }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $referral->username }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <p class="text-gray-300">{{ $referral->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $referral->created_at->format('h:i A') }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                                Active
                                            </span>
                                    </td>

                                    @if(\App\Models\ReferralBonus::where('referred_user_id', $referral->id)->exists())
                                        <td class="py-4 px-6 text-right">
                                            <p class="font-bold text-green-700 text-xs">
                                                +100 DCoins
                                            </p>
                                        </td>
                                    @else
                                        <td class="py-4 px-6 text-right">
                                            <p class="font-bold text-yellow-400 text-xs">
                                                Pending
                                            </p>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div
                            class="w-16 h-16 bg-gray-800/60 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="users" class="w-8 h-8 text-gray-500"></i>
                        </div>
                        <p class="text-gray-400 text-lg mb-2">No referrals yet</p>
                        <p class="text-gray-500 text-sm">Start sharing your code to see your earnings here</p>
                    </div>
                @endif
            </div>

            <!-- Redemption History -->
            <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl border border-gray-800/50 overflow-hidden">
                <div class="p-6 border-b border-gray-800/50">
                    <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-3">
                        <i data-lucide="history" class="w-6 h-6 text-blue-400"></i>
                        Redemption History
                    </h3>
                    <p class="text-gray-400">Your recent DCoins redemption requests</p>
                </div>

                @if($redemptionHistory->isNotEmpty())
                    <!-- Mobile Redemption List -->
                    <div class="lg:hidden divide-y divide-gray-800/50">
                        @foreach($redemptionHistory as $redemption)
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500/30 to-blue-600/30 rounded-full flex items-center justify-center">
                                            @if($redemption->currency === 'USDT')
                                                <i data-lucide="wallet" class="w-5 h-5 text-blue-400"></i>
                                            @else
                                                <i data-lucide="building-2" class="w-5 h-5 text-blue-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white text-sm">
                                                {{ number_format($redemption->amount, 0) }} DCoins
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                → {{ $redemption->currency }} {{ number_format($redemption->equivalent_amount, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($redemption->status === 'completed') bg-green-500/20 text-green-400 border border-green-500/30
                                            @elseif($redemption->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                            @elseif($redemption->status === 'processing') bg-blue-500/20 text-blue-400 border border-blue-500/30
                                            @else bg-red-500/20 text-red-400 border border-red-500/30 @endif">
                                            {{ ucfirst($redemption->status) }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $redemption->created_at->format('M d') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>Ref: {{ $redemption->reference }}</span>
                                    <span>Rate: {{ number_format($redemption->exchange_rate, 6) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800/30">
                            <tr class="text-xs text-gray-400 uppercase tracking-wider">
                                <th class="py-4 px-6 text-left font-semibold">Reference</th>
                                <th class="py-4 px-6 text-center font-semibold">Amount</th>
                                <th class="py-4 px-6 text-center font-semibold">Currency</th>
                                <th class="py-4 px-6 text-center font-semibold">Exchange Rate</th>
                                <th class="py-4 px-6 text-center font-semibold">Date</th>
                                <th class="py-4 px-6 text-center font-semibold">Status</th>
                                <th class="py-4 px-6 text-right font-semibold">Details</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/30">
                            @foreach($redemptionHistory as $redemption)
                                <tr class="hover:bg-gray-800/20 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-blue-500/30 to-blue-600/30 rounded-full flex items-center justify-center">
                                                @if($redemption->currency === 'USDT')
                                                    <i data-lucide="wallet" class="w-5 h-5 text-blue-400"></i>
                                                @else
                                                    <i data-lucide="building-2" class="w-5 h-5 text-blue-400"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white font-mono text-sm">
                                                    {{ $redemption->reference }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $redemption->created_at->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <p class="font-bold text-white text-lg">{{ number_format($redemption->amount, 0) }}</p>
                                        <p class="text-xs text-gray-400">DCoins</p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="font-semibold text-white">{{ $redemption->currency }}</span>
                                            <span
                                                class="text-xs text-yellow-400">{{ number_format($redemption->equivalent_amount, 2) }}</span>
                                            @if($redemption->currency === 'USDT' && $redemption->wallet_details)
                                                <span
                                                    class="text-xs text-gray-400">{{ $redemption->wallet_details['network'] ?? 'N/A' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <p class="text-gray-300 font-mono text-sm">{{ number_format($redemption->exchange_rate, 6) }}</p>
                                        <p class="text-xs text-gray-400">1 DCoin
                                            = {{ number_format($redemption->exchange_rate, 6) }} {{ $redemption->currency }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <p class="text-gray-300">{{ $redemption->created_at->format('M d, Y') }}</p>
                                        @if($redemption->processed_at)
                                            <p class="text-xs text-gray-500">
                                                Processed: {{ $redemption->processed_at->format('M d') }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($redemption->status === 'completed') bg-green-500/20 text-green-400 border border-green-500/30
                                            @elseif($redemption->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                            @elseif($redemption->status === 'processing') bg-blue-500/20 text-blue-400 border border-blue-500/30
                                            @else bg-red-500/20 text-red-400 border border-red-500/30 @endif">
                                            {{ ucfirst($redemption->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        @if($redemption->currency === 'USDT' && $redemption->wallet_details)
                                            <p class="text-xs text-gray-400 font-mono">
                                                {{ substr($redemption->wallet_details['address'] ?? 'N/A', 0, 8) }}...
                                            </p>
                                        @elseif($redemption->bank_details)
                                            <p class="text-xs text-gray-400">
                                                {{ $redemption->bank_details['bank_name'] ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                ****{{ substr($redemption->bank_details['account_number'] ?? '', -4) }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div
                            class="w-16 h-16 bg-gray-800/60 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="history" class="w-8 h-8 text-gray-500"></i>
                        </div>
                        <p class="text-gray-400 text-lg mb-2">No redemption history</p>
                        <p class="text-gray-500 text-sm">Your redemption requests will appear here</p>
                        <a href="{{ route('rewards.redeem') }}"
                           class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition-colors">
                            <i data-lucide="coins" class="w-4 h-4"></i>
                            Redeem DCoins
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
