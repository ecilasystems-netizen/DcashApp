<?php

declare(strict_types=1);

namespace App\Livewire\App\Kyc;

use App\Models\VirtualBankAccount;
use App\Services\SafeHavenApi\AccountsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

final class BvnVerificationModal extends Component
{
    public bool $showModal = false;

    public string $bvn = '';

    public string $phoneNumber = '';

    public string $dateOfBirth = '';

    #[Locked]
    public bool $bvnInitiated = false;

    #[Locked]
    public ?string $phoneSuffix = null;

    #[Locked]
    public bool $isCompleted = false;

    #[Locked]
    public array $createdAccountDetails = [];

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    protected function rules(): array
    {
        return [
            'bvn' => ['required', 'digits:11'],
            'phoneNumber' => ['required', 'regex:/^0[789][01]\d{8}$/'],
            'dateOfBirth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
        ];
    }

    protected function messages(): array
    {
        return [
            'bvn.required' => 'BVN is required',
            'bvn.digits' => 'BVN must be exactly 11 digits',
            'phoneNumber.required' => 'Phone number is required',
            'phoneNumber.regex' => 'Please enter a valid Nigerian phone number starting with 070, 080, 081, or 090',
            'dateOfBirth.required' => 'Date of birth is required',
            'dateOfBirth.date' => 'Please enter a valid date',
            'dateOfBirth.before' => 'Date of birth must be in the past',
            'dateOfBirth.after' => 'Please enter a valid date of birth',
        ];
    }

    #[Computed]
    public function currentStep(): int
    {
        if ($this->isCompleted) {
            return 5;
        }

        if ($this->bvnInitiated) {
            return 4;
        }

        return 1;
    }

    public function initiateBvnVerification(): void
    {
        $this->validate(['bvn' => ['required', 'digits:11']]);

        //check if user virtual ACCOUNT PROVIDER IS SAFEWHAVEN
        $user = auth()->user();
        $virtualAccount = VirtualBankAccount::where('user_id', $user->id)->first();
        if ($virtualAccount->provider === 'safehaven') {
            $this->errorMessage = 'You already have a Virtual Bank Account';
            return;
        }

        $this->resetMessages();

        try {
            $response = app(AccountsService::class)->initiateBvnVerification($this->bvn);

            if ($response['status'] !== 200 || !isset($response['json']['message'])) {
                $this->errorMessage = $response['message'] ?? 'Failed to verify BVN. Please check the number and try again.';
                return;
            }

            // Extract phone suffix from message
            if (!preg_match('/ending with (\d{4})/', $response['json']['message'], $matches)) {
                $this->errorMessage = 'Unable to retrieve phone number information. Please try again.';
                return;
            }

            $this->phoneSuffix = $matches[1];
            $this->bvnInitiated = true;
            $this->successMessage = "BVN verified! Please confirm your phone number ending with {$this->phoneSuffix} and date of birth.";

            $this->dispatch('bvn-initiated');

            Log::info('BVN verification initiated successfully', [
                'user_id' => auth()->id(),
                'phone_suffix' => $this->phoneSuffix,
            ]);
        } catch (\Exception $e) {
            Log::error('BVN verification initiation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'bvn_prefix' => substr($this->bvn, 0, 3),
            ]);

            $this->errorMessage = 'An error occurred while verifying your BVN. Please try again later.';
        }
    }

    public function confirmAndComplete(): void
    {
        $this->validate([
            'phoneNumber' => ['required', 'regex:/^0[789][01]\d{8}$/'],
            'dateOfBirth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
        ]);

        $this->resetMessages();

        // Verify phone number matches BVN
        if (substr($this->phoneNumber, -4) !== $this->phoneSuffix) {
            $this->errorMessage = "Phone number doesn't match the one linked to your BVN (ending with {$this->phoneSuffix})";
            return;
        }

        try {
            $this->createdAccountDetails = DB::transaction(function (): array {
                $user = auth()->user();

                $payload = [
                    'email' => $user->email,
                    'phone' => $this->phoneNumber,
                    'externalReference' => $this->generateExternalReference(),
                    'identityNumber' => $this->bvn,
                    'dateOfBirth' => $this->dateOfBirth,
                    'booleanMatch' => true,
                ];

                Log::info('Creating SafeHaven sub-account', [
                    'user_id' => $user->id,
                    'external_reference' => $payload['externalReference'],
                ]);

                $response = app(AccountsService::class)->createSafeHavenSubAccount($payload);

                if ($response['status'] !== 200) {
                    throw new \RuntimeException(
                        $response['json']['message'] ?? 'Failed to create virtual bank account'
                    );
                }

                $accountData = $response['json']['data'] ?? [];

                // Update user KYC information
                $user->update([
                    'bvn' => $this->bvn,
                    'dob' => $this->dateOfBirth,
                ]);

                // Create/update virtual bank account
                $user->virtualBankAccount()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'account_name' => $accountData['accountName'] ?? null,
                        'account_number' => $accountData['accountNumber'] ?? null,
                        'bank_name' => 'SafeHaven Bank',
                        'provider' => 'safehaven',
                        'is_active' => true,
                    ]
                );

                Log::info('Virtual bank account created successfully', [
                    'user_id' => $user->id,
                    'account_number' => $accountData['accountNumber'] ?? 'N/A',
                ]);

                return [
                    'bankName' => 'SafeHaven Bank',
                    'accountNumber' => $accountData['accountNumber'] ?? 'N/A',
                    'accountName' => $accountData['accountName'] ?? 'N/A',
                ];
            });

            $this->isCompleted = true;
            $this->dispatch('bvn-verified');
        } catch (\Exception $e) {
            Log::error('Account creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $this->errorMessage = 'Failed to create your virtual account. Please try again or contact support.';
        }
    }

    public function resetBvnEntry(): void
    {
        $this->bvnInitiated = false;
        $this->phoneSuffix = null;
        $this->phoneNumber = '';
        $this->dateOfBirth = '';
        $this->resetMessages();
    }

    #[On('openModal')]
    public function openModal(): void
    {
        $this->resetState();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->resetState();
        $this->showModal = false;
    }

    public function closeModalAndNavigate(): void
    {
        $this->closeModal();
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.app.kyc.bvn-verification-modal');
    }

    private function resetState(): void
    {
        $this->reset([
            'bvn',
            'phoneNumber',
            'dateOfBirth',
            'bvnInitiated',
            'phoneSuffix',
            'isCompleted',
            'createdAccountDetails',
            'errorMessage',
            'successMessage',
        ]);
    }

    private function resetMessages(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
    }

    private function generateExternalReference(): string
    {
        return sprintf('%d_%d', random_int(1000000000, 9999999999), time());
    }
}
