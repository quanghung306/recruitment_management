<?php

namespace App\Events;

use App\Models\Candidate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CandidateApplied implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $candidate;

    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }


    public function broadcastOn()
    {
        return new Channel('hr-channel');
    }

    public function broadcastAs()
    {
        return 'candidate.applied';
    }

    public function broadcastWith()
    {
        return [
            'candidate' => [
                'name' => $this->candidate->name,
                'email' => $this->candidate->email,
            ],
            'info' => 'Có một ứng viên mới nộp hồ sơ!',
        ];
    }
}
