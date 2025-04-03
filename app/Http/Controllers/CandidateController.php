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
    public function showCandidateForm(Request $request)
    {
        $users = User::all();
        $candidates = $this->candidateService->getAllCandidates($request->all());
        $skills = Skill::all();

        return view('candidates.index', compact('candidates', 'skills', 'users'));
    }

    public function index(Request $request)
    {

        $candidates = $this->candidateService->getAllCandidates($request->all());

        return response()->json($candidates);
    }
    public function createCandidateForm(Request $request)
    {
        $skills = Skill::all();
        $candidate = $this->candidateService->getAllCandidates($request->all());
        $users = User::all();
        return view('candidates.create', compact('candidate', 'skills', 'users'));
    }
    public function store(CandidateStoreRequest $request)
    {
        $candidate = $this->candidateService->createCandidate($request->validated());
        return response()->json($candidate, 201);
    }

    public function updateCandidateForm(Candidate $candidate)
    {
        $skills = Skill::all();
        $users = User::all();
        return view('candidates.edit', compact('candidate', 'skills', 'users'));
    }

    public function update(CandidateUpdateRequest $request, Candidate $candidate)
    {
        $candidate = $this->candidateService->updateCandidate($candidate, $request->validated());
        return response()->json($candidate);
    }

    public function destroy(Candidate $candidate)
    {
        $this->candidateService->deleteCandidate($candidate);
        return response()->json(['message' => 'Xóa thành công']);
    }
}
