<?php

namespace App\Livewire\App\Auth;

use App\Mail\app\OtpVerificationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
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
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                Auth::logout();

                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                Cache::put('otp_for_'.$this->email, $otp, now()->addMinutes(5));

                Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->fname));

                return $this->redirect(route('register.otp', ['email' => $user->email]), navigate: true);

            }

            session()->regenerate();
            return $this->redirect(route('dashboard'), navigate: true);
        }

        $this->addError('email', 'These credentials do not match our records.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.app.auth.login')->layout('layouts.auth.app', [
            'title' => 'Login',
        ]);
    }
}
