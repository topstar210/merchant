<?php

namespace App\Http\Livewire\App\Common;

use App\Models\MerchantPayment;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Process extends Component
{
    public MerchantPayment $transaction;

    public function mount($transaction)
    {
        $this->transaction = $transaction;
    }

    public function checkTransaction()
    {
        $this->transaction->fresh();
        if ($this->transaction->status == 'Success') {
            $this->emitTo('utils.wallets', 'refreshWallet');
        }

    }

    public function render()
    {
        return view('livewire.app.common.process');
    }
}
