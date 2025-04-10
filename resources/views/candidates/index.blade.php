@extends('home')

@section('title', 'Quản lý ứng viên')

@section('content')
<div class="card">
    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ session('errors') }}"></div>
    @endif
    @if(session('success'))
    <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Danh sách ứng viên</h5>
        <a href="{{ route('candidates.create') }}" class="btn btn-primary">Thêm ứng viên</a>
    </div>

    <div class="card-body">
        <!-- Bộ lọc -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Mới</option>
                        <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>Đã phỏng vấn</option>
                        <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Đã tuyển</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="skills" class="form-label">Kỹ năng</label>
                    <select name="skills[]" id="skills" class="form-select">
                        @foreach($skills as $skill)
                        <option value="{{ $skill->id }}" {{ in_array($skill->id, request('skills', [])) ? 'selected' : '' }}>
                            {{ $skill->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Lọc</button>
                    <a href="{{ route('candidates.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Bảng danh sách -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Skills</th>
                        <th>Status</th>
                        <th>Hr</th>
                        <th>CV</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                    <tr>
                        <td>{{ $loop->iteration + ($candidates->currentPage() - 1) * $candidates->perPage() }}</td>
                        <td>{{ $candidate->name }}</td>
                        <td >{{ $candidate->email }}</td>
                        <td>{{ $candidate->phone }}</td>
                        <td>
                            @foreach($candidate->skills as $skill)
                            <span>{{ $skill->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge fs-6
                        @if($candidate->status == 'new') bg-info
                        @elseif($candidate->status == 'interviewed') bg-warning
                        @elseif($candidate->status == 'hired') bg-success
                        @else bg-danger
                        @endif">
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </td>
                        <td>
                            @if($candidate->user)
                            {{ $candidate->user->name }}
                            @else
                            Chưa có
                            @endif
                        </td>
                        <td>
                            @if (!empty($candidate->cv_path))
                            <a href="{{ asset('storage/' . $candidate->cv_path) }}"
                                target="_blank"
                                class="btn btn-icon btn-outline-primary rounded-circle"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Xem CV ứng viên">
                                <i class="fa-solid fa-file-arrow-down fs-5"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary ">Chưa có CV</span>
                            @endif
                        </td>


                        <td class="text-center">
                            <div class="d-flex  gap-2">
                                <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa ứng viên này?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có ứng viên nào</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Phân trang -->
        {{ $candidates->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Select2 kỹ năng
        const skillsEl = $('#skills');
        if (skillsEl.length) {
            skillsEl.select2({
                placeholder: "Chọn kỹ năng",
                allowClear: true
            });
        }

        // Tooltip Bootstrap
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(t => new bootstrap.Tooltip(t));

        // SweetAlert2 Toast
        const errorEl = document.getElementById('toast-errors');
        if (errorEl && errorEl.dataset.errors) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: errorEl.dataset.errors,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }

        const successEl = document.getElementById('toast-success');
        if (successEl && successEl.dataset.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: successEl.dataset.success,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
