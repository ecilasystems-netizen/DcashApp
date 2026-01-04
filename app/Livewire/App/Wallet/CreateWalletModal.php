<?php

declare(strict_types=1);

namespace App\Livewire\App\Wallet;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateWalletModal extends Component
{
    public bool $showModal = false;

    public string $fullName = '';
    public string $dateOfBirth = '';
    public string $bvn = '';

    // Listen for an event to open this modal from other components
    protected $listeners = ['openCreateWalletModal' => 'open'];

    protected array $rules = [
        'fullName' => 'required|string|min:3',
        'dateOfBirth' => 'required|date|before:today',
        'bvn' => 'required|digits:11',
    ];

    public function open(): void
    {
        $this->reset(['fullName', 'dateOfBirth', 'bvn']);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function acceptTermsAndCreateWallet(): void
    {
        $this->validate();

        // Logic to create the wallet goes here
        // You can move the logic from your dashboard component here

        Log::info('Wallet Creation Initiated', [
            'bvn' => $this->bvn,
            'dob' => $this->dateOfBirth
        ]);

        // Simulate processing
        sleep(1);

        $this->showModal = false;

        // Dispatch success event
        $this->dispatch('wallet-created');
        $this->dispatch('notify', type: 'success', message: 'Wallet created successfully!');
    }

    public function render()
    {
        return view('livewire.app.wallet.create-wallet-modal');
    }
}
