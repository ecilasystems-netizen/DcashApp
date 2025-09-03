<?php

namespace App\Livewire\App\Kyc;

use Livewire\Component;

class UnderReview extends Component
{

    public function render()
    {
        return view('livewire.app.kyc.under-review')->layout('app.exchange.layouts.app')->title('KYC Under Review');
    }
}
