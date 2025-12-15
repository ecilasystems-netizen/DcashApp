<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule('required|email')]
    public $email = '';

    #[Rule('required|min:6')]
    public $password = '';

    public $remember = false;

    public $loginError = '';

    public function login()
    {
        $this->validate();

        // Check if credentials are valid
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Check if user is an admin or agent
            $user = Auth::user();
            if ($user && $user->is_admin || $user->is_agent) {
                // Redirect to admin dashboard or intended page
                if ($user->is_agent) {
                    return redirect()->intended('/agent/dashboard');
                } else {
                    return redirect()->intended('/admin/dashboard');
                }
            } else {
                Auth::logout();
                $this->loginError = 'You do not have admin access.';
                return;
            }
        } else {
            $this->loginError = 'Invalid credentials.';
        }
    }

    public function render()
    {
        return view('livewire.admin.auth.login')->layout('layouts.admin.authAdmin')->with([
            'title' => 'Admin Login',
            'description' => 'Login to the admin panel',
        ]);
    }
}
