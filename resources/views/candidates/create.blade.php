@extends('home')

@section('title', 'Thêm ứng viên mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Thêm ứng viên mới</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Điện thoại</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>Mới</option>
                        <option value="interviewed" {{ old('status') == 'interviewed' ? 'selected' : '' }}>Đã phỏng vấn</option>
                        <option value="hired" {{ old('status') == 'hired' ? 'selected' : '' }}>Đã tuyển</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="skills" class="form-label">Kỹ năng</label>
                <select name="skills[]" id="skills" class="js-example-basic-multiple js-states form-control" id="id_label_multiple" multiple="multiple">
                    @foreach($skills as $skill)
                    <option value="{{ $skill->id }}" {{ in_array($skill->id, (array) old('skills', [])) ? 'selected' : '' }}>
                        {{ $skill->name }}
                    </option>
                    @endforeach
                </select>
                @error('skills')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">Người đã tuyển</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Chọn người đã tuyển</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#skills').select2({
            placeholder: "Chọn kỹ năng",
            allowClear: true
        });
    });
</script>

@endsection
