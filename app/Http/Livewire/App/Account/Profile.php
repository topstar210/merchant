<?php

namespace App\Http\Livewire\App\Account;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    public $account;

    public function mount()
    {
        $this->account = user();
    }

    public function render()
    {
        return view('livewire.app.account.profile');
    }
}
