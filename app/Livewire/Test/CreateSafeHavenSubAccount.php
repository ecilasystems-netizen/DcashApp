<?php

namespace App\Livewire\Test;

use App\Services\SafeHavenService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateSafeHavenSubAccount extends Component
{
    public $step = 1;
    public $bvn, $reference, $otp, $identityNumber, $identityId, $externalReference;
    public $firstName, $lastName, $email, $phone;
    public $bvnData;
    public $error, $loading = false;


    protected ?SafeHavenService $service = null;

    protected function service(): SafeHavenService
    {
        return $this->service ??= app(SafeHavenService::class);
    }

    public function submitBvn()
    {
        $this->resetErrorBag();
        $this->loading = true;

        Log::info('submitBvn - payload', ['bvn' => $this->bvn]);
        $result = $this->service()->initiateBvnVerification($this->bvn);
        $this->loading = false;

        // log full result for debugging
        Log::info('submitBvn - full result', ['result' => $result]);

        // check for successful response
        if (in_array($result['status'], [200, 201]) && isset($result['json']['data']['_id'])) {
            $this->reference = $result['json']['data']['_id'];
            $this->step = 2;
            Log::info('submitBvn - success', [
                'reference' => $this->reference,
                'message' => $result['json']['message'] ?? ''
            ]);
            return;
        }

        // handle error cases
        $errorMessage = $result['json']['message'] ?? 'Failed to initiate BVN verification.';
        $this->error = $errorMessage;

        Log::error('submitBvn - error', [
            'status' => $result['status'],
            'message' => $errorMessage,
            'full_response' => $result
        ]);
    }

    public function submitOtp()
    {
        $this->resetErrorBag();
        $this->loading = true;

        Log::info('submitOtp - payload', ['reference' => $this->reference, 'otp' => $this->otp]);
        $result = $this->service()->validateBvnOtp($this->reference, $this->otp);
        $this->loading = false;

        // log full result for debugging
        Log::info('submitOtp - full result', ['result' => $result]);

        // check for successful response
        if (in_array($result['status'], [200, 201]) && isset($result['json']['data'])) {
            $this->bvnData = $result['json']['data'];

            // Extract from providerResponse if available
            $providerData = $this->bvnData['providerResponse'] ?? [];

            $this->bvn = $this->bvnData['identityNumber'] ?? $this->bvn;
            $this->firstName = $providerData['firstName'] ?? $this->firstName;
            $this->lastName = $providerData['lastName'] ?? $this->lastName;
            $this->email = $providerData['email'] ?? $this->email;
            $this->phone = $providerData['phoneNumber1'] ?? $providerData['phoneNumber2'] ?? $this->phone;
            $this->identityNumber = $this->bvnData['identityNumber'] ?? null;
            $this->identityId = $this->bvnData['_id'] ?? null;
            $this->externalReference = 'EXT-'.strtoupper(uniqid());

            $this->step = 3;
            Log::info('submitOtp - success', [
                'bvn_data' => $this->bvnData,
                'message' => $result['json']['message'] ?? ''
            ]);
            return;
        }

        // handle error cases
        $errorMessage = $result['json']['message'] ?? 'OTP validation failed.';
        $this->error = $errorMessage;

        Log::error('submitOtp - error', [
            'status' => $result['status'],
            'message' => $errorMessage,
            'full_response' => $result
        ]);
    }

    public function submitSubAccount()
    {
        $this->resetErrorBag();
        $this->loading = true;

        $payload = [
            'bvn' => $this->bvn,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'otp' => $this->otp,
            'identityId' => $this->identityId,
            'identityNumber' => $this->identityNumber,
            'externalReference' => $this->externalReference
        ];

        Log::info('submitSubAccount - payload', $payload);
        $result = $this->service()->createSafeHavenSubAccount($payload);
        $this->loading = false;

        // log full result for debugging
        Log::info('submitSubAccount - full result', ['result' => $result]);

        // check for successful response
        if (in_array($result['status'], [200, 201]) && isset($result['json']['data'])) {
            session()->flash('success', 'Sub account created successfully!');
            $this->step = 4;
            Log::info('submitSubAccount - success', [
                'sub_account' => $result['json']['data'],
                'message' => $result['json']['message'] ?? ''
            ]);
            return;
        }

        // handle error cases
        $errorMessage = $result['json']['message'] ?? 'Sub account creation failed.';
        $errorDetails = $result['json']['error'] ?? $result['json']['errors'] ?? null;
        $errorCode = $result['json']['code'] ?? $result['json']['errorCode'] ?? null;
        $validationErrors = $result['json']['data']['errors'] ?? $result['json']['validationErrors'] ?? null;

        $this->error = $errorMessage;

        Log::error('submitSubAccount - error', [
            'status' => $result['status'],
            'message' => $errorMessage,
            'error_code' => $errorCode,
            'error_details' => $errorDetails,
            'validation_errors' => $validationErrors,
            'response_body' => $result['json'] ?? null,
            'full_response' => $result
        ]);
    }

    public function render()
    {
        return view('livewire.test.create-safe-haven-sub-account')->layout('layouts.auth.app');
    }
}
