<?php

namespace App\Livewire\Rewards;

use App\Mail\AdminNotificationMail;
use App\Models\Bonus;
use App\Models\RedemptionRequest;
use App\Models\SupportedCurrency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class RedeemBonus extends Component
{
    public $totalRewards;
    public $selectedCurrency = '';
    public $redeemAmount = '';
    public $accountName = '';
    public $accountNumber = '';
    public $bankName = '';
    public $walletAddress = '';
    public $selectedNetwork = '';
    public $totalEarned;
    public $totalRedeemed;
    public $isProcessing = false;

    public $supportedCurrencies;
    public $currencyData; // Current selected currency data

    // Add computed property for equivalent amount
    public function getEquivalentAmountProperty()
    {
        if (!$this->redeemAmount || !$this->currencyData) {
            return 0;
        }

        return $this->redeemAmount * $this->currencyData->exchange_rate;
    }

    protected function rules()
    {
        $currency = $this->currencyData;
        $minAmount = $currency ? $currency->min_redemption : 30;

        return [
            'selectedCurrency' => 'required|exists:supported_currencies,code',
            'redeemAmount' => "required|numeric|min:{$minAmount}",
            'accountName' => 'required_if:currencyData.type,fiat|string|max:255',
            'accountNumber' => 'required_if:currencyData.type,fiat|string|max:20',
            'bankName' => 'required_if:currencyData.type,fiat|string',
            'walletAddress' => 'required_if:currencyData.type,crypto|string|max:255',
            'selectedNetwork' => 'required_if:currencyData.type,crypto|string'
        ];
    }

    protected function messages()
    {
        $currency = $this->currencyData;
        $minAmount = $currency ? $currency->min_redemption : 30;

        return [
            'redeemAmount.min' => "Minimum redemption amount is {$minAmount} DCoins",
            'selectedCurrency.exists' => 'Selected currency is not supported',
            'accountName.required_if' => 'Account name is required for bank transfers',
            'accountNumber.required_if' => 'Account number is required for bank transfers',
            'bankName.required_if' => 'Bank name is required for bank transfers',
            'walletAddress.required_if' => 'Wallet address is required for crypto redemption',
            'selectedNetwork.required_if' => 'Network selection is required for crypto redemption'
        ];
    }

    public function mount()
    {
        $this->loadSupportedCurrencies();
        $this->calculateBalance();
    }

    protected function loadSupportedCurrencies()
    {
        $this->supportedCurrencies = SupportedCurrency::active()
            ->ordered()
            ->get()
            ->keyBy('code')
            ->toArray();
    }

    protected function calculateBalance()
    {
        $user = auth()->user();

        // Calculate total earned from referral bonuses
        $totalEarned = Bonus::where('user_id', $user->id)
            ->sum('bonus_amount');

        // Calculate total redeemed amount
        $totalRedeemed = RedemptionRequest::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'processing', 'pending'])
            ->sum('amount');

        // Calculate available balance
        $this->totalRewards = $totalEarned - $totalRedeemed;
    }

    public function updatedSelectedCurrency()
    {
        $this->currencyData = $this->selectedCurrency
            ? SupportedCurrency::where('code', $this->selectedCurrency)->first()
            : null;

        $this->reset(['accountName', 'accountNumber', 'bankName', 'walletAddress', 'selectedNetwork']);
    }

    public function getCanSubmitProperty()
    {
        if (!$this->selectedCurrency || !$this->redeemAmount || $this->isProcessing || !$this->currencyData) {
            return false;
        }

        if ($this->currencyData->isFiat()) {
            return !empty($this->accountName) && !empty($this->accountNumber) && !empty($this->bankName);
        }

        if ($this->currencyData->isCrypto()) {
            return !empty($this->walletAddress) && !empty($this->selectedNetwork);
        }

        return false;
    }

    public function submitRedemption()
    {
        $this->isProcessing = true;

        $this->validate();

        if ($this->redeemAmount > $this->totalRewards) {
            $this->addError('redeemAmount', 'Insufficient DCoins balance');
            $this->isProcessing = false;
            return;
        }

        try {
            $redemptionData = [
                'user_id' => auth()->id(),
                'currency' => $this->selectedCurrency,
                'amount' => $this->redeemAmount,
                'equivalent_amount' => $this->equivalentAmount,
                'exchange_rate' => $this->currencyData->exchange_rate,
                'status' => 'pending',
                'reference' => 'RDM-'.strtoupper(uniqid()),
            ];

            //log the redemption data
            log::info('Redemption Data: ', $redemptionData);

            if ($this->currencyData->isFiat()) {
                $redemptionData['bank_details'] = [
                    'account_name' => $this->accountName,
                    'account_number' => $this->accountNumber,
                    'bank_name' => $this->bankName
                ];
            } else {
                $redemptionData['wallet_details'] = [
                    'address' => $this->walletAddress,
                    'network' => $this->selectedNetwork
                ];
            }

            RedemptionRequest::create($redemptionData);

            // Update available balance
            $this->totalRewards -= $this->redeemAmount;


            // notify admin via email
            Mail::to('rewards@dcashwallet.com')->send(new AdminNotificationMail(
                'bonus_redemption',
                Auth::user()->fname,
                Auth::user()->email,
                [
                    'bonus_amount' => $redemptionData['amount'],
                    'bonus_type' => 'Bonus Redemption',
                    'redemption_date' => now()->format('Y-m-d H:i:s')
                ],
                route('admin.redeem-bonus')
            ));

            // Flash success message to session
            session()->flash('redemption_success', [
                'message' => 'Redemption request submitted successfully! We will process it within 24-48 hours.',
                'reference' => $redemptionData['reference'],
                'amount' => $this->redeemAmount,
                'currency' => $this->selectedCurrency,
                'equivalent_amount' => $this->equivalentAmount
            ]);

            // Redirect to referrals page
            return $this->redirect(route('rewards'), navigate: true);


        } catch (\Exception $e) {
            $this->addError('general', 'An error occurred while processing your request. Please try again.');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.rewards.redeem-bonus')
            ->layout('layouts.app.app')
            ->title('Redeem DCoins');
    }
}
