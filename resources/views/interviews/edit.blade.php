@extends('layouts.home')

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh sửa lịch phỏng vấn</h2>

    <form action="{{ route('interviews.update', $interview->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Ứng viên --}}
        <div class="mb-3">
            <label for="candidate_id" class="form-label">Ứng viên</label>
            <select name="candidate_id" id="candidate_id" class="form-select" required>
                <option value="">-- Chọn ứng viên --</option>
                @foreach ($candidates as $candidate)
                    <option value="{{ $candidate->id }}" {{ old('candidate_id', $interview->candidate_id) == $candidate->id ? 'selected' : '' }}>
                        {{ $candidate->name }}
                    </option>
                @endforeach
            </select>
            @error('candidate_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Người phỏng vấn --}}
        <div class="mb-3">
            <label for="interviewer_id" class="form-label">Người phỏng vấn</label>
            <select name="interviewer_id" id="interviewer_id" class="form-select">
                <option value="">-- Không chọn --</option>
                @foreach ($interviewers as $user)
                    <option value="{{ $user->id }}" {{ old('interviewer_id', $interview->interviewer_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('interviewer_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Thời gian --}}
        <div class="mb-3">
            <label for="interview_date" class="form-label">Thời gian phỏng vấn</label>
            <input type="datetime-local" name="interview_date" id="interview_date" class="form-control"
                value="{{ old('interview_date', \Carbon\Carbon::parse($interview->interview_date)->format('Y-m-d\TH:i')) }}" required>
            @error('interview_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Vòng --}}
        <div class=" row mb-3">
            <label for="round" class="form-label">Vòng phỏng vấn</label>
            <select name="round" id="round" class="form-select" required>
                <option value="">-- Chọn vòng --</option>
                <option value="cv_screening" {{ old('round', $interview->round) == 'cv_screening' ? 'selected' : '' }}>Vòng 1: Sàng lọc CV</option>
                <option value="hr_interview" {{ old('round', $interview->round) == 'hr_interview' ? 'selected' : '' }}>Vòng 2: Phỏng vấn HR</option>
                <option value="technical_interview" {{ old('round', $interview->round) == 'technical_interview' ? 'selected' : '' }}>Vòng 3: Phỏng vấn chuyên môn</option>
                <option value="skill_test" {{ old('round', $interview->round) == 'skill_test' ? 'selected' : '' }}>Vòng 4: Kiểm tra kỹ năng</option>
                <option value="final_interview" {{ old('round', $interview->round) == 'final_interview' ? 'selected' : '' }}>Vòng 5: Phỏng vấn cuối / Offer</option>
            </select>
            @error('round') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Kết quả --}}
        <div class="mb-3">
            <label for="result" class="form-label">Kết quả</label>
            <select name="result" id="result" class="form-select">
                <option value="">-- Chưa đánh giá --</option>
                <option value="pass" {{ old('result', $interview->result) == 'pass' ? 'selected' : '' }}>Đậu</option>
                <option value="fail" {{ old('result', $interview->result) == 'fail' ? 'selected' : '' }}>Rớt</option>
                <option value="pending" {{ old('result', $interview->result) == 'pending' ? 'selected' : '' }}>Đang chờ</option>
            </select>
            @error('result') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
