<?php

namespace App\Livewire\App\Exchange;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Profile extends Component
{
    public User $user;
    public $openTab = 'kyc'; // Default open tab: kyc, referrals, security

    // Personal Info / KYC properties
    public $fname;
    public $lname;
    public $phone;

    // Security properties
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->user = auth()->user()->load('latestKyc');
        $this->fname = $this->user->fname;
        $this->lname = $this->user->lname;
        $this->phone = $this->user->phone;
    }

    public function toggleTab($tab)
    {
        $this->openTab = $this->openTab === $tab ? null : $tab;
    }

    public function savePersonalInfo()
    {
        $this->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $this->user->update([
            'fname' => $this->fname,
            'lname' => $this->lname,
            'phone' => $this->phone,
        ]);

        session()->flash('personal_info_message', 'Personal information updated successfully.');
        $this->dispatch('notify', ['message' => 'Personal information updated.', 'type' => 'success']);
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password_message', 'Password changed successfully.');
        $this->dispatch('notify', ['message' => 'Password changed successfully.', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.app.exchange.profile')->layout('layouts.app.app')->title('Profile');
    }
}
