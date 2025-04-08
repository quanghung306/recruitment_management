<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewEmail;

class SendInterviewEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $emailData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = $this->emailData['candidate_email'];
        $sendCopy = $this->emailData['send_copy'] ?? false;

        // Gửi mail chính
        Mail::to($recipient)->send(new InterviewEmail(
            $this->emailData['subject'],
            $this->emailData['content']
        ));

        // Gửi bản sao nếu có
        if ($sendCopy && config('mail.from.address')) {
            Mail::to(config('mail.from.address'))->send(new InterviewEmail(
                '[Copy] ' . $this->emailData['subject'],
                $this->emailData['content']
            ));
        }
    }
}
