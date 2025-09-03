<?php

namespace App\Livewire\App\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    public function login()
    {
        $credentials = $this->validate();

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return $this->redirect(route('dashboard'), navigate: true);
        }

        $this->addError('email', 'These credentials do not match our records.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.app.auth.login')->layout('app.auth.layout.app', [
            'title' => 'Login',
        ]);
    }
}
