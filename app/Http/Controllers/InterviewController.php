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

class InterviewController extends Controller
{
    public function __construct(protected InterviewService $interviewService) {}

    public function showInterviewsForm(Request $request )
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
        // Kiểm tra xem có dữ liệu hợp lệ không trước khi tạo
        $data = $request->validated();
        if (!isset($data['candidate_id']) || !isset($data['interviewer_id'])) {
            // Xử lý nếu thiếu dữ liệu
            return back()->withErrors(['missing_data' => 'Dữ liệu không đầy đủ']);
        }

        // Tạo mới phỏng vấn
        $this->interviewService->create($data);
        return redirect()->route('interviews.index')->with('success', 'Tạo lịch phỏng vấn thành công!');
    }


    public function edit(Interview $interview)
    {
        $candidates = Candidate::all();
        $interviewers = User::all();
        return view('interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    public function update(UpdateInterviewRequest $request, Interview $interview)
    {
        $this->interviewService->update($interview, $request->validated());
        return redirect()->route('interviews.index')->with('success', 'Cập nhật lịch phỏng vấn thành công!');
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
