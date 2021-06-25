<?php

namespace App\Http\Livewire\App\Agents;

use App\Models\User;
use Livewire\Component;

class View extends Component
{
    public $agent;

    public function mount(User $user)
    {
        $this->agent = $user;
    }

    public function deleteAgent()
    {
        sleep(5);



    }

    public function render()
    {
        return view('livewire.app.agents.view');
    }
}
