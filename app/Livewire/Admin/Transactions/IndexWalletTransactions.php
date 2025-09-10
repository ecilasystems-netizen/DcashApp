<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class IndexWalletTransactions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $typeFilter = '';
    public $perPage = 50;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];
    protected $paginationTheme = 'tailwind';

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFilter', 'typeFilter']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function approveTransaction($transactionId)
    {
        $transaction = WalletTransaction::findOrFail($transactionId);
        $transaction->update(['status' => 'completed']);
        session()->flash('message', 'Transaction approved successfully.');
    }

    public function rejectTransaction($transactionId, $reason)
    {
        $transaction = WalletTransaction::findOrFail($transactionId);
        $transaction->update([
            'status' => 'rejected',
            'description' => $transaction->description.' | Rejection Reason: '.$reason
        ]);
        session()->flash('message', 'Transaction rejected successfully.');
    }

    public function deleteTransaction($transactionId)
    {
        WalletTransaction::findOrFail($transactionId)->delete();
        session()->flash('message', 'Transaction deleted successfully.');
    }

    private function getTransactionsQuery(): Builder
    {
        return WalletTransaction::with(['user', 'wallet.currency'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('fname', 'like', '%'.$this->search.'%')
                                ->orWhere('lname', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->typeFilter, fn($query) => $query->where('type', $this->typeFilter))
            ->when($this->dateFilter, fn($query) => $query->whereDate('created_at', $this->dateFilter))
            ->latest();
    }

    public function getTransactionsProperty()
    {
        return $this->getTransactionsQuery()->paginate($this->perPage);
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => WalletTransaction::count(),
            'pending' => WalletTransaction::where('status', 'pending')->count(),
            'completed' => WalletTransaction::where('status', 'completed')->count(),
            'failed' => WalletTransaction::whereIn('status', ['failed', 'rejected'])->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.transactions.index-wallet-transactions')->layout('layouts.admin.app', [
            'title' => 'Wallet Transactions',
            'description' => 'List of all wallet transactions',
        ]);
    }
}
