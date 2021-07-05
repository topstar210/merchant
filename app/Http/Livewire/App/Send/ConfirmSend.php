<?php

namespace App\Http\Livewire\App\Send;

use App\Http\Controllers\SendController;
use App\Models\TempTransactions;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ConfirmSend extends Component
{
    public Wallet $wallet;
    public TempTransactions $temp;

    public $pin;

    public function mount(Wallet $wallet, TempTransactions $temp)
    {
        $this->wallet = $wallet;
        $this->temp = $temp;
    }

    protected $messages = [
        'pin.required' => 'Transaction Pin is required',
        'pin.in' => 'Incorrect Transaction Pin',
    ];

    protected function rules()
    {
        return [
            'pin' => ['required', 'numeric', 'in:' . user()->pin],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function handleSWSA()
    {
        $this->validate();

        return (new SendController())->initializeSWSATransaction($this->temp);
    }

    public function render()
    {
        return view('livewire.app.send.confirm-send');
    }
}
