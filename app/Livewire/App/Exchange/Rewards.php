<?php

namespace App\Livewire\App\Exchange;

use Livewire\Component;

class Rewards extends Component
{
    public $referralCode;
    public $kycStatus;

    public function mount()
    {
        //get referral code of the authenticated user
        $referralCode = auth()->user()->referral_code;
        $this->referralCode = $referralCode;
        if (auth()->user()->kyc_status == 'approved') {
            $this->kycStatus = "approved";
        } else {
            $this->kycStatus = false;
        }
    }

    public function render()
    {
        return view('livewire.app.exchange.rewards')->layout('layouts.app.app')->title('Rewards');
    }
}
