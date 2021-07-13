<?php

namespace App\Http\Livewire\App\Dashboard;

use Livewire\Component;

class Notification extends Component
{
    public $notifications = [];

    public function render()
    {
        return view('livewire.app.dashboard.notification');
    }
}
