@extends('home')

@section('title', 'Quản lý ứng viên')

@section('content')
<div class="card">
    @if (session('errors'))
        <div id="toast-errors" data-errors="{{ session('errors') }}"></div>
    @endif

    @if (session('success'))
        <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif
    <div class="card-header d-flex bd-highlight gap-2 align-items-center">
        <div class="me-auto p-2 bd-highlight">
            <h5>Danh sách ứng viên</h5>
        </div>
        <a href="{{ route('candidates.create') }}" class="btn btn-primary">Thêm ứng viên</a>
        <a href="#" id="exportCsvBtn" class="btn btn-success">Export CSV</a>
        <a href="#" id="importCsvBtn" class="btn btn-danger">Import CSV</a>
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
                    <select name="skills[]" id="skills" class="js-example-basic-multiple js-states form-control p-3" id="id_label_multiple" multiple="multiple">
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
                            <td>{{ $candidate->user->name ?? 'Chưa có' }}</td>
                            <td>
                                @if ($candidate->cv_path)
                                    <a href="{{ asset('storage/' . $candidate->cv_path) }}" target="_blank"
                                        class="btn btn-icon btn-outline-primary rounded-circle"
                                        data-bs-toggle="tooltip" title="Xem CV ứng viên">
                                        <i class="fa-solid fa-file-arrow-down fs-5"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Chưa có CV</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('candidates.edit', $candidate->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('candidates.destroy', $candidate->id) }}"
                                          method="POST" class="d-inline delete-form">
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
                        <tr><td colspan="9" class="text-center">Không có ứng viên nào</td></tr>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Select2
        $('#skills').select2({ placeholder: "Chọn kỹ năng", allowClear: true });

        // Tooltip Bootstrap
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(t => new bootstrap.Tooltip(t));

        // SweetAlert2: Delete confirm
        $('.delete-form').on('submit', function (e) {
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
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // SweetAlert2: Toast
        const errorEl = document.getElementById('toast-errors');
        if (errorEl?.dataset.errors) {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'error',
                title: errorEl.dataset.errors, showConfirmButton: false,
                timer: 3000, timerProgressBar: true,
            });
        }

        const successEl = document.getElementById('toast-success');
        if (successEl?.dataset.success) {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: successEl.dataset.success, showConfirmButton: false,
                timer: 3000, timerProgressBar: true,
            });
        }

        // SweetAlert2: Export CSV
        $('#exportCsvBtn').on('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Xuất danh sách ứng viên',
                text: "Bạn có chắc chắn muốn xuất danh sách ứng viên ra file CSV không?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    const link = document.createElement('a');
                    link.href = "{{ route('candidates.export') }}";
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire({
                        title: 'Đang tải...',
                        text: 'File CSV sẽ được tải về trong giây lát.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
        // SweetAlert2: Improt CSV
        $('#importCsvBtn').on('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'đẩy danh sách ứng viên',
                text: "Bạn có chắc chắn muốn đẩy danh sách ứng viên lên không?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    const link = document.createElement('a');
                    link.href = "#";
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    Swal.fire({
                        title: 'Đang tải...',
                        text: 'File CSV sẽ được đẩy lên trong giây lát.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
</script>
@endsection
