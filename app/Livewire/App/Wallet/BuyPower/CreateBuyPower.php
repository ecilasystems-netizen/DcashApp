<?php

namespace App\Livewire\App\Wallet\BuyPower;

use Livewire\Component;

class CreateBuyPower extends Component
{
    public function render()
    {
        return view('livewire.app.wallet.buy-power.create-buy-power')->layout('layouts.app.app')->title('Buy Power');
    }
}
