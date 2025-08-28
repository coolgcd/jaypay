<?php

namespace App\Mail;


use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;




use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $memid, $name, $rawPassword;

   public function __construct($memid, $name, $rawPassword)
{
    $this->memid = $memid;
    $this->name = $name;
    $this->rawPassword = $rawPassword;
}
    public function build()
    {
        return $this->subject('Welcome to the JAI HO INFRA PRIVATE LIMITED – Your Account Details Inside')
                    ->view('emails.welcome');
    }
}
