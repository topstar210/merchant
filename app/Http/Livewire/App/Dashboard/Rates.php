<?php

namespace App\Http\Livewire\App\Dashboard;

use App\Models\Currency;
use App\Services\ExchangeService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Rates extends Component
{
    public $currencyExchange = [];

    public $wallet;

    public function mount()
    {
        $this->wallet = user()->wallet;
    }

    public function getCurrencyExchange()
    {
        $all = Currency::supported()->get();
        $this->currencyExchange = ExchangeService::currencyExchangeBulk($this->wallet->currency, 1, $all);
    }

    public function render()
    {
        return view('livewire.app.dashboard.rates');
    }
}
