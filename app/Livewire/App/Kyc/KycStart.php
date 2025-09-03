<?php

namespace App\Livewire\App\Kyc;

use Livewire\Component;

class KycStart extends Component
{
    public function startVerification()
    {
        return $this->redirect(route('kyc.personal-info'));
    }
    
    public function render()
    {
        return view('livewire.app.kyc.kyc-start')->layout('app.exchange.layouts.app')->title('Start KYC Verification');
    }


}
