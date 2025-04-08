<?php

namespace App\Jobs;

use App\Mail\InterviewEmail;
use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInterviewEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $data = $this->data;

        $interview = Interview::find($data['interview_id']);
        $candidate = $interview->candidate;
        if (!$interview) return;
        Mail::to($data['candidate_email'])->send(new InterviewEmail(
            subjectLine: $data['subject'],
            bodyContent: $data['content'],
            interviewDate: $interview->interview_date->format('d/m/Y'),
            interviewTime: $interview->interview_date->format('H:i'),
            interviewMode: $interview->mode ?? 'Trực tiếp',
            candidateName: $candidate->name ?? null,
        ));
        if (!empty($data['send_copy']) && auth()->check()) {
            Mail::to(auth()->user()->email)->send(new InterviewEmail(
                subjectLine: '[Bản sao] ' . $data['subject'],
                bodyContent: $data['content'],
                interviewDate: $interview->interview_date->format('d/m/Y'),
                interviewTime: $interview->interview_date->format('H:i'),
                interviewMode: $interview->mode ?? 'Trực tiếp',
                candidateName: $candidate->name ?? null,
            ));
        }
    }
}
