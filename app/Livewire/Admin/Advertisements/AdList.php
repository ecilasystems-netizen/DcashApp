<?php

namespace App\Livewire\Admin\Advertisements;

use App\Models\Advertisement;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdList extends Component
{
    use WithFileUploads, WithPagination;

    public $title;
    public $link_url;
    public $image;
    public $editingId;
    public $showModal = false;
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'title' => 'required|string|max:255',
        'link_url' => 'nullable|url',
        'image' => 'required|image|max:2048'
    ];

    public function save()
    {
        $this->validate();

        $imagePath = $this->image->store('advertisements', 'public');

        Advertisement::create([
            'title' => $this->title,
            'link_url' => $this->link_url,
            'image_path' => $imagePath,
            'is_active' => true
        ]);

        $this->reset(['title', 'link_url', 'image', 'showModal']);
        session()->flash('message', 'Advertisement created successfully!');
    }

    public function toggleStatus($id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->update(['is_active' => !$ad->is_active]);
        session()->flash('message', 'Advertisement status updated!');
    }

    public function delete($id)
    {
        $ad = Advertisement::findOrFail($id);
        \Storage::disk('public')->delete($ad->image_path);
        $ad->delete();
        session()->flash('message', 'Advertisement deleted successfully!');
    }

    public function render()
    {
        return view('livewire.admin.advertisements.ad-list', [
            'ads' => Advertisement::query()
                ->when($this->search, fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
                ->orderBy('display_order')
                ->orderByDesc('created_at')
                ->paginate($this->perPage)
        ])->layout('layouts.admin.app');
    }
}
