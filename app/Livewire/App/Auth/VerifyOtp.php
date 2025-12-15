<?php

namespace App\Livewire\App\Auth;

use App\Mail\OtpMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class VerifyOtp extends Component
{
    public $email;
    public $otp;
    public $otp_sent = false;

    public function mount()
    {
        $this->email = session('password-reset-email');
        if (!$this->email) {
            return redirect()->route('reset-password');
        }
        $this->sendOtp();
    }

    public function sendOtp()
    {
        $user = User::where('email', $this->email)->first();
        if (!$user) {
            session()->flash('error', 'No account found with that email address.');
            return redirect()->route('reset-password');
        }

        $otp = rand(100000, 999999);
        PasswordResetToken::updateOrCreate(
            ['email' => $this->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        Mail::to($this->email)->send(new OtpMail($otp, $user->fname));
        $this->otp_sent = true;
        session()->flash('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $resetToken = PasswordResetToken::where('email', $this->email)
            ->where('token', $this->otp)
            ->first();

        if (!$resetToken || Carbon::parse($resetToken->created_at)->addMinutes(10)->isPast()) {
            session()->flash('error', 'Invalid or expired OTP.');
            return;
        }

        session(['otp-verified' => true]);
        return redirect()->route('reset-password.new-password');
    }


    public function render()
    {
        return view('livewire.app.auth.verify-otp')->layout('layouts.auth.app')->title('Verify OTP');
    }
}
