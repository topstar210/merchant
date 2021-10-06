<?php

namespace App\Http\Livewire\Utils;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Wallets extends Component
{
    public $wallets;
    public $lien;

    protected $listeners = ['refreshWallet'];

    public function mount($wallets)
    {
        $this->wallets = $wallets;
        $this->lien = user()->merchant->lien()->where('status', 'LOCKED')->first();
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
