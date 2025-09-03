<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class ResetPassword extends Component
{
    public function render()
    {
        return view('livewire.app.auth.reset-password')->
            layout('app.auth.layout.app', [
                'title' => 'Reset Password',
            ]);
    }
}
