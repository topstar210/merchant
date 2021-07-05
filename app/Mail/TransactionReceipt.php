<?php

namespace App\Mail;

use App\Models\MerchantPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class TransactionReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public MerchantPayment $trans;

    /**
     * TransactionReceipt constructor.
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
        return $this->view('emails.transaction_receipt')->subject(Str::title($this->trans->service) . " Transaction Receipt: " . $this->trans->reference);

    }

}
