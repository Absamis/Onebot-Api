<?php

namespace App\Mail;

use App\Models\Account;
use App\Models\Accounts\AccountInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAccountInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $account;
    public $invite;
    public $rdr_url;
    public function __construct(Account $account, AccountInvitation $invite, $rdr_url)
    {
        //
        $this->account = $account;
        $this->invite = $invite;
        $this->rdr_url = $rdr_url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account Invitation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.account-invitation',
            with: [
                "account" => $this->account,
                "invite" => $this->invite,
                "rdr_url" => $this->rdr_url
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
