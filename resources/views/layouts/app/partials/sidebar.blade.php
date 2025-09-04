<aside class="hidden lg:flex flex-col bg-gray-800 border-r border-gray-700 fixed h-full z-20">
    <div class="w-20 h-[100px] flex items-center justify-center flex-shrink-0">
        <img src="/imgs/logo-only.png" height="50px" alt="Logo">
    </div>

    <div class="group flex flex-col justify-between flex-grow w-20 hover:w-56 transition-all duration-300 ease-in-out">
        <nav class="flex flex-col space-y-2 w-full px-4 pt-2">
            <a href="{{ route('dashboard') }}"
               class="flex items-center p-3 rounded-lg transition-colors duration-200 overflow-hidden {{ request()->routeIs('dashboard') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-[#E1B362] hover:bg-gray-700' }}">
                <i data-lucide="home" class="flex-shrink-0"></i>
                <span
                    class="font-semibold opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-200 whitespace-nowrap">Home</span>
            </a>

            <a href="{{ route('exchange.transactions') }}"
               class="flex items-center p-3 rounded-lg transition-colors duration-200 overflow-hidden {{ request()->routeIs('exchange.transactions') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-[#E1B362] hover:bg-gray-700' }}">
                <i data-lucide="arrow-left-right" class="flex-shrink-0"></i>
                <span
                    class="font-semibold opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-200 whitespace-nowrap">Transactions</span>
            </a>

            <a href="{{ route('rewards') }}"
               class="flex items-center p-3 rounded-lg transition-colors duration-200 overflow-hidden {{ request()->routeIs('rewards') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-[#E1B362] hover:bg-gray-700' }}">
                <i data-lucide="gift" class="flex-shrink-0"></i>
                <span
                    class="font-semibold opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-200 whitespace-nowrap">Rewards</span>
            </a>

            <a href="{{ route('profile') }}"
               class="flex items-center p-3 rounded-lg transition-colors duration-200 overflow-hidden {{ request()->routeIs('profile') ? 'text-white bg-[#E1B362]' : 'text-gray-400 hover:text-[#E1B362] hover:bg-gray-700' }}">
                <i data-lucide="user" class="flex-shrink-0"></i>
                <span
                    class="font-semibold opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-200 whitespace-nowrap">Profile</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit"
                        class="flex w-full items-center p-3 rounded-lg text-gray-400 hover:text-[#E1B362] hover:bg-gray-700 transition-colors duration-200 overflow-hidden">
                    <i data-lucide="log-out" class="flex-shrink-0"></i>
                    <span
                        class="font-semibold opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-200 whitespace-nowrap">Logout</span>
                </button>
            </form>
        </nav>
        <div class="w-full p-4">
            <button
                class="flex items-center p-3 w-full rounded-lg text-gray-400 hover:text-[#E1B362] hover:bg-gray-700 transition-colors duration-200 overflow-hidden">
                <i data-lucide="pencil" class="flex-shrink-0"></i>
                <span
                    class="font-semibold opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-200 whitespace-nowrap">Help</span>
            </button>
        </div>
    </div>
</aside>
