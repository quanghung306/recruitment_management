<?php

namespace App\Services;

use App\Models\Interview;
use App\Jobs\SendInterviewEmail;

class InterviewEmailService
{
    public function sendInvitationEmail(Interview $interview): void
    {
        $candidate = $interview->candidate;
        if (!$candidate || !$candidate->email) return;

        $data = [
            'interview_id' => $interview->id,
            'candidate_email' => $candidate->email,
            'email_type' => 'invitation',
            'subject' => 'Thư mời phỏng vấn - ' . $candidate->name,
            'content' => "Chào {$candidate->name},\n\nBạn được mời tham gia buổi phỏng vấn . Vui lòng xác nhận qua link bên dưới.\n\nHẹn gặp bạn!",
        ];
        SendInterviewEmail::dispatch($data);
    }
    public function sendResultEmail(Interview $interview): void
    {
        $candidate = $interview->candidate;
        if (!$candidate || !$candidate->email) return;

        $result = $interview->interview_result;
        if (!in_array($result, ['pass', 'fail'])) return;

        $content = $result === 'pass'
            ? "Chúc mừng bạn đã vượt qua vòng phỏng vấn. Chúng tôi sẽ liên hệ lại với bạn sớm!"
            : "Cảm ơn bạn đã tham gia phỏng vấn. Rất tiếc bạn chưa phù hợp trong lần này.";

        $data = [
            'interview_id' => $interview->id,
            'candidate_email' => $candidate->email,
            'email_type' => 'result',
            'subject' => 'Kết quả phỏng vấn - ' . $candidate->name,
            'content' => $content,
        ];

        SendInterviewEmail::dispatch($data);
    }

    // public function sendReminderEmail(Interview $interview): void
    // {
    //     $candidate = $interview->candidate;
    //     if (!$candidate || !$candidate->email) return;

    //     $data = [
    //         'interview_id' => $interview->id,
    //         'candidate_email' => $candidate->email,
    //         'email_type' => 'reminder',
    //         'subject' => 'Nhắc nhở lịch phỏng vấn - ' . $candidate->name,
    //         'content' => "Nhắc bạn: bạn có lịch phỏng vấn với chúng tôi vào lúc {$interview->scheduled_at->format('H:i d/m/Y')}. Vui lòng có mặt đúng giờ.",
    //     ];

    //     SendInterviewEmail::dispatch($data);
    // }
}
