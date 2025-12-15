<?php

namespace App\Livewire\Admin\Rewards;

use App\Models\RedemptionRequest;
use Livewire\Component;
use Livewire\WithPagination;

class UserRedeemBonus extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $currencyFilter = '';
    public $dateFilter = '';
    public $search = '';

    public function markCompleted($redemptionId)
    {
        $redemption = RedemptionRequest::findOrFail($redemptionId);

        $redemption->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);

        session()->flash('message', "Redemption request #{$redemption->reference} marked as completed.");
    }

    public function markRejected($redemptionId, $reason = 'Rejected by admin')
    {
        $redemption = RedemptionRequest::findOrFail($redemptionId);

        $redemption->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'notes' => $reason
        ]);

        session()->flash('message', "Redemption request #{$redemption->reference} has been rejected.");
    }

    public function resetFilters()
    {
        $this->reset(['statusFilter', 'currencyFilter', 'dateFilter', 'search']);
    }

    public function getRedemptionRequestsProperty()
    {
        return RedemptionRequest::with('user')
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->currencyFilter, fn($query) => $query->where('currency', $this->currencyFilter))
            ->when($this->dateFilter, fn($query) => $query->whereDate('created_at', $this->dateFilter))
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
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function getStatsProperty()
    {
        return [
            'total' => RedemptionRequest::count(),
            'pending' => RedemptionRequest::where('status', 'pending')->count(),
            'completed' => RedemptionRequest::where('status', 'completed')->count(),
            'rejected' => RedemptionRequest::where('status', 'rejected')->count(),
            'total_amount' => RedemptionRequest::where('status', 'completed')->sum('amount'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.rewards.redeem-bonus')->layout('layouts.admin.app')->title('Redemption Requests');
    }
}
