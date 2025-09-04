<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class SuccessPage extends Component
{
    public ?string $title = null;
    public ?string $message = null;
    public ?string $redirectTo = null;
    public ?int $redirectAfter = null;

    protected $queryString = [
        'title' => ['except' => ''],
        'message' => ['except' => ''],
        'redirectTo' => ['except' => ''],
        'redirectAfter' => ['except' => 0],
    ];

    public function render()
    {
        return view('livewire.app.auth.success-page')
            ->layout('layouts.auth.app');
    }
}
