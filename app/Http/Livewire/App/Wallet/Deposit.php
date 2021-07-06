<?php

namespace App\Http\Livewire\App\Wallet;

use App\Models\TempTransactions;
use App\Models\Wallet;
use App\Services\ExchangeService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Deposit extends Component
{
    public Wallet $wallet;
    public $routes;
    public $selectedRoute;

    public $route;
    public $total = 0;
    public $charge = 0;
    private $charge_fixed = 0;
    private $charge_percentage = 0;
    public $amount = 0;

    protected $messages = [
        'route.required' => 'Select a Payment Option',
        'amount.min' => 'Minimum amount is :min',
        'amount.max' => 'Maximum amount is :max',
    ];

    public function mount(Wallet $wallet)
    {
        $this->wallet = $wallet;
        $this->routes = $wallet->currency->deposit_route;
    }

    protected function rules()
    {
        return [
            'route' => 'required',
            'amount' => ['required', 'numeric', 'min:' . ($this->selectedRoute->fee_limit_deposit->min_limit ?? 1), 'max:' . ($this->selectedRoute->fee_limit_deposit->max_limit ?? 100000)],
        ];
    }

    public function setRoute($route)
    {
        if (!empty($route)) {
            $this->route = $route;
            $this->selectedRoute = $this->routes->where('id', $route)->first();
            $this->calTotal();
        }
    }

    public function updatedAmount()
    {
        $this->calTotal();
    }

    private function calTotal()
    {
        if (!is_null($this->selectedRoute) && $this->amount > 0) {
            $range = explode('-', $this->selectedRoute->fee_limit_deposit->fixed_charge_range);
            if (count($range) > 2) {
                if ($this->amount >= $range[0] && $this->amount <= $range[1]) {
                    $this->charge_fixed = doubleval($this->selectedRoute->fee_limit_deposit->charge_fixed);
                    $this->charge_percentage = 0;
                }
            } else {
                $this->charge_percentage = doubleval($this->amount * ($this->selectedRoute->fee_limit_deposit->charge_percentage / 100));
                $this->charge_fixed = 0;
            }
            $this->charge = $this->charge_percentage + $this->charge_fixed;
            $this->total = $this->amount + $this->charge;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function initiateDeposit()
    {
        $this->validate();

        $rates = [
            'exchange_rate' => 1,
            'from_currency' => $this->wallet->currency->code,
            'to_currency' => $this->wallet->currency->code,
            'exchange_amount' => $this->total,
            'converted' => false
        ];

        if ($this->selectedRoute->payment_method->name == 'Orchard' && $this->wallet->currency->code != 'GHS') {
            $rates = ExchangeService::currencyExchange($this->wallet->currency, $this->total, 'GHS');
        } else {
            if (!in_array($this->wallet->currency->code, ['GBP', 'NGN', 'EUR', 'GHS', 'USD'])) {
                $rates = ExchangeService::currencyExchange($this->wallet->currency, $this->total);
            }
        }


        $temp = [
            "payment_method_id" => $this->route,
            "transaction_type_id" => DEPOSITS,
            "reference" => (string)rand(100000000000, 999999999999),
            "wallet_id" => $this->wallet->id,
            "user_id" => user()->id,
            "data" => array_merge($rates, [
                "amount" => (double)$this->amount,
                "total" => $this->total,
                "charge" => $this->charge,
                "charge_fixed" => $this->charge_fixed,
                "charge_percentage" => $this->charge_percentage
            ])
        ];

        TempTransactions::query()->create($temp);

        return redirect()->to('/app/wallet/' . $this->wallet->id . '/deposit/' . $temp['reference']);

    }

    public function render()
    {
        return view('livewire.app.wallet.deposit');
    }
}
