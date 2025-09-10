<?php

namespace App\Livewire\App\Exchange;

use Livewire\Component;

class Rewards extends Component
{
    public $referralCode;
    public $referralLink;
    public $totalRewards = 0.00; // Placeholder for total rewards

    public function mount()
    {
        $user = auth()->user();
        $this->referralCode = $user->referral_code;
        // Assuming you have a named route for registration that accepts a referral code
        $this->referralLink = route('register');
    }

    public function render()
    {
        return view('livewire.app.exchange.rewards')->layout('layouts.app.app')->title('Refer a Friend');
    }
}
