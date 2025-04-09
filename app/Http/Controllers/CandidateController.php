<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateUpdateRequest;
use App\Models\Candidate;
use App\Services\CandidateService;
use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\User;

class CandidateController extends Controller
{
    protected $candidateService;

    public function __construct(CandidateService $candidateService)
    {
        $this->candidateService = $candidateService;
    }

    // Hiển thị danh sách ứng viên (view)
    public function showCandidateForm(Request $request)
    {
        $users = User::all();
        $skills = Skill::all();
        $candidates = $this->candidateService->getAllCandidates($request->all());

        return view('candidates.index', compact('candidates', 'skills', 'users'));
    }

    // API trả về danh sách ứng viên (JSON)
    public function index(Request $request)
    {
        $candidates = $this->candidateService->getAllCandidates($request->all());
        return response()->json($candidates);
    }

    // Hiển thị form tạo ứng viên
    public function createCandidateForm()
    {
        $skills = Skill::all();
        $users = User::all();
        return view('candidates.create', compact('skills', 'users'));
    }

    // Lưu ứng viên mới
    public function store(CandidateStoreRequest $request)
    {
        try {
            $candidate = $this->candidateService->createCandidate($request->validated());
            if ($request->wantsJson()) {
                return response()->json($candidate->load('skills'), 201);
            }
            return redirect()->route('candidates.index')->with('success', 'Ứng viên đã được tạo thành công');
        } catch (\Exception $e) {
            logger()->error('Error creating candidate: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Hiển thị form chỉnh sửa ứng viên
    public function updateCandidateForm(Candidate $candidate)
    {
        $skills = Skill::all();
        $users = User::all();
        return view('candidates.edit', compact('candidate', 'skills', 'users'));
    }

    // Cập nhật ứng viên
    public function update(CandidateUpdateRequest $request, Candidate $candidate)
    {
        try {
            $updatedCandidate = $this->candidateService->updateCandidate(
                $candidate,
                $request->validated()
            );

            if ($request->wantsJson()) {
                return response()->json($updatedCandidate->load('skills'));
            }

            return redirect()->route('candidates.index')
                           ->with('success', 'Ứngng viên đã được cập nhật');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Xóa ứng viên
    public function destroy(Candidate $candidate)
    {
        try {
            $this->candidateService->deleteCandidate($candidate);

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Xóa thành công']);
            }

            return redirect()->route('candidates.index')
                           ->with('success', 'Ứng viên đã được xóa');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
