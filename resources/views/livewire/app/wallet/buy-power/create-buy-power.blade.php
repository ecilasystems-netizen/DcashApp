<div>
    <!-- Header -->
    <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
        <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
            <a href="#" class="p-2 rounded-full hover:bg-gray-800">
                <i data-lucide="arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-white">Buy Power</h1>
                <p class="text-gray-400 text-sm">Pay your electricity bills easily.</p>
            </div>
        </div>
    </header>

    <!-- Buy Power Form -->
    <div class="p-1 lg:p-0 lg:py-8 space-y-6">
        <!-- Disco Selection Dropdown -->
        <div>
            <h3 class="font-semibold text-white mb-4">1. Select Provider</h3>
            <div class="relative" id="disco-dropdown">
                <button type="button" id="disco-dropdown-button"
                        class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white flex items-center justify-between focus:outline-none focus:border-[#E1B362]">
                                <span id="selected-disco-content" class="flex items-center gap-3">
                                     <i data-lucide="zap" class="text-gray-400"></i>
                                     <span>Select Provider</span>
                                </span>
                    <i data-lucide="chevron-down" class="transition-transform"></i>
                </button>
                <div id="disco-dropdown-list"
                     class="absolute z-10 w-full mt-2 bg-gray-800 border border-gray-700 rounded-lg shadow-lg hidden overflow-hidden">
                    <div data-disco="Ikeja Electric" data-logo="https://i.imgur.com/Qk7G2hr.png"
                         class="disco-item cursor-pointer hover:bg-gray-700 p-3 flex items-center gap-3">
                        <img src="https://i.imgur.com/Qk7G2hr.png" class="h-8 w-8 object-contain rounded-full">
                        <span>Ikeja Electric</span>
                    </div>
                    <div data-disco="Eko Electric" data-logo="https://i.imgur.com/p2k3rA2.png"
                         class="disco-item cursor-pointer hover:bg-gray-700 p-3 flex items-center gap-3">
                        <img src="https://i.imgur.com/p2k3rA2.png" class="h-8 w-8 object-contain rounded-full">
                        <span>Eko Electric</span>
                    </div>
                    <div data-disco="AEDC" data-logo="https://i.imgur.com/k2G8Y32.png"
                         class="disco-item cursor-pointer hover:bg-gray-700 p-3 flex items-center gap-3">
                        <img src="https://i.imgur.com/k2G8Y32.png" class="h-8 w-8 object-contain rounded-full">
                        <span>AEDC</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Type -->
        <div>
            <h3 class="font-semibold text-white mb-4">2. Select Meter Type</h3>
            <div id="meter-type-selector" class="grid grid-cols-2 gap-4">
                <button data-type="Prepaid"
                        class="meter-type-btn bg-gray-800 p-4 rounded-lg font-semibold border-2 border-gray-700 transition-all">
                    Prepaid
                </button>
                <button data-type="Postpaid"
                        class="meter-type-btn bg-gray-800 p-4 rounded-lg font-semibold border-2 border-gray-700 transition-all">
                    Postpaid
                </button>
            </div>
        </div>

        <!-- Meter Number -->
        <div>
            <label for="meter-number" class="font-semibold text-white mb-2 block">3. Enter Meter Number</label>
            <input type="number" id="meter-number" placeholder="Enter meter number"
                   class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362]">
        </div>

        <!-- Amount -->
        <div>
            <label for="amount" class="font-semibold text-white mb-2 block">4. Enter Amount</label>
            <input type="number" id="amount" placeholder="Enter amount (₦)"
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
            <h2 class="text-2xl font-bold text-center text-white mb-2">Confirm Purchase</h2>
            <div class="space-y-3 text-sm my-6">
                <div class="flex justify-between"><span class="text-gray-400">Provider:</span><span id="summary-disco"
                                                                                                    class="font-bold text-white"></span>
                </div>
                <div class="flex justify-between"><span class="text-gray-400">Meter No:</span><span
                        id="summary-meter-no" class="font-bold text-white"></span></div>
                <div class="flex justify-between"><span class="text-gray-400">Meter Type:</span><span
                        id="summary-meter-type" class="font-bold text-white"></span></div>
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
            <h2 class="text-2xl font-bold text-white mb-2">Purchase Successful!</h2>
            <p class="text-gray-400 mb-4">Here is your electricity token:</p>
            <div class="bg-gray-900 border-2 border-dashed border-gray-600 rounded-lg p-4 mb-6">
                <p id="token-display" class="text-2xl font-bold text-[#E1B362] tracking-widest">1234-5678-9012-3456</p>
            </div>
            <button id="done-btn"
                    class="brand-gradient w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:opacity-90">
                Done
            </button>
        </div>
    </div>

    @push('scripts')
        <style>

            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }

            .meter-type-selected {
                background-color: #e1b362;
                color: #1f2937;
                border-color: #e1b362;
            }
        </style>
    @endpush

    @push('scripts')
        <script>

            document.addEventListener('DOMContentLoaded', () => {
                const meterTypeSelector = document.getElementById('meter-type-selector');
                const meterNumberInput = document.getElementById('meter-number');
                const amountInput = document.getElementById('amount');
                const proceedBtn = document.getElementById('proceed-btn');

                const confirmationModal = document.getElementById('confirmation-modal');
                const successModal = document.getElementById('success-modal');
                const cancelBtn = document.getElementById('cancel-btn');
                const confirmBtn = document.getElementById('confirm-btn');
                const doneBtn = document.getElementById('done-btn');

                // Dropdown elements
                const discoDropdown = document.getElementById('disco-dropdown');
                const discoDropdownButton = document.getElementById('disco-dropdown-button');
                const discoDropdownList = document.getElementById('disco-dropdown-list');
                const selectedDiscoContent = document.getElementById('selected-disco-content');

                let selectedDisco = null;
                let selectedMeterType = null;

                function validateForm() {
                    const isMeterValid = meterNumberInput.value.length >= 10;
                    const isAmountValid = parseInt(amountInput.value) > 0;
                    proceedBtn.disabled = !(selectedDisco && selectedMeterType && isMeterValid && isAmountValid);
                }

                // --- Dropdown Logic ---
                discoDropdownButton.addEventListener('click', () => {
                    discoDropdownList.classList.toggle('hidden');
                    discoDropdownButton.querySelector('[data-lucide="chevron-down"]').classList.toggle('rotate-180');
                });

                discoDropdownList.addEventListener('click', (e) => {
                    const discoItem = e.target.closest('.disco-item');
                    if (!discoItem) return;

                    selectedDisco = discoItem.dataset.disco;
                    const logoSrc = discoItem.dataset.logo;

                    selectedDiscoContent.innerHTML = `
                    <img src="${logoSrc}" class="h-6 w-6 object-contain rounded-full">
                    <span>${selectedDisco}</span>
                `;

                    discoDropdownList.classList.add('hidden');
                    discoDropdownButton.querySelector('[data-lucide="chevron-down"]').classList.remove('rotate-180');
                    validateForm();
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!discoDropdown.contains(e.target)) {
                        discoDropdownList.classList.add('hidden');
                        discoDropdownButton.querySelector('[data-lucide="chevron-down"]').classList.remove('rotate-180');
                    }
                });


                // --- Other Form Logic ---
                meterTypeSelector.addEventListener('click', (e) => {
                    const typeBtn = e.target.closest('.meter-type-btn');
                    if (!typeBtn) return;
                    meterTypeSelector.querySelectorAll('.meter-type-btn').forEach(b => b.classList.remove('meter-type-selected'));
                    typeBtn.classList.add('meter-type-selected');
                    selectedMeterType = typeBtn.dataset.type;
                    validateForm();
                });

                [meterNumberInput, amountInput].forEach(input => {
                    input.addEventListener('input', validateForm);
                });

                proceedBtn.addEventListener('click', () => {
                    document.getElementById('summary-disco').textContent = selectedDisco;
                    document.getElementById('summary-meter-no').textContent = meterNumberInput.value;
                    document.getElementById('summary-meter-type').textContent = selectedMeterType;
                    document.getElementById('summary-amount').textContent = `₦${parseInt(amountInput.value).toLocaleString()}`;
                    confirmationModal.classList.remove('hidden');
                });

                cancelBtn.addEventListener('click', () => confirmationModal.classList.add('hidden'));
                doneBtn.addEventListener('click', () => window.location.reload());

                confirmBtn.addEventListener('click', () => {
                    const token = [0, 0, 0, 0].map(() => Math.floor(1000 + Math.random() * 9000)).join('-');
                    document.getElementById('token-display').textContent = token;

                    confirmationModal.classList.add('hidden');
                    successModal.classList.remove('hidden');
                });
            });
        </script>
    @endpush
</div>
