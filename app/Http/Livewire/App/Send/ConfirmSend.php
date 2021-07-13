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

    public function handleSend()
    {
        $this->validate();

        if (in_array($this->temp->data['service'], ['SW', 'SA'])) {
            return (new SendController())->initializeSWSATransaction($this->temp);
        }

        if ($this->temp->data['service'] == "SB") {
            return (new SendController())->initializeSBTransaction($this->temp);
        }

        if ($this->temp->data['service'] == "CW") {
            return (new SendController())->initializeCWTransaction($this->temp);
        }
    }

    public function render()
    {
        return view('livewire.app.send.confirm-send');
    }
}
