<?php

namespace App\Livewire\Admin\Users;

use App\Models\KycVerification;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $kycFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'kycFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingKycFilter()
    {
        $this->resetPage();
    }

    public function suspendUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'suspended'; // Use the correct status field name
            $user->save();
            session()->flash('message', 'User has been suspended.');
        }
    }

    public function activateUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'active'; // Use the correct status field name
            $user->save();
            session()->flash('message', 'User has been activated.');
        }
    }

    public function blockUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'blocked'; // Use the correct status field name
            $user->save();
            session()->flash('message', 'User has been blocked.');
        }
    }

    public function render()
    {
        $usersQuery = User::query()
            ->with('latestKyc')
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('fname', 'like', '%' . $this->search . '%')
                        ->orWhere('lname', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->kycFilter, function ($query) {
                if ($this->kycFilter === 'verified') {
                    return $query->where('kyc_status', 'verified');
                } elseif ($this->kycFilter === 'pending') {
                    return $query->whereHas('latestKyc', function ($q) {
                        $q->where('kyc_status', 'pending');
                    });
                } elseif ($this->kycFilter === 'not-submitted') {
                    return $query->doesntHave('kycVerifications');
                }
            });

        $users = $usersQuery->orderBy('created_at', 'desc')->paginate($this->perPage);

        // Get statistics
        $totalUsers = User::count();
        $verifiedUsers = User::where('kyc_status', 'verified')->count();
        $pendingKyc = KycVerification::where('status', 'pending')->distinct('user_id')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $blockedUsers = User::where('status', 'blocked')->count();

        $stats = [
            'total' => $totalUsers,
            'verified' => $verifiedUsers,
            'pending' => $pendingKyc,
            'suspended' => $suspendedUsers,
            'blocked' => $blockedUsers,
        ];

        return view('livewire.admin.users.user-list', [
            'users' => $users,
            'stats' => $stats,
        ])->layout('layouts.admin.app', [
            'title' => 'Users',
            'description' => 'List of all users',
        ]);
    }
}
