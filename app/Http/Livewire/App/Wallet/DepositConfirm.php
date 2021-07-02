<?php

namespace App\Http\Livewire\App\Wallet;

use App\Models\TempTransactions;
use App\Models\Wallet;
use App\Services\OrchardServices;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DepositConfirm extends Component
{
    public Wallet $wallet;
    public TempTransactions $temp;

    public $orc_error;

    public function mount(Wallet $wallet, TempTransactions $temp)
    {
        $this->wallet = $wallet;
        $this->temp = $temp;

    }

    public function startOrchard()
    {
        $orc_option = $this->temp->data['converted'] ? 'CRD' : 'CRM';
        $init = OrchardServices::initiateCheckout($this->temp->data['exchange_amount'], $orc_option, $this->temp->reference);

        if ($init['status']) {
            return redirect($init['link']);
        } else {
            $this->orc_error = $init['message'];
        }


    }

    public function render()
    {
        return view('livewire.app.wallet.deposit-confirm');
    }
}
