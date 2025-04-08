<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $bodyContent,
        public ?string $interviewDate = null,
        public ?string $interviewTime = null,
        public ?string $interviewMode = null,
        public ?string $interviewLocation = null,
        public ?string $position = null,
        public ?string $confirmUrl = null,
        public ?string $candidateName = null,
    ) {}

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.interview')
                    ->with([
                        'candidateName'=> $this->candidateName,
                        'content' => $this->bodyContent,
                        'interviewDate' => $this->interviewDate,
                        'interviewTime' => $this->interviewTime,
                        'interviewMode' => $this->interviewMode,
                        'subjectLine' => $this->subjectLine,
                    ]);
    }
}
