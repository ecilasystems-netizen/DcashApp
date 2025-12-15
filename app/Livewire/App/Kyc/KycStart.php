<?php

namespace App\Livewire\App\Kyc;

use Livewire\Component;

class KycStart extends Component
{
    //check if the user has a pending kyc verification on mount
    public function mount()
    {
        $user = auth()->user();
        if ($user->kyc_status === 'pending') {
            return $this->redirect(route('kyc.under-review'));
        }
        if ($user->kyc_status === 'verified') {
            return $this->redirect(route('dashboard'));
        }
    }

    public function startVerification()
    {
        return $this->redirect(route('kyc.personal-info'));
    }

    public function render()
    {
        return view('livewire.app.kyc.kyc-start')->layout('layouts.app.app')->title('Start KYC Verification');
    }


}
