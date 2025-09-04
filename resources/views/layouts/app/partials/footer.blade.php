<footer
    class="lg:hidden fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 grid grid-cols-5 text-center text-xs text-gray-400 py-2">
    <a href="{{ route('dashboard') }}"
       class="flex flex-col items-center py-2 {{ request()->routeIs('dashboard') ? 'text-[#E1B362]' : 'hover:text-[#E1B362] transition-colors' }}">
        <i data-lucide="home"></i>
        <span class="font-semibold">Home</span>
    </a>
    <a href="{{ route('exchange.transactions') }}"
       class="flex flex-col items-center py-2 {{ request()->routeIs('exchange.transactions') ? 'text-[#E1B362]' : 'hover:text-[#E1B362] transition-colors' }}">
        <i data-lucide="arrow-left-right"></i>
        <span>History</span>
    </a>
    <a href="{{ route('rewards') }}"
       class="flex flex-col items-center py-2 {{ request()->routeIs('rewards') ? 'text-[#E1B362]' : 'hover:text-[#E1B362] transition-colors' }}">
        <i data-lucide="gift"></i>
        <span>Rewards</span>
    </a>
    <a href="{{ route('profile') }}"
       class="flex flex-col items-center py-2 {{ request()->routeIs('profile') ? 'text-[#E1B362]' : 'hover:text-[#E1B362] transition-colors' }}">
        <i data-lucide="user"></i>
        <span>Profile</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" x-data class="contents">
        @csrf
        <button type="submit" class="flex flex-col items-center py-2 hover:text-[#E1B362] transition-colors">
            <i data-lucide="log-out"></i>
            <span>Logout</span>
        </button>
    </form>
</footer>

