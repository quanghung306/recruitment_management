@extends('layouts.home')

@section('title', 'Quản lý ứng viên')

@section('content')
<div class="card">
    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ session('errors') }}"></div>
    @endif

    @if (session('success'))
    <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif
    @if(session('info'))
    <div id="toast-info" data-info="{{ session('info') }}"></div>
    <h1>hello</h1>
    @endif

    <div class="card-header d-flex bd-highlight gap-2 align-items-center">
        <h5 class="me-auto p-2 bd-highlight">Danh sách ứng viên</h5>
        <a href="{{ route('candidates.create') }}" class="btn btn-primary">Thêm ứng viên</a>
        <button id="exportCsvBtn" class="btn btn-success">Export CSV</button>
        <button id="importCsvBtn" class="btn btn-danger">Import CSV</button>
    </div>

    <div class="card-body">
        <!-- Bộ lọc -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach(['new' => 'Mới', 'interviewed' => 'Đã phỏng vấn', 'hired' => 'Đã tuyển', 'rejected' => 'Từ chối'] as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="skills" class="form-label">Kỹ năng</label>
                    <select name="skills[]" id="skills" class="form-control js-example-basic-multiple" multiple>
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
                        <th>Phone</th>
                        <th>Skills</th>
                        <th>Status</th>
                        <th>HR</th>
                        <th>CV</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                    <tr>
                        <td>{{ $loop->iteration + ($candidates->currentPage() - 1) * $candidates->perPage() }}</td>
                        <td>{{ $candidate->name }}</td>
                        <td>{{ $candidate->email }}</td>
                        <td>{{ $candidate->phone }}</td>
                        <td>
                            @forelse($candidate->skills as $skill)
                            <span>{{ $skill->name }}</span>
                            @empty
                            <span class="badge bg-secondary">Chưa có kỹ năng</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="badge fs-6 bg-{{ ['new' => 'info', 'interviewed' => 'warning', 'hired' => 'success', 'rejected' => 'danger'][$candidate->status] ?? 'secondary' }}">
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </td>
                        <td>{{ $candidate->user->name ?? 'Chưa có HR' }}</td>
                        <td>
                            @if ($candidate->cv_path)
                            <a href="{{ asset('storage/' . $candidate->cv_path) }}" target="_blank" class="btn btn-icon btn-outline-primary rounded-circle" data-bs-toggle="tooltip" title="Xem CV ứng viên">
                                <i class="fa-solid fa-file-arrow-down fs-5"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary">Chưa có CV</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2">
                                <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Không có ứng viên nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        {{ $candidates->links() }}
    </div>
</div>

<!-- Modal Import CSV -->
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('candidates.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="importCsvModalLabel">Import danh sách ứng viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="csv_file" class="form-label">Chọn file CSV</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                </div>
                <p class="text-muted">File CSV phải có các cột: name, email, phone, status, cv_path</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Import</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
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

        $('[data-bs-toggle="tooltip"]').each((_, el) => new bootstrap.Tooltip(el));

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Ứng viên sẽ bị xóa và không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then(result => result.isConfirmed && form.submit());
        });

        const showToast = (type, message) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        };

        const errorEl = $('#toast-errors').data('errors');
        if (errorEl) showToast('error', errorEl);

        const successEl = $('#toast-success').data('success');
        if (successEl) showToast('success', successEl);

        $('#exportCsvBtn').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Xuất danh sách ứng viên',
                text: "Bạn có chắc chắn muốn xuất danh sách ứng viên ra file CSV không?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có',
                cancelButtonText: 'Không'
            }).then(result => {
                if (result.isConfirmed) {
                    const link = document.createElement('a');
                    link.href = "{{ route('candidates.export') }}";
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    showToast('success', 'File CSV sẽ được tải về trong giây lát.');
                }
            });
        });
        $('#importCsvBtn').on('click', () => new bootstrap.Modal($('#importCsvModal')).show());
        window.Echo.channel('hr-channel')
            .listen('.candidate.applied', (e) => {
                console.log('🔥 Dữ liệu nhận:', JSON.stringify(e, null, 2));
                console.log('Ứng viên mới:', e);
                if (e.candidate.name) {
                    let count = parseInt(document.getElementById('notification-count').innerText);
                    console.log("Before update, notification count:", count);
                    document.getElementById('notification-count').innerText = count + 1;
                    console.log("After update, notification count:", document.getElementById('notification-count').innerText);
                    Swal.fire({
                        icon: 'info',
                        title: '🎉 Ứng viên mới',
                        text: `${e.candidate.name} vừa apply!`,
                        showConfirmButton: true,
                        timerProgressBar: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                } else {
                    console.log("Dữ liệu ứng viên không hợp lệ:", e);
                }
            }).error((error) => {
                console.log('Echo error:', error);
            });
    });
</script>
@endsection
