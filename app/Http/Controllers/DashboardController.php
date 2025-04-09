<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function ShowDashboardForm()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Chỉ admin mới lấy số liệu quản lý
            $totalCandidates = Candidate::count();
            $hiredCandidates = Candidate::where('status', 'hired')->count();
            $rejectedCandidates = Candidate::where('status', 'rejected')->count();
            $upcomingInterviews = Interview::where('interview_date', '>', now())
                ->orderBy('interview_date')->take(5)->get();

            return view('layouts.dashboard', compact(
                'totalCandidates',
                'hiredCandidates',
                'rejectedCandidates',
                'upcomingInterviews'
            ));
        }

        if ($user->role === 'hr') {
            // HR chỉ xem danh sách lịch phỏng vấn
            $upcomingInterviews = Interview::where('interview_date', '>', now())
                ->orderBy('interview_date')->take(5)->get();
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

            $interviews = Interview::with('candidate')->latest()->limit(5)->get();
            return view('layouts.dashboard', compact(
                'interviews',
                'totalCandidates',
                'hiredCandidates',
                'rejectedCandidates',
                'upcomingInterviews'
            ));
        }
    }
    public function index()
    {
        $interviews = Interview::with('candidate')->latest()->limit(5)->get();
        return response()->json($interviews);
    }
}
