<?php

namespace App\Http\Livewire\App\Send;

use App\Models\TempTransactions;
use App\Models\Wallet;
use App\Services\CyberpayService;
use App\Services\FlutterwaveService;
use App\Services\OrchardServices;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class InitiateSendBank extends Component
{
    public $payment_method;
    public $wallet;
    public $recipient_currency;
    public $rates;
    public $amount;
    public $total = 0;
    public $charge = 0;
    private $charge_fixed = 0;
    private $charge_percentage = 0;

    public $banks;
    public $selectedBank;
    public $recipient_bank;

    public $tempAccount;
    public $selectedAccount;

    public $beneficiary;

    public $account;
    public $errorAccount = false;

    public function mount(Wallet $wallet, $data)
    {
        Log::info($data['send_currency']->transfer_route);

        $this->wallet = $wallet;
        $this->amount = $data['amount'];
        $this->payment_method = $data['send_currency']->transfer_route[0];
        $this->rates = $data['rates'];
        $this->recipient_currency = $data['send_currency'];

    }

    protected $messages = [
        'account.required' => 'Enter recipient account number',
        'account.min' => 'Invalid account number',
        'recipient_bank.required' => 'Choose a bank',
    ];


    protected function rules()
    {
        return [
            'account' => ['required', 'min:10', 'max:20'],
            'recipient_bank' => ['required'],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function confirmAccount($confirm)
    {
        if ($confirm) {
            $this->selectedAccount = $this->tempAccount;
            $this->tempAccount = null;
        } else {
            $this->selectedAccount = null;
            $this->tempAccount = null;
        }
    }

    public function setSelectedBank($bank_id)
    {
        $this->recipient_bank = $bank_id;
        if (!empty($bank_id)) {
            $this->selectedBank = array_values(Arr::where($this->banks, function ($val, $key) use ($bank_id) {
                return ($val['Id'] ?? $val['id']) == $bank_id;
            }))[0];
            $this->handleNameEnquiry();
        }
    }

    public function updatedBeneficiary()
    {
        $this->tempAccount['account_name'] = $this->beneficiary;
        $this->selectedAccount = $this->tempAccount;
    }

    public function updatedAccount()
    {
        $this->handleNameEnquiry();
    }

    public function handleNameEnquiry()
    {
        if (!is_null($this->selectedBank) && !is_null($this->account)) {
            $account_name = null;
            if ($this->payment_method->payment_method->name === 'CyberPay Payout') {
                $account_name = CyberpayService::nameEnquiry($this->account, $this->selectedBank);
            }
            if ($this->payment_method->payment_method->name === 'Flutterwave Payout') {
                if (in_array($this->recipient_currency->code, ['KES', 'RWF', 'TZS', 'UGX', 'XAF', 'XOF', 'ZMW', 'ZAR'])) {
                    $account_name = '';
                } else {
                    $account_name = FlutterwaveService::nameEnquiry($this->account, $this->selectedBank);
                }
            }
            if ($this->payment_method->payment_method->name === 'Orchard') {
                $account_name = OrchardServices::nameEnquiry($this->account, $this->selectedBank);
            }

            if (!is_null($account_name)) {
                $this->tempAccount = [
                    "account" => $this->account,
                    "account_name" => $account_name
                ];
                $this->errorAccount = false;
            } else {
                $this->tempAccount = null;
                $this->errorAccount = true;
            }
        }
    }

    private function calCharge()
    {
        if (!empty($this->payment_method) && $this->amount > 0) {
            $range = explode('-', $this->payment_method->fee_limit_transfer->fixed_charge_range);
            if (count($range) > 2) {
                if ($this->amount >= $range[0] && $this->amount <= $range[1]) {
                    $this->charge_fixed = doubleval($this->payment_method->fee_limit_transfer->charge_fixed);
                    $this->charge_percentage = 0;
                }
            } else {
                $this->charge_percentage = doubleval($this->amount * ($this->payment_method->fee_limit_transfer->charge_percentage / 100));
                $this->charge_fixed = 0;
            }
            $this->charge = $this->charge_percentage + $this->charge_fixed;
            $this->total = $this->amount + $this->charge;
        }
    }

    public function retrieveBanks()
    {
        if ($this->payment_method->payment_method->name === 'CyberPay Payout') {
            $this->banks = CyberpayService::getBanks();
        } elseif ($this->payment_method->payment_method->name === 'Flutterwave Payout') {
            $this->banks = FlutterwaveService::getBanks($this->recipient_currency->code);
        } elseif ($this->payment_method->payment_method->name === 'Orchard') {
            $this->banks = OrchardServices::getBanks();
        } else {
            $this->banks = [];
        }

        $this->dispatchBrowserEvent('set_banks', $this->banks);

    }

    public function continueSendBank()
    {
        $this->validate();
        $this->emit('processingBank');
        $this->calCharge();
        $reference = (string)rand(100000000000, 999999999999);

//        TempTransactions::query()->create([
//            "payment_method_id" => 1,
//            "transaction_type_id" => WITHDRAWALS,
//            "reference" => $reference,
//            "wallet_id" => $this->wallet->id,
//            "user_id" => user()->id,
//            "data" => array_merge($this->rates, [
//                "recipient_wallet_id" => (int)$this->recipient_account_wallet,
//                "service" => 'SA',
//                "amount" => (double)$this->amount,
//                "total" => (double)$this->amount,
//                "account" => $this->recipient_irt_account,
//                "account_name" => $this->selectedAccount->full_name,
//                "charge" => 0,
//                "charge_fixed" => 0,
//                "charge_percentage" => 0
//            ])
//        ]);

        $this->emit('finishBank');

        return redirect()->to('/app/send/' . $this->wallet->id . '/' . $reference);
    }

    public function render()
    {
        return view('livewire.app.send.initiate-send-bank');
    }
}
