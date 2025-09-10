<div>
    <div class="mt-6 bg-yellow-900/20 rounded-lg p-4">
        @if($exchangeData['baseCurrencyCode'] === 'PHP')
            <div class="text-xs">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                    <h3 class="text-sm font-semibold text-yellow-300">Important notice</h3>
                </div>

                <ul class="text-xs space-y-2 text-yellow-100">
                    <li class="flex items-center space-x-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-400"></i>
                        <span>Transfers are non-refundable.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="hash" class="w-4 h-4 text-yellow-400"></i>
                        <span>Please make sure you send the exact amount.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="clock" class="w-4 h-4 text-yellow-400"></i>
                        <span>Check your daily transfer limit before proceeding.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-yellow-400"></i>
                        <span>Review transfer fees in advance.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="refresh-ccw" class="w-4 h-4 text-yellow-400"></i>
                        <span>Know the expected transfer processing time.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-yellow-400"></i>
                        <span>Wait for confirmation before exiting the app.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-yellow-400"></i>
                        <span>Save the order number for future reference.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-yellow-400"></i>
                        <span>Be wary of unexpected money requests.</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="link-2" class="w-4 h-4 text-yellow-400"></i>
                        <span>Avoid phishing scams and suspicious links.</span>
                    </li>
                </ul>

                <div class="mt-3 space-y-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-yellow-100">•</span>
                        <img src="{{ asset('images/instapay.png') }}" alt="Instapay" class="h-5 w-20">
                        <span class="text-yellow-100">transfers only</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-yellow-100">•</span>
                        <img src="{{ asset('images/pesonet.png') }}" alt="PESONet" class="h-5 w-20">
                        <span class="text-yellow-100">transfers only</span>
                    </div>
                </div>
            </div>

        @elseif($exchangeData['baseCurrencyCode'] === 'USDT')
            <div class="flex items-start space-x-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                <div class="text-xs">
                    <h3 class="text-sm font-semibold text-yellow-300 mb-1">Important notice</h3>
                    <p class="text-yellow-100">Please confirm that you are depositing USDT to this address on the TRC20
                        network. Mismatched address information may result in the permanent loss of your assets.</p>
                </div>
            </div>

        @else
            <div>
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                    <h3 class="text-sm font-semibold text-yellow-300">Important notice</h3>
                </div>

                <ul class="text-xs space-y-2 text-yellow-100">
                    <li class="flex items-start space-x-2">
                        <i data-lucide="user-check" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Verify recipient details; transfers are non-refundable.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="clock" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Check your daily transfer limit before proceeding.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Review transfer fees in advance.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="refresh-ccw" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Know the expected transfer processing time.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Wait for confirmation before exiting the app.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Save the order number for future reference.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Be wary of unexpected money requests.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="link-2" class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0"></i>
                        <span>Avoid phishing scams and suspicious links.</span>
                    </li>
                </ul>
            </div>
        @endif
    </div>

</div>
