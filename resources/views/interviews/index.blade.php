@extends('layouts.home')

@section('title', 'Quản lý lịch phỏng vấn')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Danh sách lịch phỏng vấn</h5>
        <a href="{{ route('interviews.create') }}" class="btn btn-primary">Thêm lịch phỏng vấn</a>
    </div>
    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ session('errors') }}"></div>
    @endif

    @if (session('success'))
    <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif
    @if(session('info'))
    <div id="toast-info" data-info="{{ session('info') }}"></div>
    @endif

    <div class="card-body">

        <!-- Bảng danh sách -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Candidate</th>
                        <th>Interviewer</th>
                        <th>DateTime</th>
                        <th>Round</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                    <tr>
                        <td>{{ $loop->iteration + ($interviews->currentPage() - 1) * $interviews->perPage() }}</td>
                        <td>{{ $interview->candidate->name }}</td>
                        <td>{{ $interview->interviewer?->name ?? '-' }}</td>
                        <td>{{ $interview->interview_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $interview->round }}</td>
                        <td>
                            @switch($interview->interview_result)
                            @case('pass') <span class="badge bg-success fs-6">Đậu</span> @break
                            @case('fail') <span class="badge bg-danger fs-6 ">Rớt</span> @break
                            @case('pending') <span class="badge bg-warning text-dark fs-6">Đang chờ</span> @break
                            @default <span class="text-muted">-</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="d-flex  gap-2">
                                <button type="button"
                                    class="btn btn-sm btn-outline-info send-email-btn visually-hidden"
                                    data-bs-toggle="modal"
                                    data-bs-target="#emailModal"
                                    data-interview-id="{{ $interview->id }}"
                                    data-candidate-email="{{ $interview->candidate->email }}"
                                    data-candidate-name="{{ $interview->candidate->name }}"
                                    title="Gửi email mời">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <a href="{{ route('interviews.edit', $interview->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST" class="d-inline delete-form">
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
                        <td colspan="7" class="text-center">Chưa có lịch phỏng vấn nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Phân trang -->
        {{ $interviews->links() }}
    </div>

</div>
@include('interviews.partials.email-modal')

@endsection

@section('scripts')
<!-- {{--<script>
    document.addEventListener('DOMContentLoaded', function () {
    const emailModal = document.getElementById('emailModal');

    emailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const interviewId = button.getAttribute('data-interview-id');
        const candidateEmail = button.getAttribute('data-candidate-email');
        const candidateName = button.getAttribute('data-candidate-name');

        document.getElementById('interviewId').value = interviewId;
        document.getElementById('candidateEmail').value = candidateEmail;
        document.getElementById('emailSubject').value = `Thư mời phỏng vấn - ${candidateName}`;
        document.getElementById('emailContent').value = '';

    });
});

</script>--}} -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Select2
        $('#skills').select2({
            placeholder: "Chọn kỹ năng",
            allowClear: true
        });

        // Tooltip Bootstrap
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(t => new bootstrap.Tooltip(t));

        // SweetAlert2: Delete confirm
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
        const infoEl = document.getElementById('toast-info');
        if (successEl?.dataset.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: successEl.dataset.success,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            }).then(() => {
                if (infoEl?.dataset.info) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: infoEl.dataset.info,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            });
        }
    });
</script>
@endsection
