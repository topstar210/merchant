<?php

namespace App\Http\Livewire\App\Send\Options;

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SendWallet extends Component
{
    public Wallet $wallet;
    public bool $locked = false;
    public $other_wallets;

    public $amount;
    public $recipient_wallet;

    protected $listeners = ['processingAccount' => 'lockAction', 'processingBank' => 'lockAction', 'finishAccount' => 'unlockAction', 'finishBank' => 'unlockAction'];

    public function mount($wallet)
    {
        $this->wallet = $wallet;
        $this->other_wallets = user()->wallets->where('id', '!=', $wallet->id);

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
        return view('livewire.app.send.options.send-wallet');
    }
}
