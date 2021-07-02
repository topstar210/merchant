<?php

namespace App\Http\Livewire\App\Wallet;

use App\Models\TempTransactions;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DepositConfirm extends Component
{
    public Wallet $wallet;
    public TempTransactions $temp;

    public function mount(Wallet $wallet, TempTransactions $temp)
    {
        $this->wallet = $wallet;
        $this->temp = $temp->load('route');
    }

    public function render()
    {
        return view('livewire.app.wallet.deposit-confirm');
    }
}
