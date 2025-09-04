<?php

namespace App\Livewire\App\Auth;

use Livewire\Component;

class CheckEmail extends Component
{
    public function render()
    {
        return view('livewire.app.auth.check-email')->layout('layouts.auth.app', [
            'title' => 'Check Email',
        ]);
    }
}
