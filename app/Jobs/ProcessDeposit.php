<?php

namespace App\Jobs;

use App\Mail\TransactionReceipt;
use App\Models\MerchantPayment;
use App\Services\Deposit\DepositService;
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
        $this->transaction = $transaction->load('user');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DepositService::handleDeposit($this->transaction);
    }
}
