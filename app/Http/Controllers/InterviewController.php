<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\UpdateInterviewRequest;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\User;
use App\Services\InterviewService;
use Illuminate\Http\Request;
use App\Mail\InterviewEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendInterviewEmail;
use App\Services\InterviewEmailService;

class InterviewController extends Controller
{
    public function __construct(
        protected InterviewService $interviewService,
        protected InterviewEmailService $emailService
    ) {}

    public function showInterviewsForm(Request $request)
    {

        $interviews = $this->interviewService->getAllInterview($request->all());
        return view('interviews.index', compact('interviews',));
    }
    public function index(Request $request)
    {
        $interviewers = $this->interviewService->getAllInterview($request->all());
        return response()->json($interviewers);
    }

    public function create()
    {
        $candidates = Candidate::all();
        $interviewers = User::all();
        if ($candidates->isEmpty()) {
            $candidates = collect();
        }
        if ($interviewers->isEmpty()) {
            $interviewers = collect();
        }
        return view('interviews.create', compact('candidates', 'interviewers'));
    }

    public function store(StoreInterviewRequest $request)
{
    $data = $request->validated();

    if (empty($data['candidate_id']) || empty($data['interviewer_id'])) {
        return back()->withErrors(['missing_data' => 'Dữ liệu không đầy đủ']);
    }

    $interview = $this->interviewService->createInterview($data);

    session()->flash('success', 'Tạo lịch phỏng vấn thành công!');

    if ($interview->interview_result === 'pending') {
        $this->emailService->sendInvitationEmail($interview);
        session()->flash('info', 'Đã gửi email mời phỏng vấn!');
    } elseif (in_array($interview->interview_result, ['pass', 'fail'])) {
        $this->emailService->sendResultEmail($interview);
        session()->flash('info', 'Đã gửi email kết quả phỏng vấn!');
    }

    return redirect()->route('interviews.index');
}

    public function edit(Interview $interview)
    {
        $candidates = Candidate::all();
        $interviewers = User::all();
        return view('interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    public function update(UpdateInterviewRequest $request, Interview $interview)
    {

        $data = $request->validated();
        $this->interviewService->update($interview, $request->validated());
        $this->interviewService->update($interview, $data);
        session()->flash('success', 'Cập nhật lịch phỏng vấn thành công !');
        if (in_array($interview->interview_result, ['pass', 'fail'])) {
            $this->emailService->sendResultEmail($interview);
            session()->flash('info', 'Đã gửi email kết quả phỏng vấn !');
        }
        return redirect()->route('interviews.index');
    }

    public function destroy(Interview $interview)
    {
        $this->interviewService->delete($interview);
        return back()->with('success', 'Xóa lịch phỏng vấn thành công!');
    }
    public function sendEmail(SendEmailRequest $request)
    {
        $data = $request->validated();
        SendInterviewEmail::dispatch($data);

        return back()->with('success', 'Đã gửi email thành công!');
    }
}
