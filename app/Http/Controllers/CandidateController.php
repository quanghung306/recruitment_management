<?php

namespace App\Http\Controllers;

use App\Events\CandidateApplied;
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
use Illuminate\Support\Facades\Log;

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
    public function ShowApllyForm()
    {
        return view('candidates.apply');
    }
    // upload CV
    public function storeApplication(CandidateStoreRequest $request)
    {
        try {
            if ($request->hasFile('cv')) {
                $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
            }
            $candidate = $this->candidateService->createCandidate($request->validated());
            // Gửi sự kiện real-time
            event(new CandidateApplied($candidate));
            return redirect()->back()->with('success', 'Cảm ơn bạn đã ứng tuyển!');
        } catch (Exception $e) {
            Log::error('Error storing application: ' . $e->getMessage());
            return redirect()->back()->with(['errors' => 'Có lỗi xảy ra khi nộp đơn.']);
        }
    }
    // // API: Return candidate list (JSON)
    // public function index(Request $request)
    // {
    //     return response()->json($this->candidateService->getAllCandidates($request->all()));
    // }

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
        $this->candidateService->createCandidate($request->validated());
        session()->flash('info', 'Một ứng viên mới đã apply!');
        return redirect()->route('candidates.index')
            ->with('success', 'Ứng viên tạo thành công');
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
            return redirect()->back()->with(['errors' => $e->getMessage()]);
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
            return back()->with(['errors' => $e->getMessage()]);
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
            log::error('Import CSV failed: ' . $e->getMessage());
            return back()->with(['errors' => 'Lỗi khi import CSV: trùng dữ liệu hoặc định dạng không đúng.']);
        }
    }
}
