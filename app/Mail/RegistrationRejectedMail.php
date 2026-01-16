<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $rejectionNote;

    public function __construct(Member $member, $rejectionNote)
    {
        $this->member = $member;
        $this->rejectionNote = $rejectionNote;
    }

    public function build()
    {
        return $this->subject('Pembaruan Status Pendaftaran - Koperasi Fanantara')
                    ->view('emails.registration-rejected');
    }
}