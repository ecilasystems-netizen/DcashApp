<?php

namespace App\Livewire\Admin\Rewards;

use App\Models\Bonus;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class BonusManagement extends Component
{
    use WithPagination;

    public $selectedUserId;
    public $selectedUser;
    public $bonusType = '';
    public $amount = '';
    public $notes = '';
    public $search = '';
    public $showSuccessModal = false;

    public $presetAmounts = [10, 25, 50, 100, 250, 500, 1000];

    protected $rules = [
        'selectedUserId' => 'required|exists:users,id',
        'bonusType' => 'required|in:welcome,referral,promotional,loyalty,special',
        'amount' => 'required|numeric|min:1|max:100000',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedUserId.required' => 'Please select a user.',
        'bonusType.required' => 'Please select a bonus type.',
        'amount.required' => 'Please enter an amount.',
        'amount.min' => 'Amount must be at least $1.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedUser = User::find($userId);
        $this->search = '';
    }

    public function clearUser()
    {
        $this->selectedUserId = null;
        $this->selectedUser = null;
    }

    public function setPresetAmount($amount)
    {
        $this->amount = $amount;
    }

    public function submitBonus()
    {
        $this->validate();

        Bonus::create([
            'user_id' => $this->selectedUserId,
            'type' => $this->bonusType,
            'bonus_amount' => $this->amount,
            'notes' => $this->notes,
            'status' => 'credited',
            'trigger_event' => 'manual_admin',
            'referral_bonus_id' => null,
        ]);

        $this->showSuccessModal = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->bonusType = '';
        $this->amount = '';
        $this->notes = '';
        $this->resetValidation();
    }

    public function render()
    {
        $users = [];
        if ($this->search) {
            $users = User::where(function ($query) {
                $query->where('fname', 'like', '%'.$this->search.'%')
                    ->orWhere('lname', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
                ->where('status', 'active')
                ->limit(10)
                ->get();
        }

        $recentBonuses = Bonus::with('user')
            ->where('trigger_event', 'manual_admin')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_bonuses' => Bonus::where('trigger_event', 'manual_admin')->count(),
            'total_amount' => Bonus::where('trigger_event', 'manual_admin')->sum('bonus_amount'),
            'this_month' => Bonus::where('trigger_event', 'manual_admin')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'active_users' => User::where('status', 'active')->count(),
        ];

        return view('livewire.admin.rewards.bonus-management', [
            'users' => $users,
            'recentBonuses' => $recentBonuses,
            'stats' => $stats,
        ])->layout('layouts.admin.app', [
            'title' => 'Bonus Management',
            'description' => 'Bonus Management Dashboard',
        ]);
    }
}
