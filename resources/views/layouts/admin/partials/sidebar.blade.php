<!-- Sidebar -->
<aside
    class="w-64 bg-gray-800 border-r border-gray-700 flex-shrink-0 hidden lg:flex flex-col">
    <div class="h-20 flex items-center px-6">
        <img src="/imgs/logo-only.png" alt="Logo" class="h-10 w-auto mr-3"/>
        <span class="text-xl font-bold text-white">QX Admin</span>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-2">

        {{--        if admin --}}
        @if(auth()->user() && auth()->user()->is_admin)

            <!-- Business Section -->
            <p class="px-4 pt-2 text-xs font-semibold text-amber-400 uppercase">Business</p>

            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-[#E1B362] hover:bg-gray-700' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                <span>Dashboard</span>
            </a>
            <a
                href="{{ route('admin.transactions') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.transactions') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="arrow-left-right" class="w-5 h-5 mr-3"></i>
                <span>Exchange Trxns</span>
            </a>

            <a
                href="{{ route('admin.wallet-transactions') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.wallet-transactions') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-3"></i>
                <span>Wallet Trxns</span>
            </a>

            <a
                href="{{ route('admin.users') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                <span>Users</span>
            </a>

            <a
                href="{{ route('admin.users.wallet-users') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.wallet-users') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="user-star" class="w-5 h-5 mr-3"></i>
                <span>Wallet Users</span>
            </a>

            <a
                href="{{ route('admin.agents') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.agents') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="shield-user" class="w-5 h-5 mr-3"></i>
                <span>Agents</span>
            </a>
            <a
                href="{{ route('admin.kyc') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.kyc') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="shield-check" class="w-5 h-5 mr-3"></i>
                <span>KYC Verifications</span>
            </a>

            <a
                href="{{ route('admin.redeem-bonus') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.redeem-bonus') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="piggy-bank" class="w-5 h-5 mr-3"></i>
                <span>Bonus Redemption</span>
            </a>

            <a
                href="{{ route('admin.advertisements.list') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.advertisements.list') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="speaker" class="w-5 h-5 mr-3"></i>
                <span>Advertisements</span>
            </a>

            <a
                href="{{ route('admin.dcoin-rates') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dcoin-rates') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="coins" class="w-5 h-5 mr-3"></i>
                <span>DCoin Rates</span>
            </a>

            <a
                href="{{ route('admin.limit-requests') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.limit-requests') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="cog" class="w-5 h-5 mr-3"></i>
                <span>Account Upgrades</span>
            </a>

            <a
                href="{{ route('admin.bonus-management') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.bonus-management*') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="hand-helping" class="w-5 h-5 mr-3"></i>
                <span>Bonus Management</span>
            </a>


            <!-- Settings Section -->
            <p class="px-4 pt-4 mt-2 text-xs font-semibold text-amber-400 uppercase">Settings</p>

            <a
                href="{{ route('admin.bank-accounts') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.bank-accounts') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i>
                <span>Bank Accounts</span>
            </a>

            <a
                href="{{ route('admin.currencies') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.currencies') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="coins" class="w-5 h-5 mr-3"></i>
                <span>Currencies & Rates</span>
            </a>
            <a
                href="{{ route('admin.announcements') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.announcements*') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="megaphone" class="w-5 h-5 mr-3"></i>
                <span>Announcements</span>
            </a>


            <a
                href="#"
                class="flex items-center px-4 py-2 rounded-lg transition-colors text-gray-400 hover:text-white hover:bg-gray-700">
                <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                <span>Settings</span>
            </a>
        @endif

        {{--        if user is agent--}}
        @if(auth()->user() && auth()->user()->is_agent)
            <a
                href="{{ route('agent.dashboard') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('agent.dashboard') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                <span>Dashboard</span>
            </a>
            <a
                href="{{ route('agent.transactions') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('agent.transactions') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="arrow-left-right" class="w-5 h-5 mr-3"></i>
                <span>Transactions</span>
            </a>

            <a
                href="{{ route('agent.currencies') }}"
                class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('agent.currencies') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <i data-lucide="coins" class="w-5 h-5 mr-3"></i>
                <span>Currencies & Rates</span>
            </a>

        @endif


    </nav>
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center">
            <img
                src="https://placehold.co/40x40/374151/E1B362?text=A"
                alt="Admin"
                class="w-10 h-10 rounded-full"/>

            <div class="ml-3">
                <p class="font-semibold text-white">Admin User</p>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit"
                            class="text-xs text-gray-400 hover:text-[#E1B362]">
                        <span
                            class="text-xs text-gray-400 hover:text-[#E1B362]">Logout</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</aside>
