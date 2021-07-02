<?php

namespace App\Mail;

use App\Models\MerchantPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public MerchantPayment $trans;

    /**
     * DepositReceipt constructor.
     * @param MerchantPayment $trans
     */
    public function __construct(MerchantPayment $trans)
    {
        //
        $this->trans = $trans->refresh();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.deposit_receipt')->subject("Wallet Deposit Receipt: " . $this->trans->reference);

    }
}
