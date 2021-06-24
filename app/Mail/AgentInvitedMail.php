<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentInvitedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $merchant;
    public $url;

    /**
     * AgentInvitedMail constructor.
     * @param $user
     * @param string $url
     */
    public function __construct($user, $merchant, string $url)
    {
        //
        $this->user = $user;
        $this->merchant = $merchant;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.agent_invited')->subject("Agent Account Created (" . $this->merchant->merchant_name . ")");

    }
}
