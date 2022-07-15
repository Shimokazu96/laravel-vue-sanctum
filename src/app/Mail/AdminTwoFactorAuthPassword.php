<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminTwoFactorAuthPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $two_factor_auth_token = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($two_factor_auth_token)
    {
        $this->two_factor_auth_token = $two_factor_auth_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('app.name'))
            ->subject('【Laravel】２段階認証のパスワード')
            ->view('emails.two_factor_auth.password')
            ->with('two_factor_auth_token', $this->two_factor_auth_token);
    }
}
