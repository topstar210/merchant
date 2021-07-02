<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthorizationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * AuthorizationMail constructor.
     * @param $token
     */
    public function __construct(String $token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.authorization')->subject('Login Authorization Token');
    }
}
