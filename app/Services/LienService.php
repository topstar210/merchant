<?php


namespace App\Services;


use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\MerchantLien;
use App\Models\MerchantPayment;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LienService
{
    public static function releaseLien(MerchantLien  $lien)
    {
        $merchant = $lien->merchant;
        $user = $merchant->users()->without('wallet')->where('type', 'merchant')->first();

        $wallet = Wallet::firstOrCreate(
            ['currency_id' => $lien->currency_id, 'user_id' => $user->id],
            [
                "balance" => 0.00,
                "limit_amount" => 0.00,
                "is_default" => 'No'
            ]
        );
        $old_balance = $wallet->balance;
        $wallet->balance = ($wallet->balance + $lien->lien_amount);
        $wallet->save();

        $lien->status = 'RELEASED';
        $lien->save();

        $merchant_payment = MerchantPayment::query()->create([
            'merchant_id' => $merchant->id,
            'user_id' => $user->id,
            'transaction_type' => DEPOSITS,
            'reference' => (string)rand(100000000000, 999999999999),
            'amount' => $lien->lien_amount,
            'charges' => 0,
            'exchange_amount' => $lien->lien_amount,
            'exchange_rate' => 1,
            'base_currency' => $lien->currency->code,
            'exchange_currency' => $lien->currency->code,
            'account' => null,
            'account_name' => null,
            'status' => "Success",
            'institution' => null,
            'service' => "DEPOSIT",
            'balance_before' => $old_balance,
            'balance_after' => $wallet->balance,
            'product' => "WF",
            'initiator_id' => $user->id,
            'response' => [
                "charge_percentage" => 0,
                "charge_fixed" => 0,
                "total" => $lien->lien_amount
            ],
            'wallet_id' => $wallet->id,
            'payment_method_id' => 1
        ]);

        $transaction = Resource::saveDepositTrans(new MerchantPayment($merchant_payment->toArray()),
            [
                "status" => 1,
                "message" => "Merchant Lien released to wallet",
                "balance" => $wallet->balance
            ]);

        $merchant_payment->transaction()->associate($transaction);
        $merchant_payment->save();

        try {
            Mail::to($user->email)->queue(new TransactionReceipt(new MerchantPayment($merchant_payment->toArray())));
        } catch (\Exception $e) {
            Log::error('Exception Error sending Deposit Receipt Email', format_exception($e));
        }

        return response()->json(['error' => false, 'error_message' => 'Merchant Lien Released Successfully']);

    }

}
