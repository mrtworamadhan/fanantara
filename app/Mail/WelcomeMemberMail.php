<?php

namespace App\Mail;

use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMemberMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $accounts;

    public function __construct(Member $member)
    {
        $this->member = $member;
        $this->accounts = $member->savingAccounts()->with('savingType')->get();
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.id-card', ['member' => $this->member]);

        return $this->subject('Selamat Bergabung di Koperasi Kami! [PENTING]')
                    ->view('emails.welcome_member');
                    
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Member Fanantara',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
