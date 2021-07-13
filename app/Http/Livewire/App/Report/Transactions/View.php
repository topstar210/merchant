<?php

namespace App\Http\Livewire\App\Report\Transactions;

use App\Http\Controllers\SendController;
use App\Http\Utils\Resource;
use App\Models\MerchantPayment;
use Livewire\Component;

class View extends Component
{
    public MerchantPayment $transaction;

    public function mount(MerchantPayment $transaction)
    {
        $this->transaction = $transaction;
    }

    public function requery()
    {
        $query = (new SendController())->requerySend($this->transaction);

        $this->transaction->refresh();

        request()->session()->flash(
            'error',
            $query['status'] == 'success'
        );
        request()->session()->flash(
            'error_message',
            $query['message']
        );

        $this->emit('showAlert');
    }

    public function render()
    {
        return view('livewire.app.report.transactions.view');
    }
}
