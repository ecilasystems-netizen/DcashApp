<?php

namespace App\Livewire\App\Kyc;

use App\Jobs\ProcessSelfieUpload;
use Livewire\Component;
use Livewire\WithFileUploads;

class Selfie extends Component
{
    use WithFileUploads;

    public function mount()
    {
        \Log::info('kyc_personal_info session:', ['data' => session('kyc_personal_info')]);
        // Check if previous steps were completed
        if (!session('kyc_personal_info')) {
            return $this->redirect(route('kyc.personal-info'), navigate: true);
        }

        if (!session('kyc_documents')) {
            return $this->redirect(route('kyc.upload-documents'), navigate: true);
        }

        //prevent user from doing a new KYC if there's already pending KYC
        if (auth()->user()->kyc_status === 'pending') {
            return $this->redirect(route('kyc.under-review'), navigate: true);
        }
    }

    public function saveSelfie($base64Image)
    {
        try {
            // Get session data first
            $personalInfo = session('kyc_personal_info');
            $documents = session('kyc_documents');

            // Generate a filename that will be used by the job
            $filename = 'selfie_'.auth()->id().'_'.time().'.png';
            $storagePath = 'kyc/selfies/'.$filename;

            // Dispatch the job to process the selfie upload
            ProcessSelfieUpload::dispatch(
                $base64Image,
                $filename,
                $personalInfo,
                $documents,
                auth()->id()
            );

            // Update user status immediately
            auth()->user()->update(['kyc_status' => 'pending']);

            // Clear the session data since it's been passed to the job
            session()->forget(['kyc_personal_info', 'kyc_documents']);

            // Set a success message
            session()->flash('success', 'Your selfie has been uploaded and is being processed');

            // Redirect to the under review page
            return $this->redirect(route('kyc.under-review'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Failed to queue selfie upload: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Failed to process your KYC application. Please try again.');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.app.kyc.selfie')
            ->layout('layouts.app.app')
            ->title('Selfie Verification');
    }
}
