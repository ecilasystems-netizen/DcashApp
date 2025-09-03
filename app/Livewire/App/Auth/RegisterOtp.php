<?php

namespace App\Livewire\App\Auth;

use App\Mail\app\OtpVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class RegisterOtp extends Component
{
    public string $otp = '';
    public string $email = '';

    protected array $rules = [
        'otp' => ['required', 'string', 'size:6'],
    ];

    public function mount($email)
    {
        $this->email = $email;
    }

    public function verifyOtp()
    {
        $this->validate();

        $cachedOtp = Cache::get('otp_for_' . $this->email);

        if ($cachedOtp && $cachedOtp === $this->otp) {
            $user = User::where('email', $this->email)->first();
            $user->email_verified_at = now();
            $user->save();

            auth()->login($user);
            Cache::forget('otp_for_' . $this->email);

            return $this->redirect(route('success', [
                'title' => 'Account Verified',
                'message' => 'Your account has been successfully verified.',
                'redirectTo' => route('dashboard'),
                'redirectAfter' => 3, // seconds
            ]));
        }

        $this->addError('otp', 'Invalid OTP code.');
        $this->reset('otp');
    }

    public function resendOtp()
    {
        $user = User::where('email', $this->email)->first();
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_for_' . $this->email, $otp, now()->addMinutes(5));

        Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->fname));

        $this->dispatch('otp-resent');
    }

    public function render()
    {
        return view('livewire.app.auth.register-otp')->layout('app.auth.layout.app', [
            'title' => 'Verify Email',
        ]);
    }
}
