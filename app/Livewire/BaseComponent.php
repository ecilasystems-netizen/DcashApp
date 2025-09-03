<?php

namespace App\Livewire;

use Livewire\Component;

class BaseComponent extends Component
{
    public function updated($propertyName = null)
    {
        // Use direct JavaScript call instead of dispatch
        $this->js('
            if (window.reinitLucideIcons) {
                window.reinitLucideIcons();
            }
        ');
    }
}
