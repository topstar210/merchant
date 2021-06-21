<?php

namespace App\Http\Livewire\App\Agents;

use App\Models\User;
use Livewire\Component;

class All extends Component
{
//    public $agents;

    public function mounted()
    {

    }

    public function getAgentsProperty()
    {
        return User::with('userDetail')->where('merchant_id', user()->merchant_id)->whereNotIn('id', [user()->id])->latest()->paginate(10);

    }


    public function render()
    {
        return view('livewire.app.agents.all');
    }
}
