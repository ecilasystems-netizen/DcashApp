<?php

namespace App\Livewire\Admin\Kyc;

use App\Models\KycVerification;
use Livewire\Component;
use Livewire\WithPagination;

class KycList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $selectedKyc = null;
    public $showModal = false;
    public $rejectionReason = '';

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = KycVerification::with('user')
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                return $query->whereDate('created_at', $this->dateFilter);
            })
            ->when($this->searchTerm, function ($query) {
                return $query->whereHas('user', function ($q) {
                    $q->where('fname', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('lname', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->orWhere('first_name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
            });

        $kycVerifications = $query->latest()->paginate(10);

        $stats = [
            'total' => KycVerification::count(),
            'pending' => KycVerification::where('status', 'pending')->count(),
            'approved' => KycVerification::where('status', 'approved')->count(),
            'rejected' => KycVerification::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.kyc.kyc-list', [
            'kycVerifications' => $kycVerifications,
            'stats' => $stats,
        ])->layout('layouts.admin.app', [
            'title' => 'KYC List',
            'description' => 'List of all KYC applications',
        ]);
    }

    public function viewKyc($id)
    {
        $this->selectedKyc = KycVerification::with('user')->find($id);
        $this->showModal = true;
    }

    public function approveKyc()
    {
        if ($this->selectedKyc) {
            $this->selectedKyc->update([
                'status' => 'approved',
                'verified_at' => now(),
            ]);

            // also update the user's KYC status
            $this->selectedKyc->user->update(['kyc_status' => 'verified']);

            $this->showModal = false;
            session()->flash('message', 'KYC application has been approved.');
        }
    }

    public function rejectKyc()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:5',
        ]);

        if ($this->selectedKyc) {
            $this->selectedKyc->update([
                'status' => 'rejected',
                'rejection_reason' => $this->rejectionReason,
            ]);
            // also update the user's KYC status
            $this->selectedKyc->user->update(['kyc_status' => 'rejected']);

            $this->showModal = false;
            $this->rejectionReason = '';
            session()->flash('message', 'KYC application has been rejected.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedKyc = null;
        $this->rejectionReason = '';
    }
}
