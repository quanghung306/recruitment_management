<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Interview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function ShowDashboardForm()
    {
        // Lấy tổng số ứng viên
        $totalCandidates = Candidate::count();

        // Lấy tổng số ứng viên đã thuê
        $hiredCandidates = Candidate::where('status', 'hired')->count();

        // Lấy tổng số ứng viên bị từ chối
        $rejectedCandidates = Candidate::where('status', 'rejected')->count();

        // Lấy các cuộc phỏng vấn sắp tới
        $upcomingInterviews = Interview::where('interview_date', '>', now())
            ->orderBy('interview_date')
            ->take(5)
            ->get();
        // Lấy 5 cuộc phỏng vấn gần nhất
        $interviews = Interview::with('candidate')->latest()->limit(5)->get();
        return view('layouts.dashboard', compact(
            'interviews',
            'totalCandidates',
            'hiredCandidates',
            'rejectedCandidates',
            'upcomingInterviews'
        ));
    }
    public function index()
    {
        $interviews = Interview::with('candidate')->latest()->limit(5)->get(); // Lấy 5 cuộc phỏng vấn gần nhất

        return response()->json($interviews);
    }
}
