<?php

namespace App\Http\Livewire\App\Dashboard;

use App\Models\Currency;
use App\Services\ExchangeService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Converter extends Component
{
    public $currencies;
    public $rates;

    public $amount;
    public $from_currency;
    public $to_currency;

    public $selectedToCurrency;
    public $selectedFromCurrency;
    public $selectedWallet;
    public $max;

    public function mount()
    {
        $this->currencies = Currency::supported()->get();
    }

    protected $messages = [
        'amount.min' => 'Amount is below allowed minimum',
        'amount.max' => 'Amount exceeds your available balance',
    ];

    public function setFromWallet($wallet_id)
    {
        if (!empty($wallet_id)) {

            $this->selectedWallet = user()->wallets()->where('id', $wallet_id)->first();
            $this->selectedFromCurrency = $this->selectedWallet->currency;
            $this->from_currency = $this->selectedFromCurrency->id;
            $this->max = $this->selectedWallet->balance - ($this->selectedWallet->balance * (3 / 100));
            $this->calRate();
        } else {
            $this->rates = null;
        }
    }

    public function setToCurrency($currency_id)
    {
        $this->to_currency = $currency_id;
        if (!empty($currency_id)) {
            $this->selectedToCurrency = $this->currencies->where('id', $currency_id)->first();
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
        if (!is_null($this->selectedToCurrency) && !is_null($this->selectedFromCurrency) && $this->amount > 0) {
            $this->rates = ExchangeService::currencyExchange($this->selectedFromCurrency, $this->amount, $this->selectedToCurrency->code);
        } else {
            $this->rates = null;
        }
    }

    public function sendNow()
    {
        $validatedData = $this->validate([
            'amount' => ['required', 'numeric', 'min:' . config('env.min_send'), 'max:' . $this->max],
        ]);

        request()->session()->put(
            'sendBank_' . $this->selectedWallet->id,
            [
                "amount" => $this->amount,
                "rates" => $this->rates,
                "send_currency" => $this->to_currency
            ]
        );

        return redirect()->to('/app/send/bank/' . $this->selectedWallet->id);
    }

    public function render()
    {
        return view('livewire.app.dashboard.converter');
    }
}
