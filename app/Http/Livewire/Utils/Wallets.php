<?php

namespace App\Http\Livewire\Utils;

use Livewire\Component;

class Wallets extends Component
{
    public $wallets;

    protected $listeners = ['refreshWallet'];

    public function mount($wallets)
    {
        $this->wallets = $wallets;
    }

    public function refreshWallet()
    {
        $this->wallets = user()->wallets;
    }

    public function render()
    {
        return view('livewire.utils.wallets');
    }
}
