<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MerchantCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $merchant;
    public $url;


    /**
     * MerchantCreatedMail constructor.
     * @param  $user
     * @param  $merchant
     * @param string $url
     */
    public function __construct($user, $merchant, string $url)
    {
        $this->user = $user;
        $this->merchant = $merchant;
        $this->url = $url;
    }

    public function build()
    {
        return $this->view('emails.merchant_created')->subject("Merchant Account Created (" . $this->merchant->merchant_name . ")");
    }
}
