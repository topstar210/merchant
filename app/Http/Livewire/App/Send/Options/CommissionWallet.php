<?php

namespace App\Http\Livewire\App\Send\Options;

use App\Models\TempTransactions;
use App\Models\Wallet;
use Livewire\Component;

class CommissionWallet extends Component
{
    public Wallet $wallet;
    public bool $locked = false;

    public $amount;

    protected $listeners = ['processingAccount' => 'lockAction', 'processingBank' => 'lockAction', 'processingWallet' => 'lockAction', 'finishAccount' => 'unlockAction', 'finishBank' => 'unlockAction', 'finishWallet' => 'unlockAction'];


    public function mount(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    protected $messages = [
        'amount.min' => 'Amount is below allowed minimum',
        'amount.max' => 'Amount exceeds your available commission',
    ];

    protected function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:' . config('env.min_send'), 'max:' . $this->wallet->commission],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function lockAction()
    {
        $this->locked = true;
    }

    public function unlockAction()
    {
        $this->locked = false;
    }

    public function continueCommissionWallet()
    {
        $this->validate();
        $this->emit('processingCommission');
        $reference = (string)rand(100000000000, 999999999999);

        $rates = [
            'exchange_rate' => 1,
            'from_currency' => $this->wallet->currency->code,
            'to_currency' => $this->wallet->currency->code,
            'exchange_amount' => $this->amount,
            'converted' => false
        ];

        TempTransactions::query()->create([
            "payment_method_id" => 1,
            "transaction_type_id" => DEPOSITS,
            "reference" => $reference,
            "wallet_id" => $this->wallet->id,
            "user_id" => user()->id,
            "data" => array_merge($rates, [
                "service" => 'CW',
                "amount" => (double)$this->amount,
                "total" => (double)$this->amount,
                "charge" => 0,
                "charge_fixed" => 0,
                "charge_percentage" => 0,
                "ip"=> request()->ip(),
                "browser"=> request()->userAgent(),
            ])
        ]);

        $this->emit('finishCommission');

        return redirect()->to('/app/send/' . $this->wallet->id . '/' . $reference);
    }

    public function render()
    {
        return view('livewire.app.send.options.commission-wallet');
    }
}
