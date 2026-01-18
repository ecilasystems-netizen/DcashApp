<?php

declare(strict_types=1);

namespace App\Livewire\App\Wallet\CableTv;

use App\Models\SafehavenTvBundle;
use App\Services\SafeHavenApi\SafehavenTvBillsService;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateCableTv extends Component
{
    protected SafehavenTvBillsService $tvBillsService;

    // Form fields
    public ?string $selectedProvider = null;
    public ?string $cardNumber = null;
    public ?string $selectedBundleCode = null;
    public float $amount = 0;

    // Verification data
    public ?string $customerName = null;
    public ?string $customerAddress = null;
    public bool $isVerified = false;

    // UI state
    public bool $showConfirmationModal = false;
    public bool $showPinModal = false;
    public ?string $pin = null;
    public array $availableBundles = [];
    public array $providers = [];

    public function boot(SafehavenTvBillsService $tvBillsService): void
    {
        $this->tvBillsService = $tvBillsService;
    }

    public function mount(): void
    {
        $this->providers = $this->tvBillsService->getSupportedProviders();
    }

    /**
     * Load bundles when provider is selected
     */
    public function updatedSelectedProvider(): void
    {
        if (!$this->selectedProvider) {
            $this->availableBundles = [];
            return;
        }

        $this->availableBundles = $this->tvBillsService
            ->getBundlesForProvider($this->selectedProvider)
            ->toArray();

        // Reset dependent fields
        $this->reset(['selectedBundleCode', 'amount', 'isVerified', 'customerName', 'customerAddress']);
    }

    /**
     * Update amount when bundle is selected
     */
    public function updatedSelectedBundleCode(): void
    {
        if (!$this->selectedBundleCode) {
            $this->amount = 0;
            return;
        }

        $bundle = $this->tvBillsService->getBundleByCode($this->selectedBundleCode);

        if ($bundle) {
            $this->amount = (float) $bundle->amount;
        }
    }

    /**
     * Verify smartcard/IUC number
     */
    public function verifyCard(): void
    {
        $this->validate([
            'cardNumber' => ['required', 'string', 'size:10'], // Changed from min:10, max:20
            'selectedProvider' => ['required', 'string'],
        ], [
            'cardNumber.required' => 'Please enter your smartcard/IUC number',
            'cardNumber.size' => 'Smartcard/IUC number must be exactly 10 characters',
            'selectedProvider.required' => 'Please select a TV provider',
        ]);

        $result = $this->tvBillsService->verifyUser(
            $this->cardNumber,
            $this->selectedProvider
        );

        if (in_array($result['status'], [200, 201])) {
            $data = $result['json']['data'] ?? [];

            $this->customerName = $data['name'] ?? $data['customerName'] ?? null;
            $this->customerAddress = $data['address'] ?? $data['customerAddress'] ?? null;
            $this->isVerified = true;

            $this->dispatch('card-verified');
            session()->flash('success', 'Smartcard verified successfully!');
        } else {
            $this->isVerified = false;
            $this->customerName = null;
            $this->customerAddress = null;

            $errorMessage = $result['json']['message'] ?? 'Failed to verify smartcard. Please check the number and try again.';
            session()->flash('error', $errorMessage);
        }
    }

    /**
     * Show confirmation modal before purchase
     */
    public function showConfirmation(): void
    {
        $this->validate([
            'cardNumber' => ['required', 'string'],
            'selectedProvider' => ['required', 'string'],
            'selectedBundleCode' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:100'],
        ]);

        if (!$this->isVerified) {
            session()->flash('error', 'Please verify your smartcard number first.');
            return;
        }

        $this->showConfirmationModal = true;
    }

    /**
     * Proceed to PIN entry after confirmation
     */
    public function proceedToPin(): void
    {
        $this->showConfirmationModal = false;
        $this->showPinModal = true;
        $this->pin = null; // Reset PIN
    }

    /**
     * Purchase TV subscription with PIN verification
     */
    public function purchaseSubscription(): void
    {
        $this->validate([
            'pin' => ['required', 'string', 'size:4', 'regex:/^\d{4}$/'],
        ], [
            'pin.required' => 'Please enter your 4-digit PIN',
            'pin.size' => 'PIN must be exactly 4 digits',
            'pin.regex' => 'PIN must contain only numbers',
        ]);

        if (!$this->isVerified) {
            session()->flash('error', 'Please verify your smartcard number first.');
            $this->showPinModal = false;
            return;
        }

        $result = $this->tvBillsService->purchaseTvBill(
            cardNumber: $this->cardNumber,
            bundleCode: $this->selectedBundleCode,
            serviceCategoryId: $this->selectedProvider,
            amount: $this->amount
        );

        $this->showPinModal = false;

        if (in_array($result['status'], [200, 201])) {
            session()->flash('success', 'TV subscription purchased successfully!');

            // Reset form
            $this->reset([
                'cardNumber',
                'selectedBundleCode',
                'amount',
                'customerName',
                'customerAddress',
                'isVerified',
                'pin'
            ]);

            return;
        }

        $errorMessage = $result['json']['message'] ?? 'Failed to purchase subscription. Please try again.';
        session()->flash('error', $errorMessage);
    }

    public function updatedCardNumber(): void
    {
        // Reset verification state when user modifies card number
        $this->reset(['isVerified', 'customerName', 'customerAddress']);

        // Auto-verify when cardNumber reaches exactly 10 characters
        if ($this->selectedProvider && strlen($this->cardNumber) === 10) {
            $this->verifyCard();
        }
    }

    /**
     * Get selected bundle details
     */
    public function getSelectedBundleProperty(): ?array
    {
        if (!$this->selectedBundleCode || empty($this->availableBundles)) {
            return null;
        }

        return collect($this->availableBundles)
            ->firstWhere('bundleCode', $this->selectedBundleCode);
    }

    /**
     * Get formatted provider name
     */
    public function getProviderNameProperty(): ?string
    {
        return $this->providers[$this->selectedProvider] ?? null;
    }

    public function render()
    {
        return view('livewire.app.wallet.cable-tv.create-cable-tv')
            ->layout('layouts.app.app')
            ->title('Pay Cable TV');
    }
}
