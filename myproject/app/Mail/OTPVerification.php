<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $otp; // <--- 1. Add this public variable

    // 2. Accept the OTP in the constructor
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // 3. Build the message
    public function build()
    {
        return $this->subject('Verify Your Account')
                    ->view('emails.otp'); // <--- Points to the blade file we will make next
    }
}
