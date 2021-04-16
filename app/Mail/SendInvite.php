<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;

class SendInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@ben.io')
                ->markdown('emails.invite', [
                    'url' => 'https://laravel.com/docs/8.x/mail',
                    'invitation_token' => $this->invite->invitation_token,
                ]);
    }
}
