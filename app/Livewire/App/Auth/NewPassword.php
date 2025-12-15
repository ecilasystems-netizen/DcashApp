<?php

namespace App\Livewire\App\Auth;

use App\Mail\PasswordResetNotificationMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\DeviceInfoService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class NewPassword extends Component
{
    public $email;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->email = session('password-reset-email');
        if (!session('otp-verified') || !$this->email) {
            session()->forget(['password-reset-email', 'otp-verified']);
            return redirect()->route('reset-password');
        }
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $this->email)->first();
        if ($user) {
            $user->password = Hash::make($this->password);
            $user->save();

            // Create instance of DeviceInfoService
            $deviceInfoService = app(DeviceInfoService::class);
            $deviceInfo = $deviceInfoService->getDeviceInfo();

            // Here you can log the device info or send a notification email to the user
            $browser = $deviceInfo['browser'];
            $location = $deviceInfo['location'];

            //send email notification
            Mail::to($this->email)->send(new PasswordResetNotificationMail($user->fname, $browser, $location));

            PasswordResetToken::where('email', $this->email)->delete();
            session()->forget(['password-reset-email', 'otp-verified']);

            return redirect()->route('reset-password.success');
        }

        session()->flash('error', 'An unexpected error occurred. Please try again.');
    }

    public function render()
    {
        return view('livewire.app.auth.new-password')->
        layout('layouts.auth.app', [
            'title' => 'New Password',
        ]);
    }
}
