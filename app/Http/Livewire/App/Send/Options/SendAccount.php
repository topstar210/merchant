<?php

namespace App\Http\Livewire\App\Send\Options;

use App\Models\TempTransactions;
use App\Models\User;
use App\Models\Wallet;
use App\Services\ExchangeService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SendAccount extends Component
{
    public Wallet $wallet;
    public bool $locked = false;

    public $selectedWallet;
    public $selectedAccount;
    public $selectedAccount_wallet_ids;

    public $rates;

    public $amount = 0;

    public $tempAccount;

    public $recipient_irt_account;
    public $recipient_account_wallet;

    protected $listeners = ['processingWallet' => 'lockAction', 'processingBank' => 'lockAction', 'finishWallet' => 'unlockAction', 'finishBank' => 'unlockAction'];

    public function mount($wallet)
    {
        $this->wallet = $wallet;
    }

    protected $messages = [
        'recipient_irt_account.required' => 'Enter recipient IRT account',
        'recipient_irt_account.min' => 'Invalid IRT account',
        'recipient_irt_account.not_in' => 'You can not send to your own account.',
        'recipient_irt_account.exists' => 'Account does not exist',
        'recipient_account_wallet.required' => 'Choose recipient wallet',
        'recipient_account_wallet.in' => 'Select a valid wallet',
        'amount.min' => 'Amount is below allowed minimum',
        'amount.max' => 'Amount exceeds your available balance',
    ];


    protected function rules()
    {
        return [
            'recipient_irt_account' => ['required', 'min:11', 'not_in:' . user()->account_number, 'exists:users,account_number'],
            'recipient_account_wallet' => ['required', 'in:' . $this->selectedAccount_wallet_ids],
            'amount' => ['required', 'numeric', 'min:10', 'max:' . $this->wallet->balance],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedRecipientIrtAccount()
    {
        $this->tempAccount = User::account($this->recipient_irt_account)->first();
        $this->selectedAccount = null;
        $this->rates = null;
    }

    public function confirmAccount($confirm)
    {
        if ($confirm) {
            $this->selectedAccount = $this->tempAccount;
            $this->selectedAccount_wallet_ids = $this->selectedAccount->wallets->implode('id', ',');
            $this->tempAccount = null;
            $this->rates = null;
        } else {
            $this->selectedAccount = null;
            $this->selectedAccount_wallet_ids = null;
            $this->tempAccount = null;
            $this->rates = null;
        }
    }

    public function setRecipientAccountWallet($wallet_id)
    {
        $this->recipient_account_wallet = $wallet_id;
        if (!empty($wallet_id)) {
            $this->selectedWallet = $this->selectedAccount->wallets->where('id', $wallet_id)->first();
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

    public function continueSendAccount()
    {
        $this->validate();
        $this->emit('processingAccount');
        $reference = (string)rand(100000000000, 999999999999);

        TempTransactions::query()->create([
            "payment_method_id" => 1,
            "transaction_type_id" => WITHDRAWALS,
            "reference" => $reference,
            "wallet_id" => $this->wallet->id,
            "user_id" => user()->id,
            "data" => array_merge($this->rates, [
                "recipient_wallet_id" => (int)$this->recipient_account_wallet,
                "service" => 'SA',
                "amount" => (double)$this->amount,
                "total" => (double)$this->amount,
                "account" => $this->recipient_irt_account,
                "account_name" => $this->selectedAccount->full_name,
                "charge" => 0,
                "charge_fixed" => 0,
                "charge_percentage" => 0
            ])
        ]);

        $this->emit('finishAccount');

        return redirect()->to('/app/send/' . $this->wallet->id . '/' . $reference);
    }

    public function render()
    {
        return view('livewire.app.send.options.send-account');
    }
}
