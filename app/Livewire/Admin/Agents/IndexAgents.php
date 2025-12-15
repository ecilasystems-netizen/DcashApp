<?php

namespace App\Livewire\Admin\Agents;

use App\Models\ExchangeTransaction;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class IndexAgents extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function suspendAgent($agentId)
    {
        $agent = User::where('is_agent', 1)->find($agentId);
        if ($agent) {
            $agent->status = 'suspended';
            $agent->save();
            session()->flash('message', 'Agent has been suspended.');
        }
    }

    public function activateAgent($agentId)
    {
        $agent = User::where('is_agent', 1)->find($agentId);
        if ($agent) {
            $agent->status = 'active';
            $agent->save();
            session()->flash('message', 'Agent has been activated.');
        }
    }

    public function blockAgent($agentId)
    {
        $agent = User::where('is_agent', 1)->find($agentId);
        if ($agent) {
            $agent->status = 'blocked';
            $agent->save();
            session()->flash('message', 'Agent has been blocked.');
        }
    }

    public function render()
    {
        $agentsQuery = User::query()
            ->where('is_agent', 1)
            ->withCount([
                'exchangeTransactions as total_transactions',
                'exchangeTransactions as completed_transactions' => function ($query) {
                    $query->where('status', 'completed');
                },
                'exchangeTransactions as pending_transactions' => function ($query) {
                    $query->where('status', 'pending_confirmation');
                },
            ])
            ->withSum('exchangeTransactions as total_volume', 'amount_to')
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('fname', 'like', '%'.$this->search.'%')
                        ->orWhere('lname', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            });

        $agents = $agentsQuery->orderBy('created_at', 'desc')->paginate($this->perPage);

        // Get statistics
        $totalAgents = User::where('is_agent', 1)->count();
        $activeAgents = User::where('is_agent', 1)->where('status', 'active')->count();
        $suspendedAgents = User::where('is_agent', 1)->where('status', 'suspended')->count();
        $blockedAgents = User::where('is_agent', 1)->where('status', 'blocked')->count();

        // Performance stats
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $totalTransactionsThisMonth = ExchangeTransaction::whereNotNull('agent_id')
            ->where('created_at', '>=', $thisMonth)
            ->count();

        $totalVolumeThisMonth = ExchangeTransaction::whereNotNull('agent_id')
            ->where('created_at', '>=', $thisMonth)
            ->where('status', 'completed')
            ->sum('amount_to');

        $stats = [
            'total' => $totalAgents,
            'active' => $activeAgents,
            'suspended' => $suspendedAgents,
            'blocked' => $blockedAgents,
            'transactions_this_month' => $totalTransactionsThisMonth,
            'volume_this_month' => $totalVolumeThisMonth,
        ];

        return view('livewire.admin.agents.index-agents', [
            'agents' => $agents,
            'stats' => $stats,
        ])->layout('layouts.admin.app', [
            'title' => 'Agent Management',
            'description' => 'Manage agents and view their performance',
        ]);
    }
}
