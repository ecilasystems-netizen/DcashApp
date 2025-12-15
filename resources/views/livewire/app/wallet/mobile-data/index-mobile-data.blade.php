<div>
    <x-slot name="header">
        <!-- Header -->
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex items-center gap-4">
                <a href="{{route('dashboard')}}" class="p-2 rounded-full hover:bg-gray-800">
                    <i data-lucide="arrow-left"></i>
                </a>
                <div>
                    <p class="text-xs text-gray-400">Buy</p>
                    <h2 class="font-bold text-xl text-white">Mobile Data</h2>
                </div>
            </div>
        </header>
    </x-slot>

    <div>
        <!-- Data Purchase Form -->
        <div class="p-1 lg:p-0 lg:py-8 space-y-6">
            <!-- Network Selection -->
            <div>
                <h3 class="font-semibold text-white mb-4">1. Select Network</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div wire:click="selectNetwork('BIL108')"
                         class="cursor-pointer bg-gray-800 p-3 rounded-lg border-2 border-gray-700 flex justify-center items-center transition-all duration-200 {{ $selectedNetwork === 'BIL108' ? 'network-selected' : '' }}">
                        <img
                            src="{{asset('storage/mobile_networks/mtn.png')}}"
                            alt="MTN" class="h-10 object-contain rounded">
                    </div>
                    <div wire:click="selectNetwork('BIL109')"
                         class="cursor-pointer bg-gray-800 p-3 rounded-lg border-2 border-gray-700 flex justify-center items-center transition-all duration-200 {{ $selectedNetwork === 'BIL109' ? 'network-selected' : '' }}">
                        <img src="{{asset('storage/mobile_networks/glo.png')}}" alt="Glo"
                             class="h-10 object-contain">
                    </div>
                    <div wire:click="selectNetwork('BIL110')"
                         class="cursor-pointer bg-gray-800 p-3 rounded-lg border-2 border-gray-700 flex justify-center items-center transition-all duration-200 {{ $selectedNetwork === 'BIL110' ? 'network-selected' : '' }}">
                        <img
                            src="{{asset('storage/mobile_networks/airtel.png')}}"
                            alt="Airtel" class="h-8 object-contain">
                    </div>
                    <div wire:click="selectNetwork('BIL111')"
                         class="cursor-pointer bg-gray-800 p-3 rounded-lg border-2 border-gray-700 flex justify-center items-center transition-all duration-200 {{ $selectedNetwork === 'BIL111' ? 'network-selected' : '' }}">
                        <img
                            src="{{asset('storage/mobile_networks/9mobile.png')}}"
                            alt="9mobile" class="h-8 object-contain">
                    </div>
                </div>
            </div>

            <!-- Phone Number -->
            <div>
                <label for="mobileNumber" class="font-semibold text-white mb-2 block">2. Enter Phone Number</label>
                <input type="tel" wire:model.live="mobileNumber" id="mobileNumber" placeholder="Enter phone number"
                       class="w-full bg-gray-800 border-2 border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#E1B362] transition-colors">
                @error('mobileNumber') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Data Plans -->
            @if($selectedNetwork && !$dataPlans->isEmpty())
                <div>
                    <h3 class="font-semibold text-white mb-4">3. Select Data Plan</h3>
                    <!-- Tabs -->
                    <div class="flex bg-gray-800 rounded-lg p-1 mb-4">
                        <button wire:click="selectTab('daily')"
                                class="flex-1 p-2 rounded-md font-semibold transition-colors duration-200 {{ $currentTab === 'daily' ? 'tab-active' : '' }}">
                            Daily
                        </button>
                        <button wire:click="selectTab('weekly')"
                                class="flex-1 p-2 rounded-md font-semibold transition-colors duration-200 {{ $currentTab === 'weekly' ? 'tab-active' : '' }}">
                            Weekly
                        </button>
                        <button wire:click="selectTab('monthly')"
                                class="flex-1 p-2 rounded-md font-semibold transition-colors duration-200 {{ $currentTab === 'monthly' ? 'tab-active' : '' }}">
                            Monthly
                        </button>
                    </div>

                    <!-- Plan Cards Container -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if(isset($dataPlans[$currentTab]))
                            @foreach($dataPlans[$currentTab] as $plan)
                                <div wire:click="selectPlan('{{ $plan['item_code'] }}')"
                                     class="cursor-pointer bg-gray-800 p-4 rounded-lg border-2 {{ $selectedPlan === $plan['item_code'] ? 'plan-selected' : 'border-gray-700' }} text-center transition-all">
                                    <p class="font-bold text-sm text-white">{{ $plan['biller_name'] }}</p>
                                    <p class="font-semibold text-[#E1B362]">₦{{ number_format($plan['amount']) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $plan['validity_period'] }} day(s)</p>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center py-6 text-gray-400">
                                No {{ $currentTab }} plans available for this network
                            </div>
                        @endif
                    </div>
                </div>
            @endif


            <!-- Proceed Button -->
            <button wire:click="openConfirmationModal"
                    class="brand-gradient w-full text-white py-4 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all mt-5 disabled:opacity-50 disabled:cursor-not-allowed"
                {{ !$selectedNetwork || !$selectedPlan || !$mobileNumber ? 'disabled' : '' }}>
                <span wire:loading.remove wire:target="openConfirmationModal">Proceed</span>
                <span wire:loading wire:target="openConfirmationModal">Processing...</span>
            </button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop {{ $showConfirmModal ? '' : 'hidden' }}"
    >
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-6 border border-gray-700 shadow-xl">
            <h2 class="text-2xl font-bold text-center text-white mb-2">Confirm Purchase</h2>
            <div class="space-y-3 text-sm my-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Network:</span>
                    <span class="font-bold text-white flex items-center gap-2">
                        @if($selectedNetwork)
                            <img
                                src="{{asset('storage/mobile_networks/' . strtolower($networkNames[$selectedNetwork]) . '.png')}}"
                                class="h-5 w-5 object-contain rounded-full"
                                alt="{{ $networkNames[$selectedNetwork] }}">
                            {{ $networkNames[$selectedNetwork] }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Phone:</span>
                    <span class="font-bold text-white">{{ $mobileNumber }}</span>
                </div>
                <!-- Plan details -->
                @if($selectedPlan)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Plan:</span>
                        <span class="font-bold text-white">{{ $this->getPlanDetails()['biller_name'] ?? '' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Amount:</span>
                        <span
                            class="font-bold text-red-400">₦{{ number_format($this->getPlanDetails()['amount'] ?? 0) }}</span>
                    </div>
                    <hr class="border-gray-700 !my-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Service Fee:</span>
                        <span class="font-bold text-gray-400 line-through"
                              style="text-decoration: line-through !important;">₦0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Current Balance:</span>
                        <span class="font-bold text-white">₦{{ number_format($userBalance) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Balance After:</span>
                        <span
                            class="font-bold {{ ($userBalance - ($this->getPlanDetails()['amount'] ?? 0)) >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            ₦{{ number_format($userBalance - ($this->getPlanDetails()['amount'] ?? 0)) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="mb-6">
                <label for="pin" class="font-semibold text-white mb-2 block text-center">Enter your 4-digit PIN</label>
                <input type="password" wire:model.live="pin" id="pin" maxlength="4"
                       class="w-full bg-gray-900 border-2 border-gray-700 rounded-lg px-4 py-3 text-white text-center text-2xl tracking-[1em] focus:outline-none focus:border-[#E1B362]">

                @error('pin') <span class="text-red-500 text-sm mt-1 block text-center">{{ $message }}</span> @enderror
                @if(session()->has('error'))
                    <p class="text-red-500 text-sm mt-1 block text-center">
                        {{ session('error') }}
                    </p>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button wire:click="cancelPurchase"
                        @click="intentionallyCancelling = true"
                        class="bg-gray-700 text-white py-3 rounded-lg font-semibold hover:bg-gray-600">
                    Cancel
                </button>
                <button wire:click="confirmPurchase" wire:loading.attr="disabled"
                        class="brand-gradient text-white py-3 rounded-lg font-semibold hover:opacity-90">
                    <span wire:loading.remove wire:target="confirmPurchase">Confirm</span>
                    <span wire:loading wire:target="confirmPurchase">Processing...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop {{ $showSuccessModal ? '' : 'hidden' }}">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-8 text-center border border-gray-700 shadow-xl">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check" class="text-green-400 w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Success!</h2>
            <p class="text-gray-400 mb-6">Your data purchase was successful.</p>
            <button wire:click="closeSuccessModal"
                    class="brand-gradient w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all">
                Done
            </button>
        </div>
    </div>

    <!-- error Modal -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop {{ $showErrorModal ? '' : 'hidden' }}">
        <div class="bg-gray-800 rounded-2xl w-full max-w-sm p-8 text-center border border-gray-700 shadow-xl">
            <div class="w-20 h-20 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="x-circle" class="text-red-600 w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Error Occured!</h2>

            @if(session()->has('error'))
                <p class="text-gray-400 mb-6">
                    {{ session('error') }}
                </p>
            @else
                <p class="text-gray-400 mb-6">Your data purchase was not successful, please try again later.</p>

            @endif
            <button wire:click="closeErrorModal"
                    class="brand-gradient w-full text-white py-3 px-6 rounded-xl font-semibold text-lg hover:opacity-90 transition-all">
                Okay
            </button>
        </div>
    </div>


    @push('styles')
        <style>
            .network-selected {
                transform: scale(1.05);
                border-color: #e1b362;
                box-shadow: 0 0 15px rgba(225, 179, 98, 0.3);
            }

            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }

            .tab-active {
                background-color: #e1b362;
                color: #1f2937;
            }

            .plan-selected {
                border-color: #e1b362;
                background-color: rgba(225, 179, 98, 0.1);
            }
        </style>
    @endpush
</div>
