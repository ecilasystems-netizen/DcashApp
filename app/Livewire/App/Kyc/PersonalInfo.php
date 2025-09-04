<?php

namespace App\Livewire\App\Kyc;

use Livewire\Component;

class PersonalInfo extends Component
{
    public $fullName;
    public $dob;
    public $nationality;
    public $address;
    public $bvn;

    public function mount()
    {
        // Prevent user from doing a new KYC if there's already pending KYC
        if (auth()->user()->kyc_status === 'pending') {
            return $this->redirect(route('kyc.under-review'), navigate: true);
        }
    }

    public function submit()
    {
        $validated = $this->validate([
            'fullName' => 'required|string|max:255',
            'dob' => 'required|date',
            'nationality' => 'required|string',
            'address' => 'required|string|max:255',
            'bvn' => 'nullable|string|max:11|min:11',
        ]);

        // Store the validated data in session
        session(['kyc_personal_info' => $validated]);

        // Redirect to the next page
        return $this->redirect(route('kyc.upload-documents'), navigate: true);
    }

    public function render()
    {
        return view('livewire.app.kyc.personal-info')
            ->layout('layouts.app.appx')
            ->title('Personal Information');
    }
}
