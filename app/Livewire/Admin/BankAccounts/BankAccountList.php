<?php

namespace App\Livewire\Admin\BankAccounts;

use App\Models\CompanyBankAccount;
use App\Models\Currency;
use Livewire\Component;
use Livewire\WithFileUploads;

class BankAccountList extends Component
{
    use WithFileUploads;

    public $currencies = [];
    public $bankAccounts = [];
    public $wallets = [];

    public $newBank = [
        'currency_id'    => null,
        'bank_name'      => '',
        'account_name'   => '',
        'account_number' => '',
        'qr'             => null,
        'is_active'      => true,
    ];

    public $newWallet = [
        'currency_id'     => null,
        'crypto_name'     => '',
        'crypto_network'  => '',
        'crypto_wallet_address' => '',
        'qr'              => null,
        'is_active'       => true,
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->currencies   = Currency::all();
        $this->bankAccounts = CompanyBankAccount::where('is_crypto', 0)->withCount('transactions')->latest()->get();
        $this->wallets      = CompanyBankAccount::where('is_crypto', 1)->withCount('transactions')->latest()->get();
    }

    public function updatedNewBankQr() {
        // Trigger re-render for temporaryUrl()
    }

    public function updatedNewWalletQr() {
        // Trigger re-render for temporaryUrl()
    }

    public function storeBankAccount()
    {
        $this->validate($this->bankRules());

        $data = $this->newBank;

        // Handle QR code upload
        if ($data['qr']) {
            $path = $data['qr']->store('images/bank_qr_codes', 'public');
            $data['bank_account_qr_code'] = $path;
        }
        unset($data['qr']);

        // Set is_crypto flag for bank account
        $data['is_crypto'] = 0;

        CompanyBankAccount::create($data);

        $this->newBank = $this->defaultBank();
        $this->loadData();
        $this->dispatch('close-bank-modal');
    }

    public function bankRules(): array
    {
        return [
            'newBank.currency_id'    => 'required|exists:currencies,id',
            'newBank.bank_name'      => 'required|string|max:255',
            'newBank.account_name'   => 'required|string|max:255',
            'newBank.account_number' => 'required|string|max:255',
            'newBank.qr'             => 'nullable|image|max:1024',
            'newBank.is_active'      => 'boolean',
        ];
    }

    protected function defaultBank(): array
    {
        return [
            'currency_id'    => null,
            'bank_name'      => '',
            'account_name'   => '',
            'account_number' => '',
            'qr'             => null,
            'is_active'      => true,
        ];
    }

    public function storeWallet()
    {
        $this->validate($this->walletRules());

        $data = $this->newWallet;

        // Handle QR code upload
        if ($data['qr']) {
            $path = $data['qr']->store('images/crypto_qr_codes', 'public');
            $data['crypto_qr_code'] = $path;
        }
        unset($data['qr']);

        // Map wallet-specific field names to the database fields
        $data['wallet_name'] = $data['crypto_name'];
        $data['network'] = $data['crypto_network'];
        $data['address'] = $data['crypto_wallet_address'];

        // Set is_crypto flag for wallet
        $data['is_crypto'] = 1;

        CompanyBankAccount::create($data);

        $this->newWallet = $this->defaultWallet();
        $this->loadData();
        $this->dispatch('close-wallet-modal');
    }

    public function walletRules(): array
    {
        return [
            'newWallet.currency_id'          => 'required|exists:currencies,id',
            'newWallet.crypto_name'          => 'required|string|max:255',
            'newWallet.crypto_network'       => 'required|string|max:255',
            'newWallet.crypto_wallet_address' => 'required|string|max:255',
            'newWallet.qr'                   => 'nullable|image|max:1024',
            'newWallet.is_active'            => 'boolean',
        ];
    }

    protected function defaultWallet(): array
    {
        return [
            'currency_id'          => null,
            'crypto_name'          => '',
            'crypto_network'       => '',
            'crypto_wallet_address' => '',
            'qr'                   => null,
            'is_active'            => true,
        ];
    }

    public function toggleStatus($type, $id)
    {
        // Both bank accounts and wallets are stored in CompanyBankAccount
        $model = CompanyBankAccount::findOrFail($id);
        $model->update(['is_active' => !$model->is_active]);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts.bank-account-list')
            ->layout('layouts.admin.app')
            ->title('Accounts')
            ->with([
                'currencies'   => $this->currencies,
                'bankAccounts' => $this->bankAccounts,
                'wallets'      => $this->wallets,
            ]);
    }
}
