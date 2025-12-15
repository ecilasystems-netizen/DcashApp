<?php

namespace App\Livewire\Agent;

use App\Models\ExchangeTransaction;
use Carbon\Carbon;
use Livewire\Component;

class AgentDashboard extends Component
{

    public $search = '';
    public $pendingCount = 0;
    public $pendingTransactions;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->pendingTransactions = ExchangeTransaction::where('status', 'pending_confirmation')
            ->where('agent_id', auth()->id())
            ->with(['fromCurrency', 'toCurrency', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $this->pendingCount = $this->pendingTransactions->count();
    }

    public function searchTransactions()
    {
        if (empty($this->search)) {
            return;
        }

        return redirect()->route('agent.transactions', ['search' => $this->search]);
    }

    public function getStatsProperty()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $lastMonth = Carbon::now()->subMonth();

        // Current month transactions assigned to this agent
        $currentTransactions = ExchangeTransaction::where('agent_id', auth()->id())
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $lastMonthTransactions = ExchangeTransaction::where('agent_id', auth()->id())
            ->whereBetween('created_at', [$lastMonth->copy()->subDays(30), $lastMonth])
            ->count();

        $transactionGrowth = $lastMonthTransactions > 0 ? (($currentTransactions - $lastMonthTransactions) / $lastMonthTransactions) * 100 : 0;

        // Volume handled by this agent
        $currentVolume = ExchangeTransaction::where('status', 'completed')
            ->where('agent_id', auth()->id())
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount_to');

        $lastMonthVolume = ExchangeTransaction::where('status', 'completed')
            ->where('agent_id', auth()->id())
            ->whereBetween('created_at', [$lastMonth->copy()->subDays(30), $lastMonth])
            ->sum('amount_to');

        $volumeGrowth = $lastMonthVolume > 0 ? (($currentVolume - $lastMonthVolume) / $lastMonthVolume) * 100 : 0;

        // Pending transactions for this agent
        $pendingTransactions = ExchangeTransaction::where('status', 'pending_confirmation')
            ->where('agent_id', auth()->id())
            ->count();

        // Completed transactions for this agent
        $completedTransactions = ExchangeTransaction::where('status', 'completed')
            ->where('agent_id', auth()->id())
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        return [
            'total_transactions' => $currentTransactions,
            'transaction_growth' => round($transactionGrowth, 1),
            'total_volume' => $currentVolume,
            'volume_growth' => round($volumeGrowth, 1),
            'pending_transactions' => $pendingTransactions,
            'completed_transactions' => $completedTransactions,
        ];
    }

    public function getRecentTransactionsProperty()
    {
        return ExchangeTransaction::with(['user', 'fromCurrency', 'toCurrency'])
            ->where('agent_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    }

    public function getChartDataProperty()
    {
        $days = [];
        $volumes = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D');

            $dayVolume = ExchangeTransaction::where('status', 'completed')
                ->where('agent_id', auth()->id())
                ->whereDate('created_at', $date)
                ->sum('amount_to');

            $volumes[] = (float) $dayVolume;
        }

        $transactionStats = [
            'completed' => ExchangeTransaction::where('agent_id', auth()->id())->where('status', 'completed')->count(),
            'pending' => ExchangeTransaction::where('agent_id', auth()->id())->where('status',
                'pending_confirmation')->count(),
            'failed' => ExchangeTransaction::where('agent_id', auth()->id())->where('status', 'failed')->count(),
        ];

        return [
            'days' => $days,
            'volumes' => $volumes,
            'transaction_stats' => $transactionStats,
        ];
    }

    public function render()
    {
        return view('livewire.agent.agent-dashboard')->layout('layouts.admin.app', [
            'title' => 'Agent Dashboard',
            'description' => 'Agent Dashboard',
        ]);
    }

}
