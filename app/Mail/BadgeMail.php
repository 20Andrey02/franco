<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BadgeMail extends Mailable
{
    use Queueable, SerializesModels;

    public Participant $participant;
    public string $loginEmail;
    protected string $pdfContent;
    protected string $pdfFilename;

    public function __construct(Participant $participant, string $pdfContent, string $pdfFilename, string $loginEmail)
    {
        $this->participant = $participant;
        $this->pdfContent = $pdfContent;
        $this->pdfFilename = $pdfFilename;
        $this->loginEmail = $loginEmail;
    }

    public function build()
    {
        return $this->view('emails.badge')
                    ->subject('Tu gafete — Evento Francofonía')
                    ->attachData($this->pdfContent, $this->pdfFilename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
