<?php

namespace App\Livewire\Admin\Announcements;

use App\Models\Announcement;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AnnouncementList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function toggleActive($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        session()->flash('success', 'Announcement status updated.');
    }

    #[On('delete')]
    public function delete($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        session()->flash('success', 'Announcement deleted successfully.');
    }

    public function render()
    {
        $announcements = Announcement::query()
            ->when($this->search, function($query) {
                return $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function($query) {
                return $query->where('status', $this->status);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.announcements.announcement-list', [
            'announcements' => $announcements
        ])->layout('layouts.admin.app')->title('Announcements');
    }
}
