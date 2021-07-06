<?php

namespace App\Jobs;

use App\Models\MerchantPayment;
use App\Services\Send\SendAccountService;
use App\Services\Send\SendBankService;
use App\Services\Send\SendWalletService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public MerchantPayment $transaction;

    /**
     * ProcessSend constructor.
     * @param MerchantPayment $transaction
     */
    public function __construct(MerchantPayment $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->transaction->product == 'SW') {
            SendWalletService::handleSend($this->transaction);
        }
        if ($this->transaction->product == 'SA') {
            SendAccountService::handleSend($this->transaction);
        }

        if ($this->transaction->product == 'SB') {
            SendBankService::handleSend($this->transaction);
        }
    }
}
