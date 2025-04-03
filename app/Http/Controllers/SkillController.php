<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    // Lấy danh sách tất cả kỹ năng
    public function index()
    {
        return response()->json(Skill::all());
    }

    // Tạo kỹ năng mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:skills,name|max:255',
        ]);

        $skill = Skill::create(['name' => $request->name]);

        return response()->json($skill, 201);
    }

    // Xóa kỹ năng
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->json(['message' => 'Xóa kỹ năng thành công']);
    }
}
