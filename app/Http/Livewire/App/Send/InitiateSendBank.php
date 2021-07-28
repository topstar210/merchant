<?php

namespace App\Http\Livewire\App\Send;

use App\Http\Utils\Resource;
use App\Models\TempTransactions;
use App\Models\Wallet;
use App\Services\CyberpayService;
use App\Services\FlutterwaveService;
use App\Services\OrchardServices;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class InitiateSendBank extends Component
{
    public $payment_method;
    public $supported;
    public $wallet;
    public $recipient_currency;
    public $rates;
    public $amount;
    public $total = 0;
    public $charge = 0;
    public $commission = 0;
    public $charge_fixed = 0;
    public $charge_percentage = 0;

    public $banks;
    public $selectedBank;
    public $recipient_bank;
    public $eu_countries;

    public $tempAccount;
    public $selectedAccount;

    public $beneficiary;

    public $beneficiary_name;
    public $routing_number;
    public $swift_code;
    public $postal_code;
    public $street_number;
    public $street_name;
    public $city;
    public $recipient_country;
    public $beneficiary_address;


    public $account;

    public function mount(Wallet $wallet, $data)
    {
        $routes = $data['send_currency']->transfer_route;
        Log::info($routes);
        $this->wallet = $wallet;
        $this->amount = $data['amount'];
        $this->supported = count($routes) ? true : false;
        $this->payment_method = count($routes) ? $routes[0] : [];
        $this->rates = $data['rates'];
        $this->recipient_currency = $data['send_currency'];
        $this->eu_countries = Resource::supportedEUCountries();
    }

    protected $messages = [
        'account.required' => 'Enter recipient account number',
        'account.min' => 'Invalid account number',
        'recipient_bank.required' => 'Choose a bank',
    ];


    protected function rules()
    {
        return [
            'account' => ['required', 'min:9', 'max:20'],
            'recipient_bank' => ['required'],
            'beneficiary' => [Rule::requiredIf(!is_null($this->tempAccount))],
            'beneficiary_name' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['USD', 'EUR', 'GBP']))],
            'routing_number' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['USD', 'EUR', 'GBP']))],
            'swift_code' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['USD', 'EUR', 'GBP']))],
            'postal_code' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['EUR', 'GBP']))],
            'street_number' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['EUR', 'GBP']))],
            'street_name' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['EUR', 'GBP']))],
            'city' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['EUR', 'GBP']))],
            'recipient_country' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['EUR']))],
            'beneficiary_address' => [Rule::requiredIf(in_array($this->recipient_currency->code, ['USD', 'ZAR']))],
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
        $this->selectedAccount['account_name'] = $this->beneficiary;
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
                if (in_array($this->recipient_currency->code, ['GHS', 'KES', 'RWF', 'TZS', 'UGX', 'XAF', 'XOF', 'ZMW', 'ZAR'])) {
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
                if (empty($account_name)) {
                    $this->selectedAccount = $this->tempAccount;
                }
            } else {
                $this->tempAccount = null;
                throw ValidationException::withMessages(['account' => 'No account found']);
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
            $this->commission = (double)(($this->charge * user()->merchant->commission) / 100);
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

        $this->dispatchBrowserEvent('set_banks', array_reverse($this->banks));

    }

    public function continueSendBank()
    {
        $this->validate();
        $this->emit('processingBank');
        $this->calCharge();
        $reference = (string)rand(100000000000, 999999999999);

        TempTransactions::query()->create([
            "payment_method_id" => $this->payment_method->id,
            "transaction_type_id" => WITHDRAWALS,
            "reference" => $reference,
            "wallet_id" => $this->wallet->id,
            "user_id" => user()->id,
            "data" => array_merge($this->rates, [
                "service" => 'SB',
                "amount" => (double)$this->amount,
                "total" => (double)$this->total,
                "bank" => $this->selectedBank,
                "account" => $this->account,
                "account_name" => $this->selectedAccount['account_name'] ?? $this->beneficiary_name,
                "charge" => $this->charge,
                "charge_fixed" => $this->charge_fixed,
                "charge_percentage" => $this->charge_percentage,
                "commission" => $this->commission,
                "ip" => request()->ip(),
                "browser" => request()->userAgent(),
                "extra" => [
                    'routing_number' => $this->routing_number ?? null,
                    'swift_code' => $this->swift_code ?? null,
                    'postal_code' => $this->postal_code ?? null,
                    'street_number' => $this->street_number ?? null,
                    'street_name' => $this->street_name ?? null,
                    'city' => $this->city ?? null,
                    'recipient_bank' => $this->recipient_bank ?? null,
                    'recipient_country' => switchSendUGECountry($this->recipient_currency->code, $this->recipient_country),
                    'beneficiary_address' => $this->beneficiary_address ?? null,
                ]
            ])
        ]);

        request()->session()->forget('sendBank_' . $this->wallet->id,);

        $this->emit('finishBank');

        return redirect()->to('/app/send/' . $this->wallet->id . '/' . $reference);
    }

    public function render()
    {
        return view('livewire.app.send.initiate-send-bank');
    }
}
