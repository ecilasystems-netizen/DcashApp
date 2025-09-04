<?php

namespace App\Livewire\App\Wallet\Airtime;

use App\Services\FlutterwaveBillsService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class IndexAirtime extends Component
{
    public $selectedNetwork = null;
    public $phoneNumber = '';
    public $selectedAmount = null;
    public $customAmount = '';
    public $predefinedAmounts = [100, 200, 500, 1000, 2000, 5000];
    public $networks = [];
    public $showConfirmModal = false;
    public $pin = '';
    public $showSuccessModal = false;
    public $processing = false;
    public int $userBalance;
    protected $rules = [
        'selectedNetwork' => 'required',
        'phoneNumber' => 'required|min:10|max:11',
        'pin' => 'required|digits:4'
    ];

    public function mount()
    {
        $this->networks = [
            [
                'code' => 'BIL099',
                'name' => 'MTN VTU',
                'short_name' => 'MTN VTU',
                'logo' => asset('storage/mobile_networks/mtn.png')
            ],
            [
                'code' => 'BIL102',
                'name' => 'GLO VTU',
                'short_name' => 'GLO VTU',
                'logo' => asset('storage/mobile_networks/glo.png')
            ],
            [
                'code' => 'BIL100',
                'name' => 'AIRTEL VTU',
                'short_name' => 'AIRTEL VTU',
                'logo' => asset('storage/mobile_networks/airtel.png')
            ],
            [
                'code' => 'BIL103',
                'name' => '9MOBILE VTU',
                'short_name' => '9MOBILE VTU',
                'logo' => asset('storage/mobile_networks/9mobile.png')
            ]
        ];

        $this->userBalance = auth()->user()->wallet->balance ?? 0;
    }

    public function render()
    {
        return view('livewire.app.wallet.airtime.index-airtime')
            ->layout('layouts.app.app', [
                'title' => 'Airtime Purchase',
            ]);
    }

    public function selectNetwork($networkCode)
    {
        $this->selectedNetwork = $networkCode;
    }

    public function selectAmount($amount)
    {
        $this->selectedAmount = $amount;
        $this->customAmount = $amount;
    }

    public function updatedCustomAmount()
    {
        if ($this->customAmount) {
            $this->selectedAmount = (int) $this->customAmount;
        } else {
            $this->selectedAmount = null;
        }
    }

    public function openConfirmationModal()
    {
        if (!$this->canProceed()) {
            return;
        }

        $this->showConfirmModal = true;
    }

    public function canProceed()
    {
        return $this->selectedNetwork &&
            $this->phoneNumber &&
            strlen($this->phoneNumber) >= 10 &&
            $this->getSelectedAmountProperty() > 0;
    }

    public function getSelectedAmountProperty()
    {
        if ($this->customAmount && is_numeric($this->customAmount)) {
            return (int) $this->customAmount;
        }
        return $this->selectedAmount ?? 0;
    }

    public function cancelPurchase()
    {
        $this->showConfirmModal = false;
        $this->pin = '';
    }

    public function confirmPurchase()
    {
        $this->validate();

        $this->processing = true;

        try {
            $flutterwave = new FlutterwaveBillsService();
            $reference = 'airtime_'.time().'_'.auth()->id();

            // verify PIN against user's stored PIN first
            if (!Hash::check($this->pin, auth()->user()->pin)) {
                session()->flash('error', 'Invalid PIN');
                $this->processing = false;
                return;
            }

            $user = auth()->user();

            $user->refresh();
            if ($user->wallet->balance < $this->getSelectedAmountProperty()) {
                $this->showError('Insufficient balance');
                return;
            }


            // Debit wallet
            $user->wallet->balance -= $this->getSelectedAmountProperty();
            $user->wallet->save();

            // Record transaction
            $user->wallet->transactions()->create([
                'reference' => $reference,
                'type' => 'airtime',
                'direction' => 'debit',
                'user_id' => $user->id,
                'amount' => $this->getSelectedAmountProperty(),
                'fee' => 0,
                'description' => 'Airtime to '.$this->phoneNumber,
                'status' => 'pending',
                'balance_before' => $this->userBalance,
                'balance_after' => $user->wallet->balance,
                'metadata' => [
                    'phone_number' => $this->phoneNumber
                ],
            ]);

            //validate customer details
            // Get the item code for the selected network (AT099, etc.)
            $itemCode = $this->getItemCodeForNetwork($this->selectedNetwork);
            $validationResponse = $flutterwave->validateCustomer($itemCode, $this->phoneNumber);
            if (isset($validationResponse['status']) && $validationResponse['status'] !== 'success') {
                session()->flash('error', 'Customer validation failed: '.$validationResponse['message']);
                $this->showConfirmModal = false;
                $this->processing = false;
                return;
            }


            $response = $flutterwave->createBillPayment(
                $this->selectedNetwork, // biller_code
                $itemCode, // item_code
                'NG', // country code
                $this->phoneNumber, // customer phone number
                $this->getSelectedAmountProperty(), // amount
                $reference // unique reference
            );

            if (isset($response['status']) && $response['status'] === 'success') {

                $this->showConfirmModal = false;
                $this->showSuccessModal = true;

            } else {
                session()->flash('error', $response['message'] ?? 'Transaction failed');
                $this->showConfirmModal = false;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Airtime purchase failed: '.$e->getMessage());
        }

        $this->processing = false;
        $this->pin = '';
    }

    private function getItemCodeForNetwork($billerCode)
    {
        $itemCodes = [
            'BIL099' => 'AT099', // MTN
            'BIL102' => 'AT133', // GLO
            'BIL100' => 'AT100', // AIRTEL
            'BIL103' => 'AT134'  // 9MOBILE
        ];

        return $itemCodes[$billerCode] ?? null;
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->reset(['selectedNetwork', 'phoneNumber', 'selectedAmount', 'customAmount']);
        //redirect to dashboard route
        return redirect()->route('dashboard');
    }
}
