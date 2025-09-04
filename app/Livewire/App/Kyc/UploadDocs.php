<?php

namespace App\Livewire\App\Kyc;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadDocs extends Component
{
    use WithFileUploads;

    public $idType;
    public $frontId;
    public $backId;
    public $passportPage;
    public $idNumber;

    public function mount()
    {
        // Check if personal info was completed
        if (!session('kyc_personal_info')) {
            return $this->redirect(route('kyc.personal-info'), navigate: true);
        }

        //prevent user from doing a new KYC if there's already pending KYC
        if (auth()->user()->kyc_status === 'pending') {
            return $this->redirect(route('kyc.under-review'), navigate: true);
        }
    }

    public function updatedIdType()
    {
        $this->frontId = null;
        $this->backId = null;
        $this->passportPage = null;
    }

    public function removeFront()
    {
        $this->frontId = null;
    }

    public function removeBack()
    {
        $this->backId = null;
    }

    public function removePassportPage()
    {
        $this->passportPage = null;
    }


    public function submit()
    {
        if ($this->idType === 'passport') {
            $validated = $this->validate([
                'idType' => 'required|string',
                'idNumber' => 'required|string|max:50',
                'passportPage' => 'required|image|max:5120',
            ]);

            $passportUrl = $this->passportPage->store('kyc/documents', 'public');

            session([
                'kyc_documents' => [
                    'idType' => $validated['idType'],
                    'idNumber' => $validated['idNumber'],
                    'frontId' => $passportUrl
                ]
            ]);
        } else {
            $validated = $this->validate([
                'idType' => 'required|string',
                'idNumber' => 'required|string|max:50',
                'frontId' => 'required|image|max:5120',
                'backId' => 'required|image|max:5120',
            ]);

            $frontUrl = $this->frontId->store('kyc/documents', 'public');
            $backUrl = $this->backId->store('kyc/documents', 'public');

            session([
                'kyc_documents' => [
                    'idType' => $validated['idType'],
                    'idNumber' => $validated['idNumber'],
                    'frontId' => $frontUrl,
                    'backId' => $backUrl
                ]
            ]);
        }

        return $this->redirect(route('kyc.selfie'), navigate: true);
    }

    public function render()
    {
        return view('livewire.app.kyc.upload-docs')
            ->layout('layouts.app.app')
            ->title('Upload Documents');
    }
}
