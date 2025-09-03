<?php

namespace App\Livewire\App\Wallet\CableTv;

use Livewire\Component;

class CreateCableTv extends Component
{
    public function render()
    {
        return view('livewire.app.wallet.cable-tv.create-cable-tv')->layout('app.exchange.layouts.app')->title('Pay Cable TV');
    }
}
