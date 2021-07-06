<?php


namespace App\Services\Deposit;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\MerchantPayment;
use App\Services\FlutterwaveService;
use App\Services\PayswitchService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DepositService
{
    public static function handleDeposit(MerchantPayment $trans)
    {
        $final = null;
        if ($trans->payment_method->name == 'Flutterwave') {
            $final = FlutterwaveService::checkStatus($trans);
        }

        if ($trans->payment_method->name == 'Payswitch') {
            $final = PayswitchService::checkStatus($trans);
        }

        if (!is_null($final)) {
            self::completeDeposit($trans, $final);
        }

    }

    public static function completeDeposit(MerchantPayment $trans, $final)
    {
        $transaction = Resource::saveDepositTrans($trans, $final);

        $trans->transaction()->associate($transaction);
        $trans->status = $transaction->status;

        $data = $trans->response;
        unset($data['response']);
        $data['response'] = $final['response'];

        $trans->response = $data;

        if ($trans->status == "Success") {
            $balance = (new WalletController())->creditWallet($trans->wallet, $trans->amount);
            $trans->balance_after = $balance;
            $transaction->available_amount = $balance;
        }

        $trans->save();
        $transaction->save();

        try {
            Mail::to($trans->user->email)->queue(new TransactionReceipt($trans));
        } catch (\Exception $e) {
            Log::error('Exception Error sending Deposit Receipt Email', format_exception($e));
        }
    }
}
