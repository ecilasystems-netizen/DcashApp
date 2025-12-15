<?php

namespace App\Livewire\Agent;

use App\Models\ExchangeTransaction;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AgentTransactions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $perPage = 50;
    public $viewMode = 'my_transactions'; // 'my_transactions' or 'available_orders'

    public $selectedBank = '';
    public $banks = [
        'Polaris Bank',
        'Providus Bank',
        'Guaranty Trust Bank (GTBank)',
        'United Bank for Africa (UBA)',
        'Zenith Bank',
        'Opay',
        'Kuda Bank',
        'Rubies Bank',
        'Fidelity Bank',
        'Access Bank',
        'SafeHaven ',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'viewMode' => ['except' => 'my_transactions']
    ];

    protected $paginationTheme = 'tailwind';

    public function switchToMyTransactions()
    {
        $this->viewMode = 'my_transactions';
        $this->resetPage();
    }

    public function switchToAvailableOrders()
    {
        $this->viewMode = 'available_orders';
        $this->resetPage();
    }

    public function acceptOrder($transactionId)
    {
        $transaction = ExchangeTransaction::where('id', $transactionId)
            ->where('status', 'pending_confirmation')
            ->whereNull('agent_id')
            ->firstOrFail();

        $transaction->update([
            'agent_id' => auth()->id()
        ]);

        session()->flash('message', 'Order accepted successfully. You can now process this transaction.');

        // Switch to my transactions view to see the newly accepted order
        $this->viewMode = 'my_transactions';
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFilter']);
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

    public function approveTransaction($transactionId, $selectedBank = null)
    {
        $transaction = ExchangeTransaction::where('id', $transactionId)
            ->where('agent_id', auth()->id())
            ->firstOrFail();


        // If NGN transaction and bank is selected, store it in the note
        if ($transaction->toCurrency->code === 'NGN' && $selectedBank) {
            $note = $transaction->note ?? [];
            $note['transfer_bank'] = $selectedBank;
            $transaction->note = $note;
        }

        $transaction->update([
            'status' => 'completed'
        ]);

        session()->flash('message', 'Transaction approved successfully.');
    }

    public function rejectTransaction($transactionId, $reason)
    {
        $transaction = ExchangeTransaction::where('id', $transactionId)
            ->where('agent_id', auth()->id())
            ->firstOrFail();

        $transaction->update([
            'status' => 'rejected',
            'note' => $reason
        ]);

        session()->flash('message', 'Transaction rejected successfully.');
    }

    public function exportCsv()
    {
        $transactions = $this->getTransactionsQuery()->get();

        $response = new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

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
        if ($this->viewMode === 'available_orders') {
            return ExchangeTransaction::with(['user', 'fromCurrency', 'toCurrency'])
                ->where('status', 'pending_confirmation')
                ->whereNull('agent_id')
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
                ->when($this->dateFilter, function ($query) {
                    $query->whereDate('created_at', $this->dateFilter);
                })
                ->latest();
        }

        return ExchangeTransaction::with(['user', 'fromCurrency', 'toCurrency'])
            ->where('agent_id', auth()->id())
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

    public function getTransactionsProperty()
    {
        return $this->getTransactionsQuery()->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $availableOrders = ExchangeTransaction::where('status', 'pending_confirmation')
            ->whereNull('agent_id')
            ->count();

        if ($this->viewMode === 'available_orders') {
            return [
                'available' => $availableOrders,
                'total_unassigned' => ExchangeTransaction::whereNull('agent_id')->count(),
            ];
        }

        return [
            'total' => ExchangeTransaction::where('agent_id', auth()->id())->count(),
            'pending' => ExchangeTransaction::where('agent_id', auth()->id())->where('status',
                'pending_confirmation')->count(),
            'completed' => ExchangeTransaction::where('agent_id', auth()->id())->where('status', 'completed')->count(),
            'failed' => ExchangeTransaction::where('agent_id', auth()->id())->whereIn('status',
                ['failed', 'rejected'])->count(),
            'available' => $availableOrders, // Always include this
        ];
    }

    public function render()
    {
        $title = $this->viewMode === 'available_orders' ? 'Available Orders' : 'My Transactions';
        $description = $this->viewMode === 'available_orders' ? 'Accept new orders to process' : 'Manage transactions assigned to me';

        return view('livewire.agent.agent-transactions')->layout('layouts.admin.app', [
            'title' => $title,
            'description' => $description,
        ]);
    }
}
