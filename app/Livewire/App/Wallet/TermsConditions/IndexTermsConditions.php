<?php

namespace App\Livewire\App\Wallet\TermsConditions;

use Livewire\Component;

class IndexTermsConditions extends Component
{
    public function render()
    {
        return view('livewire.app.wallet.terms-conditions.index-terms-conditions')->layout('layouts.app.app')->title('Terms and Conditions');
    }
}
