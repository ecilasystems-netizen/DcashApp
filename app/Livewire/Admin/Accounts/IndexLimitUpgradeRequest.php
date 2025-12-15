<?php

namespace App\Livewire\Admin\Accounts;

use App\Models\AccountLimitUpgradeRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IndexLimitUpgradeRequest extends Component
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
        $this->statusFilter = Session::get('limit_request_status_filter', $this->statusFilter);
    }

    public function updatedStatusFilter($value)
    {
        Session::put('limit_request_status_filter', $value);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFilter']);
        $this->statusFilter = '';
        Session::forget('limit_request_status_filter');
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

    public function approveRequest($requestId)
    {
        $request = AccountLimitUpgradeRequest::findOrFail($requestId);

        // Update the request status
        $request->update([
            'status' => 'approved',
        ]);

        // Update user's account tier to tier 3 (higher limits)
        $request->user->update([
            'account_tier_id' => 3
        ]);

        session()->flash('message', 'Limit upgrade request approved successfully.');
    }

    public function rejectRequest($requestId, $reason = null)
    {
        $request = AccountLimitUpgradeRequest::findOrFail($requestId);
        $request->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        session()->flash('message', 'Limit upgrade request rejected successfully.');
    }

    public function deleteRequest($requestId)
    {
        AccountLimitUpgradeRequest::findOrFail($requestId)->delete();
        session()->flash('message', 'Request deleted successfully.');
    }

    public function exportCsv()
    {
        $requests = $this->getRequestsQuery()->get();

        $response = new StreamedResponse(function () use ($requests) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Request ID',
                'User Name',
                'Email',
                'Phone',
                'Occupation',
                'Source of Income',
                'Status',
                'Date Requested'
            ]);

            foreach ($requests as $request) {
                fputcsv($handle, [
                    $request->id,
                    $request->user->fname.' '.$request->user->lname,
                    $request->user->email,
                    $request->user->phone ?? 'N/A',
                    $request->occupation,
                    $request->source_of_income,
                    $request->status,
                    $request->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="limit_requests_'.date('Y-m-d').'.csv"');

        return $response;
    }

    private function getRequestsQuery(): Builder
    {
        return AccountLimitUpgradeRequest::with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('occupation', 'like', '%'.$this->search.'%')
                        ->orWhere('source_of_income', 'like', '%'.$this->search.'%')
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

    public function getRequestsProperty()
    {
        return $this->getRequestsQuery()->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'total' => AccountLimitUpgradeRequest::count(),
            'submitted' => AccountLimitUpgradeRequest::where('status', 'submitted')->count(),
            'under_review' => AccountLimitUpgradeRequest::where('status', 'under_review')->count(),
            'approved' => AccountLimitUpgradeRequest::where('status', 'approved')->count(),
            'rejected' => AccountLimitUpgradeRequest::where('status', 'rejected')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.accounts.index-limit-upgrade-request')->layout('layouts.admin.app', [
            'title' => 'Account Limit Requests',
            'description' => 'Manage account limit upgrade requests',
        ]);
    }
}
