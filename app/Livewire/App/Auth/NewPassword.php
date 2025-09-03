<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class NewPassword extends Component
{
    public function render()
    {
        return view('livewire.app.auth.new-password')->
            layout('app.auth.layout.app', [
                'title' => 'New Password',
            ]);
    }
}
