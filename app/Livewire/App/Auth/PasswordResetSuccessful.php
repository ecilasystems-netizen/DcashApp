<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class PasswordResetSuccessful extends Component
{
    public function render()
    {
        return view('livewire.app.auth.password-reset-successful')->
            layout('app.auth.layout.app', [
                'title' => 'Password Reset Successful',
            ]);
    }
}
