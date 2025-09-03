<?php

namespace App\Livewire\App\Exchange;

use Livewire\Component;

class Rewards extends Component
{
    public function render()
    {
        return view('livewire.app.exchange.rewards')->layout('app.exchange.layouts.app')->title('Rewards');
    }
}
