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

    public $newBank = [];
    public $editingBank = null;
    public $editingBankId = null;
    public $newBankQr;
    public $editingBankQr;

    public $newWallet = [];
    public $editingWallet = null;
    public $editingWalletId = null;
    public $editingWalletQr;

    public function mount()
    {
        $this->newBank = $this->defaultBank();
        $this->newWallet = $this->defaultWallet();
        $this->loadData();
    }

    public function loadData()
    {
        $this->currencies = Currency::all();
        $this->bankAccounts = CompanyBankAccount::where('is_crypto', 0)->withCount('transactions')->latest()->get();
        $this->wallets = CompanyBankAccount::where('is_crypto', 1)->withCount('transactions')->latest()->get();
    }

    protected function defaultBank(): array
    {
        return [
            'currency_id' => null,
            'bank_name' => '',
            'account_name' => '',
            'account_number' => '',
            'position' => 1,
            'tab_name' => '',
            'is_active' => true,
            'qr' => null,
        ];
    }

    protected function defaultWallet(): array
    {
        return [
            'currency_id' => null,
            'crypto_name' => '',
            'crypto_network' => '',
            'crypto_wallet_address' => '',
            'qr' => null,
            'is_active' => true,
        ];
    }

    public function bankRules(): array
    {
        return [
            'currency_id' => 'required|exists:currencies,id',
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'position' => 'required|integer|min:0',
            'tab_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function walletRules(): array
    {
        return [
            'currency_id' => 'required|exists:currencies,id',
            'crypto_name' => 'required|string|max:255',
            'crypto_network' => 'required|string|max:255',
            'crypto_wallet_address' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function storeBankAccount()
    {
        $accountCount = CompanyBankAccount::where('currency_id', $this->newBank['currency_id'])
            ->where('is_crypto', 0)
            ->count();

        if ($accountCount >= 3) {
            $this->addError('newBank.currency_id', 'This currency already has the maximum of 3 bank accounts.');
            return;
        }

        $validatedData = $this->validate(collect($this->bankRules())->mapWithKeys(fn(
            $rule,
            $key
        ) => ["newBank.{$key}" => $rule])->all());

        $data = $validatedData['newBank'];

        if ($this->newBankQr) {
            $this->validate(['newBankQr' => 'image|max:1024']);
            $data['bank_account_qr_code'] = $this->newBankQr->store('images/bank_qr_codes', 'public');
        }

        $data['is_crypto'] = 0;
        CompanyBankAccount::create($data);

        $this->newBank = $this->defaultBank();
        $this->newBankQr = null;
        $this->loadData();
        $this->dispatch('close-bank-modal');
    }

    public function editBankAccount($id)
    {
        $this->editingBankId = $id;
        $bank = CompanyBankAccount::findOrFail($id);
        $this->editingBank = $bank->only(array_keys($this->defaultBank()));
        $this->editingBankQr = null;
        $this->dispatch('open-edit-bank-modal');
    }

    public function updateBankAccount()
    {
        if (!$this->editingBankId) {
            return;
        }

        $validatedData = $this->validate(collect($this->bankRules())->mapWithKeys(fn(
            $rule,
            $key
        ) => ["editingBank.{$key}" => $rule])->all());

        $bank = CompanyBankAccount::findOrFail($this->editingBankId);
        $data = $validatedData['editingBank'];

        if ($this->editingBankQr) {
            $this->validate(['editingBankQr' => 'image|max:1024']);
            $data['bank_account_qr_code'] = $this->editingBankQr->store('images/bank_qr_codes', 'public');
        }

        $bank->update($data);

        $this->editingBankId = null;
        $this->editingBank = null;
        $this->editingBankQr = null;
        $this->loadData();
        $this->dispatch('close-edit-bank-modal');
    }

    public function cancelEdit()
    {
        $this->editingBankId = null;
        $this->editingBank = null;
        $this->editingBankQr = null;
        $this->dispatch('close-edit-bank-modal');
    }

    public function storeWallet()
    {
        $validatedData = $this->validate(collect($this->walletRules())->mapWithKeys(fn(
            $rule,
            $key
        ) => ["newWallet.{$key}" => $rule])->all());

        $data = $validatedData['newWallet'];

        if (isset($this->newWallet['qr']) && $this->newWallet['qr']) {
            $this->validate(['newWallet.qr' => 'image|max:1024']);
            $data['crypto_qr_code'] = $this->newWallet['qr']->store('images/crypto_qr_codes', 'public');
        }

        $data['is_crypto'] = 1;
        CompanyBankAccount::create($data);

        $this->newWallet = $this->defaultWallet();
        $this->loadData();
        $this->dispatch('close-wallet-modal');
    }

    public function editWallet($id)
    {
        $this->editingWalletId = $id;
        $wallet = CompanyBankAccount::findOrFail($id);
        $this->editingWallet = $wallet->only(array_keys($this->defaultWallet()));
        $this->editingWalletQr = null;
        $this->dispatch('open-edit-wallet-modal');
    }

    public function updateWallet()
    {
        if (!$this->editingWalletId) {
            return;
        }

        $validatedData = $this->validate(collect($this->walletRules())->mapWithKeys(fn(
            $rule,
            $key
        ) => ["editingWallet.{$key}" => $rule])->all());

        $wallet = CompanyBankAccount::findOrFail($this->editingWalletId);
        $data = $validatedData['editingWallet'];

        if ($this->editingWalletQr) {
            $this->validate(['editingWalletQr' => 'image|max:1024']);
            $data['crypto_qr_code'] = $this->editingWalletQr->store('images/crypto_qr_codes', 'public');
        }

        $wallet->update($data);

        $this->editingWalletId = null;
        $this->editingWallet = null;
        $this->editingWalletQr = null;
        $this->loadData();
        $this->dispatch('close-edit-wallet-modal');
    }

    public function cancelWalletEdit()
    {
        $this->editingWalletId = null;
        $this->editingWallet = null;
        $this->editingWalletQr = null;
        $this->dispatch('close-edit-wallet-modal');
    }

    public function toggleStatus($id)
    {
        $model = CompanyBankAccount::findOrFail($id);
        $model->update(['is_active' => !$model->is_active]);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts.bank-account-list')
            ->layout('layouts.admin.app')
            ->title('Accounts');
    }
}
