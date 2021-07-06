<?php

namespace App\Http\Livewire\App\Send\Options;

use App\Models\Currency;
use App\Models\Wallet;
use App\Services\ExchangeService;
use Livewire\Component;

class SendBank extends Component
{
    public Wallet $wallet;
    public bool $locked = false;
    public $currencies;
    public $currency_ids;
    public $selectedCurrency;

    public $rates;

    public $amount = 0;
    public $send_currency;
    public $max;

    protected $listeners = ['processingAccount' => 'lockAction', 'processingWallet' => 'lockAction', 'finishAccount' => 'unlockAction', 'finishBankWallet' => 'unlockAction'];

    public function mount($wallet)
    {
        $this->wallet = $wallet;
        $this->currencies = Currency::supported()->get();
        $this->currency_ids = $this->currencies->implode('id', ',');
        $this->max = $this->wallet->balance - ($this->wallet->balance * (3 / 100));
    }

    protected $messages = [
        'send_currency.required' => 'Select a currency',
        'send_currency.in' => 'Currency not supported',
        'amount.min' => 'Amount is below allowed minimum',
        'amount.max' => 'Amount exceeds your available balance',
    ];

    protected function rules()
    {
        return [
            'send_currency' => ['required', 'in:' . $this->currency_ids],
            'amount' => ['required', 'numeric', 'min:10', 'max:' . $this->max],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setSendCurrency($currency_id)
    {
        $this->send_currency = $currency_id;
        if (!empty($currency_id)) {
            $this->selectedCurrency = $this->currencies->where('id', $currency_id)->first();
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
        if (!is_null($this->selectedCurrency) && $this->amount > 0) {
            $this->rates = ExchangeService::currencyExchange($this->wallet->currency, $this->amount, $this->selectedCurrency->code);
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

    public function continueSendBank()
    {
        $this->validate();
        $this->emit('processingBank');

        request()->session()->put(
            'sendBank_' . $this->wallet->id,
            [
                "amount" => $this->amount,
                "rates" => $this->rates,
                "send_currency" => $this->send_currency
            ]
        );

        $this->emit('finishBank');
        return redirect()->to('/app/send/bank/' . $this->wallet->id);
    }

    public function render()
    {
        return view('livewire.app.send.options.send-bank');
    }
}
