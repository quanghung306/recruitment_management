<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateUpdateRequest;
use App\Models\Candidate;
use App\Models\Skill;
use App\Models\User;
use App\Services\CandidateService;
use App\Exports\CandidatesExport;
use App\Imports\CandidatesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class CandidateController extends Controller
{
    protected $candidateService;

    public function __construct(CandidateService $candidateService)
    {
        $this->candidateService = $candidateService;
    }

    // Display candidate list (view)
    public function showCandidateForm(Request $request)
    {
        return view('candidates.index', [
            'candidates' => $this->candidateService->getAllCandidates($request->all()),
            'skills' => Skill::all(),
            'users' => User::all(),
        ]);
    }

    // API: Return candidate list (JSON)
    public function index(Request $request)
    {
        return response()->json($this->candidateService->getAllCandidates($request->all()));
    }

    // Display create candidate form
    public function createCandidateForm()
    {
        return view('candidates.create', [
            'skills' => Skill::all(),
            'users' => User::all(),
        ]);
    }

    // Store new candidate
    public function store(CandidateStoreRequest $request)
    {
        try {
            $candidate = $this->candidateService->createCandidate($request->validated());
            return $request->wantsJson()
                ? response()->json($candidate->load('skills'), 201)
                : redirect()->route('candidates.index')->with('success', 'Ứng viên đã được tạo thành công');
        } catch (Exception $e) {
            logger()->error('Error creating candidate: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Display edit candidate form
    public function updateCandidateForm(Candidate $candidate)
    {
        return view('candidates.edit', [
            'candidate' => $candidate,
            'skills' => Skill::all(),
            'users' => User::all(),
        ]);
    }

    // Update candidate
    public function update(CandidateUpdateRequest $request, Candidate $candidate)
    {
        try {
            $this->candidateService->updateCandidate($candidate, $request->validated());
            return redirect()->route('candidates.index')->with('success', 'Ứng viên đã được cập nhật');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Delete candidate
    public function destroy(Candidate $candidate)
    {
        try {
            $this->candidateService->deleteCandidate($candidate);
            return request()->wantsJson()
                ? response()->json(['message' => 'Xóa thành công'])
                : redirect()->route('candidates.index')->with('success', 'Ứng viên đã được xóa');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Export candidates to CSV
    public function exportCsv()
    {
        return Excel::download(new CandidatesExport, 'danh_sach_ung_vien.csv');
    }

    // Import candidates from CSV
    public function importCsv(Request $request)
    {
        try {
            Excel::import(new CandidatesImport, $request->file('csv_file'));
            return redirect()->route('candidates.index')->with('success', 'Import CSV thành công.');
        } catch (Exception $e) {
            logger()->error('Import CSV failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi khi import CSV: ' . $e->getMessage()]);
        }
    }
}
