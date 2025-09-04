<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class PasswordResetSuccessful extends Component
{
    public function render()
    {
        return view('livewire.app.auth.password-reset-successful')->
        layout('layouts.auth.app', [
            'title' => 'Password Reset Successful',
        ]);
    }
}
