<?php
namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function ShowDashboardForm()
    {
        // Truy vấn danh sách các cuộc phỏng vấn
        $interviews = Interview::with('candidate')->latest()->limit(5)->get(); // Lấy 5 cuộc phỏng vấn gần nhất

        // Truyền biến $interviews vào view
        return view('layouts.dashboard', compact('interviews'));
    }

    public function index()
    {
        $interviews = Interview::with('candidate')->latest()->limit(5)->get(); // Lấy 5 cuộc phỏng vấn gần nhất

        return response()->json($interviews);
    }
}
