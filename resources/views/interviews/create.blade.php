@extends('layouts.home')

@section('content')
<div class="container">
    <h2 class="mb-4">Tạo lịch phỏng vấn</h2>

    <form action="{{ route('interviews.store') }}" method="POST">
        @csrf

        {{-- Ứng viên --}}
        <div class="mb-3">
            <label for="candidate_id" class="form-label">Ứng viên</label>
            <select name="candidate_id" id="candidate_id" class="form-select" required>
                <option value="">-- Chọn ứng viên --</option>
                @foreach ($candidates as $candidate)
                <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
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
                <option value="{{ $user->id }}" {{ old('interviewer_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('interviewer_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Thời gian --}}
        <div class="mb-3">
            <label for="interview_date" class="form-label">Thời gian phỏng vấn</label>
            <input type="datetime-local" name="interview_date" id="interview_date" class="form-control" value="{{ old('interview_date') }}" required>
            @error('interview_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Vòng --}}
        <div class=" mb-3">
            <div class="col-md-6">
                <label for="round" class="form-label">Vòng phỏng vấn</label>
                <select name="round" id="round" class="form-select" required>
                    <option value="">-- Chọn vòng --</option>
                    <option value="cv_screening">Vòng 1: Sàng lọc CV</option>
                    <option value="hr_interview">Vòng 2: Phỏng vấn HR</option>
                    <option value="technical_interview">Vòng 3: Phỏng vấn chuyên môn</option>
                    <option value="skill_test">Vòng 4: Kiểm tra kỹ năng</option>
                    <option value="final_interview">Vòng 5: Phỏng vấn cuối / Offer</option>
                </select>
            </div>
            {{-- Kết quả --}}
            <div class="mb-3">
                <label for="interview_result" class="form-label">Kết quả</label>
                <select name="result" id="result" class="form-select">
                    <option value="">-- Chưa đánh giá --</option>
                    <option value="pass" {{ old('interview_result') == 'pass' ? 'selected' : '' }}>Đậu</option>
                    <option value="fail" {{ old('interview_result') == 'fail' ? 'selected' : '' }}>Rớt</option>
                    <option value="pending" {{ old('interview_result') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                </select>
                @error('result') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Tạo lịch</button>
            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
