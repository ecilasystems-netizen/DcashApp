<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\WalletTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class WalletUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 25;
    public $selectedUserId = null;
    public $selectedUserTransactions = [];
    public $showTransactionsModal = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showUserTransactions($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedUserTransactions = WalletTransaction::where('user_id', $userId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
        $this->showTransactionsModal = true;
    }

    public function closeTransactionsModal()
    {
        $this->showTransactionsModal = false;
        $this->selectedUserId = null;
        $this->selectedUserTransactions = [];
    }

    public function render()
    {
        $users = User::with(['virtualBankAccount', 'walletTransactions'])
            ->whereHas('virtualBankAccount', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('fname', 'like', '%'.$this->search.'%')
                        ->orWhere('lname', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->withCount('walletTransactions')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.users.wallet-users', compact('users'))->layout('layouts.admin.app');
    }
}
