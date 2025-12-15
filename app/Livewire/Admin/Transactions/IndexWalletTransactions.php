<?php

namespace App\Livewire\Admin\Transactions;

use App\Mail\RefundMail;
use App\Models\WalletTransaction;
use App\Services\SafeHavenService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
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

    public $safeHavenAccount = null;
    public $safeHavenAccountBalance = null;

    public function fetchSafeHavenAccounts()
    {
        try {
            $safeHaven = new SafeHavenService();

            // Get specific account details
            $this->safeHavenAccount = $safeHaven->getAccountByNumber('0119358126');

            // Set balance if account exists
            if ($this->safeHavenAccount) {
                $this->safeHavenAccountBalance = $this->safeHavenAccount['accountBalance'];
            }

        } catch (\Exception $e) {
            logger()->error('SafeHaven account fetch failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function mount()
    {
        $this->fetchSafeHavenAccounts();

    }


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

    public function refundTransaction($transactionId)
    {
        $transaction = WalletTransaction::findOrFail($transactionId);

        if ($transaction->status !== 'failed') {
            session()->flash('error', 'Only failed transactions can be refunded.');
            return;
        }

        // Create refund transaction
        $refundTransaction = WalletTransaction::create([
            'reference' => 'REF-'.uniqid(),
            'wallet_id' => $transaction->wallet_id,
            'user_id' => $transaction->user_id,
            'direction' => $transaction->direction === 'debit' ? 'credit' : 'debit',
            'type' => 'refund',
            'amount' => $transaction->amount,
            'charge' => 0,
            'description' => "Refund for transaction #{$transaction->reference}",
            'status' => 'completed',
            'balance_before' => $transaction->wallet->balance,
            'balance_after' => $transaction->wallet->balance + $transaction->amount,
            'metadata' => ['original_transaction_id' => $transaction->id],
        ]);

        // Update wallet balance
        $transaction->wallet->increment('balance', $transaction->amount);

        // Update original transaction status
        $transaction->update(['status' => 'refunded']);

        // Send refund email notification
        Mail::to($transaction->user->email)->send(
            new RefundMail(
                $transaction,
                $refundTransaction,
                $transaction->user->fname.' '.$transaction->user->lname
            )
        );

        session()->flash('message', 'Transaction refunded successfully.');
    }

    public function render()
    {
        return view('livewire.admin.transactions.index-wallet-transactions')->layout('layouts.admin.app', [
            'title' => 'Wallet Transactions',
            'description' => 'List of all wallet transactions',
        ]);
    }
}
