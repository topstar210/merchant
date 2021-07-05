<?php

namespace App\Http\Livewire\App\Send\Options;

use App\Models\TempTransactions;
use App\Models\Wallet;
use App\Services\ExchangeService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SendWallet extends Component
{
    public Wallet $wallet;
    public bool $locked = false;
    public $other_wallets;
    public $other_wallet_ids;
    public $selectedWallet;

    public $rates;

    public $amount = 0;
    public $recipient_wallet;

    protected $listeners = ['processingAccount' => 'lockAction', 'processingBank' => 'lockAction', 'finishAccount' => 'unlockAction', 'finishBank' => 'unlockAction'];

    public function mount($wallet)
    {
        $this->wallet = $wallet;
        $this->other_wallets = user()->wallets->where('id', '!=', $wallet->id);
        $this->other_wallet_ids = $this->other_wallets->implode('id', ',');
    }

    protected $messages = [
        'recipient_wallet.required' => 'Select a Wallet',
        'recipient_wallet.in' => 'Select a valid wallet',
        'amount.min' => 'Amount is below allowed minimum',
        'amount.max' => 'Amount exceeds your available balance',
    ];

    protected function rules()
    {
        return [
            'recipient_wallet' => ['required', 'in:' . $this->other_wallet_ids],
            'amount' => ['required', 'numeric', 'min:10', 'max:' . $this->wallet->balance],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setRecipientWallet($wallet_id)
    {
        $this->recipient_wallet = $wallet_id;
        if (!empty($wallet_id)) {
            $this->selectedWallet = $this->other_wallets->where('id', $wallet_id)->first();
            $this->calRate();
        } else {
            $this->rates = null;
        }
    }

    public function updatedAmount()
    {
        $this->calRate();
    }

    public function calRate()
    {
        if (!is_null($this->selectedWallet) && $this->amount > 0) {
            $this->rates = ExchangeService::currencyExchange($this->wallet->currency, $this->amount, $this->selectedWallet->currency->code);
        }
    }

    public function lockAction()
    {
        $this->locked = true;
    }

    public function unlockAction()
    {
        $this->locked = false;
    }

    public function continueSendWallet()
    {
        $this->validate();
        $this->emit('processingWallet');
        $reference = (string)rand(100000000000, 999999999999);

        TempTransactions::query()->create([
            "payment_method_id" => 1,
            "transaction_type_id" => WITHDRAWALS,
            "reference" => $reference,
            "wallet_id" => $this->wallet->id,
            "user_id" => user()->id,
            "data" => array_merge($this->rates, [
                "recipient_wallet_id" => (int)$this->recipient_wallet,
                "service" => 'SW',
                "amount" => (double)$this->amount,
                "total" => (double)$this->amount,
                "charge" => 0,
                "charge_fixed" => 0,
                "charge_percentage" => 0
            ])
        ]);

        $this->emit('finishWallet');

        return redirect()->to('/app/send/' . $this->wallet->id . '/' . $reference);
    }

    public function render()
    {
        return view('livewire.app.send.options.send-wallet');
    }
}
