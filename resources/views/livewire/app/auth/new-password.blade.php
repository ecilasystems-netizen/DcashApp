<div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="/imgs/logo-only.png" alt="Dcash Logo" class="w-16 h-16 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white">Create New Password</h1>
                <p class="text-gray-400">Your new password must be different from previous ones.</p>
            </div>

            <!-- New Password Form -->
            <div class="bg-gray-800/2 border border-gray-700 rounded-lg p-8 shadow-lg">
                <form class="space-y-6" method="POST" action="{{ route('reset-password.success') }}">
                    @csrf
                    <div>
                        <label for="new-password" class="block text-sm font-medium text-gray-400 mb-2">New
                            Password</label>
                        <div class="relative">
                            <i data-lucide="lock"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input type="password" id="new-password" placeholder="Enter new password"
                                   class="w-full bg-gray-800/2 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                    </div>
                    <div>
                        <label for="confirm-password" class="block text-sm font-medium text-gray-400 mb-2">Confirm New
                            Password</label>
                        <div class="relative">
                            <i data-lucide="lock"
                               class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                            <input type="password" id="confirm-password" placeholder="Confirm new password"
                                   class="w-full bg-gray-800/2 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#E1B362]">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
