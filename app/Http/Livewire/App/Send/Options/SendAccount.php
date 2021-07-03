<?php

namespace App\Http\Livewire\App\Send\Options;

use App\Models\Wallet;
use Livewire\Component;

class SendAccount extends Component
{
    public Wallet $wallet;
    public bool $locked = false;

    public $amount;

    protected $listeners = ['processingWallet' => 'lockAction', 'processingBank' => 'lockAction', 'finishWallet' => 'unlockAction', 'finishBank' => 'unlockAction'];

    public function mount($wallet)
    {
        $this->wallet = $wallet;
    }

    public function lockAction()
    {
        $this->locked = true;
    }

    public function unlockAction()
    {
        $this->locked = false;
    }

    public function render()
    {
        return view('livewire.app.send.options.send-account');
    }
}
