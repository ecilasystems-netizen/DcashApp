<?php

namespace App\Livewire\App\Exchange;

use Livewire\Component;

class Profile extends Component
{
    public function mount()
    {
        $this->user = auth()->user()->load('latestKyc');
    }

    public function render()
    {
        return view('livewire.app.exchange.profile')->layout('app.exchange.layouts.app')->title('Profile');
    }
}
