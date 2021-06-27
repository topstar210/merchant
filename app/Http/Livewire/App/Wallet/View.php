<?php

namespace App\Http\Livewire\App\Wallet;

use App\Models\Wallet;
use Livewire\Component;

class View extends Component
{
    public Wallet $wallet;

    public function mount(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function render()
    {
        return view('livewire.app.wallet.view');
    }
}
