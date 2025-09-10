<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\ExchangeTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $perPage = 50;


    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => '']
    ];
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Get the last used status filter from session or use current status
        $this->statusFilter = Session::get('transaction_status_filter', $this->statusFilter);
    }

    public function updatedStatusFilter($value)
    {
        // Store the new status filter in session
        Session::put('transaction_status_filter', $value);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFilter']);
        $this->statusFilter = '';
        Session::forget('transaction_status_filter');
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

    public function approveTransaction($transactionId)
    {
        $transaction = ExchangeTransaction::findOrFail($transactionId);
        $transaction->update([
            'status' => 'completed',
            'note' => null
        ]);

        session()->flash('message', 'Transaction approved successfully.');
    }

    public function rejectTransaction($transactionId, $reason)
    {
        $transaction = ExchangeTransaction::findOrFail($transactionId);
        $transaction->update([
            'status' => 'rejected',
            'note' => $reason
        ]);

        session()->flash('message', 'Transaction rejected successfully.');
    }

    public function deleteTransaction($transactionId)
    {
        ExchangeTransaction::findOrFail($transactionId)->delete();

        session()->flash('message', 'Transaction deleted successfully.');
    }

    public function exportCsv()
    {
        $transactions = $this->getTransactionsQuery()->get();

        $response = new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, [
                'Transaction ID',
                'User Name',
                'Email',
                'From Currency',
                'To Currency',
                'Amount From',
                'Amount To',
                'Rate',
                'Status',
                'Date Created'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->reference,
                    $transaction->user->fname.' '.$transaction->user->lname,
                    $transaction->user->email,
                    $transaction->fromCurrency->code ?? 'N/A',
                    $transaction->toCurrency->code ?? 'N/A',
                    $transaction->amount_from,
                    $transaction->amount_to,
                    $transaction->rate,
                    $transaction->status,
                    $transaction->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="transactions_'.date('Y-m-d').'.csv"');

        return $response;
    }

    private function getTransactionsQuery(): Builder
    {
        return ExchangeTransaction::with(['user', 'fromCurrency', 'toCurrency'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('fname', 'like', '%'.$this->search.'%')
                                ->orWhere('lname', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('created_at', $this->dateFilter);
            })
            ->latest();
    }

    public function exportExcel()
    {
        $transactions = $this->getTransactionsQuery()->get();

        $response = new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fputs($handle, "\xEF\xBB\xBF");

            // Add headers
            fputcsv($handle, [
                'Transaction ID',
                'User Name',
                'Email',
                'Phone',
                'From Currency',
                'To Currency',
                'Amount From',
                'Amount To',
                'Rate',
                'Recipient Bank',
                'Account Number',
                'Account Name',
                'Status',
                'Date Created'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->reference,
                    $transaction->user->fname.' '.$transaction->user->lname,
                    $transaction->user->email,
                    $transaction->user->phone ?? 'N/A',
                    $transaction->fromCurrency->code ?? 'N/A',
                    $transaction->toCurrency->code ?? 'N/A',
                    $transaction->amount_from,
                    $transaction->amount_to,
                    $transaction->rate,
                    $transaction->recipient_bank_name ?? 'N/A',
                    $transaction->recipient_account_number ?? 'N/A',
                    $transaction->recipient_account_name ?? 'N/A',
                    $transaction->status,
                    $transaction->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="transactions_'.date('Y-m-d').'.xlsx"');

        return $response;
    }

    public function getTransactionsProperty()
    {
        return $this->getTransactionsQuery()->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'total' => ExchangeTransaction::count(),
            'pending' => ExchangeTransaction::where('status', 'pending_confirmation')->count(),
            'completed' => ExchangeTransaction::where('status', 'completed')->count(),
            'failed' => ExchangeTransaction::whereIn('status', ['failed', 'rejected'])->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.transactions.transaction-list')->layout('layouts.admin.app', [
            'title' => 'Transactions',
            'description' => 'List of all transactions',
        ]);
    }
}
