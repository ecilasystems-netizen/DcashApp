<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class ResetPassword extends Component
{
    public $email;

    public function continue()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'The provided email does not match any account.'
        ]);

        session(['password-reset-email' => $this->email]);

        return redirect()->route('reset-password.verify-otp');
    }

    public function render()
    {
        return view('livewire.app.auth.reset-password')->
        layout('layouts.auth.app', [
            'title' => 'Reset Password',
        ]);
    }
}
