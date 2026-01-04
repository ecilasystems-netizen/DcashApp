<div>
    @if(session('error'))
        <div
            class="mb-4 flex items-center px-4 py-3 bg-red-600 text-white text-sm rounded-lg border-l-4 border-red-400 shadow font-semibold">
            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-white" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif
</div>
