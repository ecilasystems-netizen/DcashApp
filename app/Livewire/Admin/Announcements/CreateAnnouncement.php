<?php

namespace App\Livewire\Admin\Announcements;

use App\Models\Announcement;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateAnnouncement extends Component
{
    use WithFileUploads;

    // Form properties
    public $title;
    public $contentType = 'image';
    public $image;
    public $videoUrl;
    public $sliderImages = [];
    public $ctaText;
    public $ctaLink;
    public $isActive = true;
    public $status = 'published';

    // Validation rules

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        // Process content based on type
        $content = [];

        if ($this->contentType === 'image') {
            $imagePath = $this->image->store('announcements', 'public');
            $content['path'] = $imagePath;
        } elseif ($this->contentType === 'video') {
            $content['url'] = $this->videoUrl;
        } elseif ($this->contentType === 'slider') {
            $paths = [];
            foreach ($this->sliderImages as $image) {
                $paths[] = $image->store('announcements/sliders', 'public');
            }
            $content['paths'] = $paths;
        }

        // Create the announcement
        Announcement::create([
            'title' => $this->title,
            'content_type' => $this->contentType,
            'content' => $content,
            'cta_text' => $this->ctaText,
            'cta_link' => $this->ctaLink,
            'is_active' => $this->isActive,
            'status' => $this->status,
            'starts_at' => Carbon::now(),
        ]);

        session()->flash('success', 'Announcement created successfully.');

        // Redirect to the announcements list
        return $this->redirect(route('admin.announcements'), navigate: true);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.announcements'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.announcements.create-announcement')
            ->layout('layouts.admin.app')
            ->title('Create Announcement');
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'contentType' => 'required|in:image,video,slider',
            'image' => $this->contentType === 'image' ? 'required|image|max:5120' : 'nullable',
            'videoUrl' => $this->contentType === 'video' ? 'required|url' : 'nullable',
            'sliderImages.*' => $this->contentType === 'slider' ? 'required|image|max:5120' : 'nullable',
            'ctaText' => 'nullable|string|max:50',
            'ctaLink' => 'nullable|url|max:255',
            'isActive' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ];
    }
}
