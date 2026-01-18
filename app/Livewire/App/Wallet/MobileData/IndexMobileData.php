<?php

declare(strict_types=1);

namespace App\Livewire\App\Wallet\MobileData;

use App\Models\SafehavenDataBundle;
use App\Services\SafeHavenApi\SafehavenDataBundleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class IndexMobileData extends Component
{
    public ?string $selectedNetwork = null;
    public string $phoneNumber = '';
    public ?int $selectedBundleId = null; // Changed from selectedBundleCode
    public string $currentTab = 'daily';
    public array $availableBundles = [];
    public array $networks = [];
    public int $userBalance;
    public bool $showConfirmModal = false;
    public bool $showSuccessModal = false;
    public bool $processing = false;
    public ?string $pin = null;
    public ?string $pinError = null;

    protected SafehavenDataBundleService $dataBundleService;

    protected $rules = [
        'selectedNetwork' => 'required|string',
        'phoneNumber' => 'required|string|min:10|max:11',
        'selectedBundleId' => 'required|integer|exists:safehaven_data_bundles,id',
        'pin' => 'required|digits:4'
    ];

    protected $messages = [
        'selectedNetwork.required' => 'Please select a network provider',
        'phoneNumber.required' => 'Please enter a phone number',
        'phoneNumber.min' => 'Phone number must be at least 10 digits',
        'phoneNumber.max' => 'Phone number must not exceed 11 digits',
        'selectedBundleId.required' => 'Please select a data bundle',
        'selectedBundleId.exists' => 'Invalid data bundle selected',
        'pin.required' => 'Please enter your 4-digit PIN',
        'pin.digits' => 'PIN must be exactly 4 digits',
    ];

    public function boot(SafehavenDataBundleService $dataBundleService): void
    {
        $this->dataBundleService = $dataBundleService;
    }

    public function mount(): void
    {
        $providers = $this->dataBundleService->getSupportedProviders();
        $logos = $this->dataBundleService->getNetworkLogos();

        foreach ($providers as $code => $name) {
            $this->networks[] = [
                'code' => $code,
                'name' => $name,
                'logo' => $logos[$code] ?? null
            ];
        }

        $this->userBalance = (int) (auth()->user()->wallet->balance ?? 0);
    }

    public function selectNetwork(string $networkCode): void
    {
        $this->selectedNetwork = $networkCode;
        $this->loadDataBundles();
        $this->reset(['selectedBundleId']);
    }

    public function loadDataBundles(): void
    {
        if (!$this->selectedNetwork) {
            $this->availableBundles = [];
            return;
        }

        $bundles = $this->dataBundleService->getBundlesForProvider($this->selectedNetwork);

        $this->availableBundles = [
            'daily' => $bundles->get('daily', collect())->map(fn($bundle) => [
                'id' => $bundle->id,
                'bundle_code' => $bundle->bundle_code,
                'data_size' => $bundle->data_size,
                'duration_days' => $bundle->duration_days,
                'amount' => $bundle->amount,
            ])->toArray(),
            'weekly' => $bundles->get('weekly', collect())->map(fn($bundle) => [
                'id' => $bundle->id,
                'bundle_code' => $bundle->bundle_code,
                'data_size' => $bundle->data_size,
                'duration_days' => $bundle->duration_days,
                'amount' => $bundle->amount,
            ])->toArray(),
            'monthly' => $bundles->get('monthly', collect())->map(fn($bundle) => [
                'id' => $bundle->id,
                'bundle_code' => $bundle->bundle_code,
                'data_size' => $bundle->data_size,
                'duration_days' => $bundle->duration_days,
                'amount' => $bundle->amount,
            ])->toArray(),
            'others' => $bundles->get('others', collect())->map(fn($bundle) => [
                'id' => $bundle->id,
                'bundle_code' => $bundle->bundle_code,
                'data_size' => $bundle->data_size,
                'duration_days' => $bundle->duration_days,
                'amount' => $bundle->amount,
            ])->toArray(),
        ];
    }

    public function selectTab(string $tab): void
    {
        $this->currentTab = $tab;
    }

    public function selectBundle(int $bundleId): void
    {
        $this->selectedBundleId = $bundleId;
    }

    public function canProceed(): bool
    {
        return $this->selectedNetwork &&
            $this->phoneNumber &&
            strlen($this->phoneNumber) >= 10 &&
            $this->selectedBundleId !== null;
    }

    public function updatedPin(): void
    {
        $this->pinError = null;
    }

    public function openConfirmationModal(): void
    {
        $this->validate([
            'selectedNetwork' => 'required|string',
            'phoneNumber' => 'required|string|min:10|max:11',
            'selectedBundleId' => 'required|integer|exists:safehaven_data_bundles,id',
        ]);

        $this->showConfirmModal = true;
        $this->pinError = null;
        $this->pin = null;
    }

    public function cancelPurchase(): void
    {
        $this->showConfirmModal = false;
        $this->pin = null;
        $this->pinError = null;
    }

    public function confirmPurchase(): void
    {
        $this->validate();

        $this->processing = true;
        $this->pinError = null;

        try {
            $reference = 'data_bundle_'.time().'_'.auth()->id();

            if (!Hash::check($this->pin, auth()->user()->pin)) {
                $this->pinError = 'Invalid PIN. Please try again.';
                $this->processing = false;
                return;
            }

            $user = auth()->user();
            $bundle = $this->getSelectedBundle();

            if (!$bundle) {
                $this->pinError = 'Selected bundle not found. Please try again.';
                $this->processing = false;
                return;
            }

            $amount = (int) $bundle->amount_in_naira;
            $wallet = $user->wallet;
            $balanceBefore = $wallet->balance;

            if ($balanceBefore < $amount) {
                $this->pinError = 'Insufficient balance. Please fund your wallet.';
                $this->processing = false;
                return;
            }

            $updated = DB::table('wallets')
                ->where('id', $wallet->id)
                ->where('balance', '>=', $amount)
                ->decrement('balance', $amount);

            if (!$updated) {
                $this->pinError = 'Insufficient balance. Please try again.';
                $this->processing = false;
                return;
            }

            $balanceAfter = $balanceBefore - $amount;

            $transaction = $user->wallet->transactions()->create([
                'reference' => $reference,
                'type' => 'data',
                'direction' => 'debit',
                'user_id' => $user->id,
                'amount' => $amount,
                'charge' => 0,
                'description' => "Data bundle ({$bundle->data_size}) to {$this->phoneNumber}",
                'status' => 'pending',
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'metadata' => [
                    'phone_number' => $this->phoneNumber,
                    'network' => $this->selectedNetwork,
                    'bundle_id' => $this->selectedBundleId,
                    'bundle_code' => $bundle->bundle_code,
                    'data_size' => $bundle->data_size,
                    'validity' => $bundle->validity,
                    'queued_at' => now()->toDateTimeString()
                ],
            ]);

            // Dispatch job to process the purchase
            \App\Jobs\SafeHaven\SafeHavenProcessDataBundlePurchaseJob::dispatch(
                $reference,
                $user->id,
                $this->phoneNumber,
                $this->selectedBundleId,
                $this->selectedNetwork,
                $amount
            );

            $this->showConfirmModal = false;
            $this->showSuccessModal = true;
            session()->flash('success', 'Data bundle purchase is being processed!');

        } catch (\Exception $e) {
            Log::error('IndexMobileData - confirmPurchase exception', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->pinError = 'An error occurred. Please try again.';
        }

        $this->processing = false;
    }

    public function getSelectedBundle(): ?SafehavenDataBundle
    {
        if (!$this->selectedBundleId) {
            return null;
        }

        return SafehavenDataBundle::find($this->selectedBundleId);
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
        $this->reset(['selectedNetwork', 'phoneNumber', 'selectedBundleId', 'pin', 'pinError']);
        $this->availableBundles = [];

        $this->redirect(route('dashboard'));
    }

    #[Computed]
    public function selectedNetworkRecord(): ?array
    {
        if (!$this->selectedNetwork) {
            return null;
        }

        return collect($this->networks)->firstWhere('code', $this->selectedNetwork);
    }

    #[Computed]
    public function selectedAmount(): int
    {
        $bundle = $this->getSelectedBundle();
        return $bundle ? (int) $bundle->amount / 100 : 0;
    }

    public function render()
    {
        return view('livewire.app.wallet.mobile-data.index-mobile-data')
            ->layout('layouts.app.app')
            ->title('Buy Data Bundle');
    }
}
