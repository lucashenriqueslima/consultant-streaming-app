<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateStatusActive extends Mailable
{
    use Queueable, SerializesModels;

    public $nameCandidate;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name)
    {
        $this->nameCandidate = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Cadastro Aprovado - GrowthFlix!')
                    ->view('mails.candidate_register_status.blade')
                    ->with([
                        'user' => $this->nameCandidate,
                    ]);
    }
}
