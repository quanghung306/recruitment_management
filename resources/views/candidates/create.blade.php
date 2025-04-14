@extends('home')

@section('title', 'Thêm ứng viên mới')

@section('content')
<div class="card">
    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ json_encode($errors)}}"></div>
    @endif
    <div class="card-header">
        <h5>Thêm ứng viên mới</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control " id="email" name="email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Điện thoại</label>
                    <input
                        type="text"
                        class="form-control "
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        pattern="[0-9]{1,20}"
                        title="Chỉ được nhập số từ 8 đến 20 ký tự"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />

                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select " id="status" name="status">
                        <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>Mới</option>
                        <option value="interviewed" {{ old('status') == 'interviewed' ? 'selected' : '' }}>Đã phỏng vấn</option>
                        <option value="hired" {{ old('status') == 'hired' ? 'selected' : '' }}>Đã tuyển</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>

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
            </div>
            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF/DOCX)</label>
                <input type="file" name="cv" id="cv" class="form-control">
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#skills').select2({
            placeholder: "Chọn kỹ năng",
            allowClear: true
        });
        const errorEl = document.getElementById('toast-errors');
        if (errorEl?.dataset.errors) {
            try {
                const errors = JSON.parse(errorEl.dataset.errors);
                errors.forEach(error => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: error,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            } catch (e) {
                console.error('Error parsing errors:', e);
            }
        }
    });
</script>

@endsection
