<div>
    <!-- Header -->
    <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
        <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
            <a href="#" class="p-2 rounded-full hover:bg-gray-800">
                <i data-lucide="arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-white">Pay Cable TV Bills</h1>
                <p class="text-gray-400 text-sm">Renew your subscription instantly.</p>
            </div>
        </div>
    </header>

    <!-- Pay TV Form -->
    <div class="p-1 lg:p-0 lg:py-8 space-y-6">
        <!-- Provider Selection Dropdown -->
        <div>
            <h3 class="font-semibold text-white mb-4">1. Select Provider</h3>
            <div class="relative" id="provider-dropdown">
                <button type="button" id="provider-dropdown-button"
                        class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white flex items-center justify-between focus:outline-none focus:border-[#E1B362]">
                                <span id="selected-provider-content" class="flex items-center gap-3">
                                     <i data-lucide="tv" class="text-gray-400"></i>
                                     <span>Select Provider</span>
                                </span>
                    <i data-lucide="chevron-down" class="transition-transform"></i>
                </button>
                <div id="provider-dropdown-list"
                     class="absolute z-20 w-full mt-2 bg-gray-800 border border-gray-700 rounded-lg shadow-lg hidden overflow-hidden">
                    <div data-provider="DStv" data-logo="https://i.imgur.com/7sZ5vce.png"
                         class="provider-item cursor-pointer hover:bg-gray-700 p-3 flex items-center gap-3">
                        <img src="https://i.imgur.com/7sZ5vce.png"
                             class="h-8 w-8 object-contain rounded-full bg-white p-1">
                        <span>DStv</span>
                    </div>
                    <div data-provider="GOtv" data-logo="https://i.imgur.com/Jq8i26B.png"
                         class="provider-item cursor-pointer hover:bg-gray-700 p-3 flex items-center gap-3">
                        <img src="https://i.imgur.com/Jq8i26B.png"
                             class="h-8 w-8 object-contain rounded-full bg-white p-1">
                        <span>GOtv</span>
                    </div>
                    <div data-provider="Startimes" data-logo="https://i.imgur.com/jVbYm2I.png"
                         class="provider-item cursor-pointer hover:bg-gray-700 p-3 flex items-center gap-3">
                        <img src="https://i.imgur.com/jVbYm2I.png" class="h-8 w-8 object-contain rounded-full">
                        <span>Startimes</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Selection Dropdown -->
        <div id="plan-section" class="hidden">
            <h3 class="font-semibold text-white mb-4">2. Select Plan</h3>
            <select id="plan-selector"
                    class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362] appearance-none">
                <option>Select a plan</option>
            </select>
        </div>

        <!-- Smart Card Number -->
        <div id="smartcard-section" class="hidden">
            <label for="smartcard-number" class="font-semibold text-white mb-2 block">3. Smart Card / IUC Number</label>
            <input type="number" id="smartcard-number" placeholder="Enter your card number"
                   class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362]">
        </div>

        <!-- Proceed Button -->
        <button id="proceed-btn"
                class="brand-gradient w-full text-white py-4 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all mt-5 disabled:opacity-50"
                disabled>
            Proceed
        </button>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop hidden">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-6 border border-gray-700 shadow-xl">
            <h2 class="text-2xl font-bold text-center text-white mb-2">Confirm Payment</h2>
            <div class="space-y-3 text-sm my-6">
                <div class="flex justify-between"><span class="text-gray-400">Provider:</span><span
                        id="summary-provider" class="font-bold text-white"></span></div>
                <div class="flex justify-between"><span class="text-gray-400">Smart Card:</span><span
                        id="summary-smartcard" class="font-bold text-white"></span></div>
                <div class="flex justify-between"><span class="text-gray-400">Plan:</span><span id="summary-plan"
                                                                                                class="font-bold text-white"></span>
                </div>
                <div class="flex justify-between"><span class="text-gray-400">Amount:</span><span id="summary-amount"
                                                                                                  class="font-bold text-white"></span>
                </div>
            </div>
            <div class="mb-6">
                <label for="pin" class="font-semibold text-white mb-2 block text-center">Enter your 4-digit PIN</label>
                <input type="password" id="pin" maxlength="4"
                       class="w-full bg-gray-900 border-2 border-gray-700 rounded-lg px-4 py-3 text-white text-center text-2xl tracking-[1em] focus:outline-none focus:border-[#E1B362]">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button id="cancel-btn" class="bg-gray-700 text-white py-3 rounded-lg font-semibold hover:bg-gray-600">
                    Cancel
                </button>
                <button id="confirm-btn"
                        class="brand-gradient text-white py-3 rounded-lg font-semibold hover:opacity-90">Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop hidden">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-8 text-center border border-gray-700 shadow-xl">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check" class="text-green-400 w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Payment Successful!</h2>
            <p class="text-gray-400 mb-6">Your subscription has been renewed.</p>
            <button id="done-btn"
                    class="brand-gradient w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:opacity-90">
                Done
            </button>
        </div>
    </div>

    @push('styles')
        <style>
            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }
        </style>
    @endpush

    @push('scripts')
        <script>

            document.addEventListener('DOMContentLoaded', () => {
                const TV_PLANS = {
                    'DStv': [
                        {name: 'Padi', price: 2150},
                        {name: 'Yanga', price: 3500},
                        {name: 'Confam', price: 5300},
                        {name: 'Compact', price: 9000},
                        {name: 'Premium', price: 21000},
                    ],
                    'GOtv': [
                        {name: 'Smallie', price: 900},
                        {name: 'Jinja', price: 1900},
                        {name: 'Jolli', price: 2800},
                        {name: 'Max', price: 4150},
                    ],
                    'Startimes': [
                        {name: 'Nova', price: 900},
                        {name: 'Basic', price: 1700},
                        {name: 'Smart', price: 2200},
                        {name: 'Classic', price: 2500},
                    ]
                };

                const providerDropdown = document.getElementById('provider-dropdown');
                const providerDropdownButton = document.getElementById('provider-dropdown-button');
                const providerDropdownList = document.getElementById('provider-dropdown-list');
                const selectedProviderContent = document.getElementById('selected-provider-content');

                const planSection = document.getElementById('plan-section');
                const planSelector = document.getElementById('plan-selector');
                const smartcardSection = document.getElementById('smartcard-section');
                const smartcardNumberInput = document.getElementById('smartcard-number');
                const proceedBtn = document.getElementById('proceed-btn');

                const confirmationModal = document.getElementById('confirmation-modal');
                const successModal = document.getElementById('success-modal');
                const cancelBtn = document.getElementById('cancel-btn');
                const confirmBtn = document.getElementById('confirm-btn');
                const doneBtn = document.getElementById('done-btn');

                let selectedProvider = null;
                let selectedPlan = null;

                function validateForm() {
                    const isCardValid = smartcardNumberInput.value.length >= 10;
                    const isPlanSelected = selectedPlan && selectedPlan.price > 0;
                    proceedBtn.disabled = !(selectedProvider && isPlanSelected && isCardValid);
                }

                function populatePlans() {
                    planSelector.innerHTML = '<option value="">Select a plan</option>';
                    if (!selectedProvider) return;

                    const plans = TV_PLANS[selectedProvider];
                    plans.forEach(plan => {
                        const option = document.createElement('option');
                        option.value = JSON.stringify(plan);
                        option.textContent = `${plan.name} - ₦${plan.price.toLocaleString()}`;
                        planSelector.appendChild(option);
                    });
                    planSection.classList.remove('hidden');
                    smartcardSection.classList.remove('hidden');
                }

                providerDropdownButton.addEventListener('click', () => {
                    providerDropdownList.classList.toggle('hidden');
                    providerDropdownButton.querySelector('[data-lucide="chevron-down"]').classList.toggle('rotate-180');
                });

                providerDropdownList.addEventListener('click', (e) => {
                    const providerItem = e.target.closest('.provider-item');
                    if (!providerItem) return;

                    selectedProvider = providerItem.dataset.provider;
                    const logoSrc = providerItem.dataset.logo;

                    selectedProviderContent.innerHTML = `
                    <img src="${logoSrc}" class="h-6 w-6 object-contain rounded-full">
                    <span>${selectedProvider}</span>
                `;

                    providerDropdownList.classList.add('hidden');
                    providerDropdownButton.querySelector('[data-lucide="chevron-down"]').classList.remove('rotate-180');

                    populatePlans();
                    selectedPlan = null; // Reset plan selection
                    validateForm();
                });

                planSelector.addEventListener('change', (e) => {
                    if (e.target.value) {
                        selectedPlan = JSON.parse(e.target.value);
                    } else {
                        selectedPlan = null;
                    }
                    validateForm();
                });

                smartcardNumberInput.addEventListener('input', validateForm);

                proceedBtn.addEventListener('click', () => {
                    document.getElementById('summary-provider').textContent = selectedProvider;
                    document.getElementById('summary-smartcard').textContent = smartcardNumberInput.value;
                    document.getElementById('summary-plan').textContent = selectedPlan.name;
                    document.getElementById('summary-amount').textContent = `₦${selectedPlan.price.toLocaleString()}`;
                    confirmationModal.classList.remove('hidden');
                });

                cancelBtn.addEventListener('click', () => confirmationModal.classList.add('hidden'));
                doneBtn.addEventListener('click', () => window.location.reload());

                confirmBtn.addEventListener('click', () => {
                    confirmationModal.classList.add('hidden');
                    successModal.classList.remove('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!providerDropdown.contains(e.target)) {
                        providerDropdownList.classList.add('hidden');
                        providerDropdownButton.querySelector('[data-lucide="chevron-down"]').classList.remove('rotate-180');
                    }
                });
            });
        </script>
    @endpush
</div>
