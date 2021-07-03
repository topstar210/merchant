<?php

namespace App\Jobs;

use App\Mail\DepositReceipt;
use App\Models\MerchantPayment;
use App\Services\FlutterwaveService;
use App\Services\PayswitchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessDeposit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public MerchantPayment $transaction;

    /**
     * ProcessDeposit constructor.
     * @param MerchantPayment $transaction
     */
    public function __construct(MerchantPayment $transaction)
    {
        //
        $this->transaction = $transaction->load('user');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->transaction->payment_method->name == 'Flutterwave') {
            FlutterwaveService::handlePayment($this->transaction);
            $this->sendEmail();
        }

        if ($this->transaction->payment_method->name == 'Payswitch') {
            PayswitchService::handlePayment($this->transaction);
            $this->sendEmail();
        }
    }

    private function sendEmail()
    {
        try {
            Mail::to($this->transaction->user->email)->queue(new DepositReceipt($this->transaction));
        } catch (\Exception $e) {
            Log::error('Exception Error sending Deposit Receipt Email', format_exception($e));
        }
    }
}
