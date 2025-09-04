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
    public string $referral;
    public $referrerName;

    protected array $rules = [
        'referral' => ['nullable', 'exists:users,referral_code'],
        'fname' => ['required', 'string', 'max:255'],
        'lname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'phone' => ['required', 'string', 'max:15'],
        'pin' => ['required', 'digits:4'],
    ];

    public function updatedReferral($value)
    {
        if (!empty($value)) {
            $referrer = User::where('referral_code', $value)->first();
            if ($referrer) {
                $this->referrerName = $referrer->fname.' '.$referrer->lname;
                $this->resetValidation('referral');
            } else {
                $this->referrerName = null;
                $this->addError('referral', 'Invalid referral code.');
            }
        } else {
            $this->referrerName = null;
            $this->resetValidation('referral');
        }
    }

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
            'kyc_status' => 'unverified',
            'referral_code' => strtolower(mb_substr(trim($this->fname), 0, 5).rand(1000, 9999)),
            'referred_by' => $this->referral,
        ]);


        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_for_'.$this->email, $otp, now()->addMinutes(5));

        Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->fname));

        return $this->redirect(route('register.otp', ['email' => $user->email]));

    }

    public function render()
    {
        return view('livewire.app.auth.register')->layout('layouts.auth.app', [
            'title' => 'Register',
        ]);
    }
}
