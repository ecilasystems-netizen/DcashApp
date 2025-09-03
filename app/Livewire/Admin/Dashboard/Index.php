<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\ExchangeTransaction;
use App\Models\KycVerification;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    public $pendingCount = 0;
    public $pendingKycs;
    public $pendingTransactions;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        // ... existing mount code ...
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->pendingKycs = KycVerification::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $this->pendingTransactions = ExchangeTransaction::where('status', 'pending_confirmation')
            ->with(['fromCurrency', 'toCurrency'])
            ->latest()
            ->take(5)
            ->get();

        $this->pendingCount = $this->pendingKycs->count() + $this->pendingTransactions->count();
    }

    public function searchUsers()
    {
        if (empty($this->search)) {
            return;
        }

        // Redirect to users page with search parameter
        return redirect()->route('admin.users', ['search' => $this->search]);
    }

    public function reviewKyc($kycId)
    {
        return redirect()->route('admin.kyc', ['kyc_id' => $kycId]);
    }

    public function getStatsProperty()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $lastMonth = Carbon::now()->subMonth();

        $currentUsers = User::count();
        $lastMonthUsers = User::where('created_at', '<=', $lastMonth)->count();
        $userGrowth = $lastMonthUsers > 0 ? (($currentUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;

        $currentVolume = ExchangeTransaction::where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount_to');

        $lastMonthVolume = ExchangeTransaction::where('status', 'completed')
            ->whereBetween('created_at', [$lastMonth->copy()->subDays(30), $lastMonth])
            ->sum('amount_to');

        $volumeGrowth = $lastMonthVolume > 0 ? (($currentVolume - $lastMonthVolume) / $lastMonthVolume) * 100 : 0;

        $pendingKyc = KycVerification::where('status', 'pending')->count();

        // Calculate revenue (assuming 1% fee on completed transactions)
        $currentRevenue = ExchangeTransaction::where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount_to') * 0.01;

        $lastMonthRevenue = ExchangeTransaction::where('status', 'completed')
            ->whereBetween('created_at', [$lastMonth->copy()->subDays(30), $lastMonth])
            ->sum('amount_to') * 0.01;

        $revenueGrowth = $lastMonthRevenue > 0 ? (($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return [
            'total_users' => $currentUsers,
            'user_growth' => round($userGrowth, 1),
            'total_volume' => $currentVolume,
            'volume_growth' => round($volumeGrowth, 1),
            'pending_kyc' => $pendingKyc,
            'revenue' => $currentRevenue,
            'revenue_growth' => round($revenueGrowth, 1),
        ];
    }

    public function getRecentTransactionsProperty()
    {
        return ExchangeTransaction::with(['user', 'fromCurrency', 'toCurrency'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function getRecentKycProperty()
    {
        return KycVerification::with('user')
            ->where('status', 'pending')
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
                ->whereDate('created_at', $date)
                ->sum('amount_to');

            $volumes[] = (float) $dayVolume;
        }

        $userStats = [
            'verified' => User::where('kyc_status', 'verified')->count(),
            'pending' => User::where('kyc_status', 'pending')->count(),
            'new' => User::where('kyc_status', 'unverified')->count(),
        ];

        return [
            'days' => $days,
            'volumes' => $volumes,
            'user_stats' => $userStats,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index')->layout('layouts.admin.app', [
            'title' => 'Dashboard',
            'description' => 'Admin Dashboard',
        ]);
    }
}
