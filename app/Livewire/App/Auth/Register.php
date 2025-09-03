<?php

namespace App\Livewire\App\Auth;

use App\Mail\app\OtpVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Register extends Component
{
    public string $fname = '';
    public string $lname = '';
    public string $email = '';
    public string $password = '';
    public string $phone = '';
    public string $password_confirmation = '';
    public string $pin = '';

    protected array $rules = [
        'fname' => ['required', 'string', 'max:255'],
        'lname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'phone' => ['required', 'string', 'max:15'],
        'pin' => ['required', 'digits:4'],
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'fname' => $this->fname,
            'lname' => $this->lname,
            'email' => $this->email,
            'phone' => $this->phone,
            'pin' => Hash::make($this->pin),
            'is_admin' => 0,
            'password' => Hash::make($this->password),
            'username' => strtolower($this->fname.rand(1000, 9999)),
            'kyc_status' => 'unverified'
        ]);


        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_for_'.$this->email, $otp, now()->addMinutes(5));

        Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->fname));

        return $this->redirect(route('register.otp', ['email' => $user->email]));

    }

    public function render()
    {
        return view('livewire.app.auth.register')->layout('app.auth.layout.app', [
            'title' => 'Register',
        ]);
    }
}
