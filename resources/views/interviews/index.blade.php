@extends('home')

@section('title', 'Quản lý lịch phỏng vấn')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Danh sách lịch phỏng vấn</h5>
        <a href="{{ route('interviews.create') }}" class="btn btn-primary">Thêm lịch phỏng vấn</a>
    </div>

    <div class="card-body">
        <!-- Bộ lọc (nếu có) -->

        <!-- Bảng danh sách -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ứng viên</th>
                        <th>Người phỏng vấn</th>
                        <th>Thời gian</th>
                        <th>Vòng</th>
                        <th>Kết quả</th>
                        <th>Thao tác</th>
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
                                @case('pass') <span class="badge bg-success ls-5">Đậu</span> @break
                                @case('fail') <span class="badge bg-danger fs-6 ">Rớt</span> @break
                                @case('pending') <span class="badge bg-warning text-dark fs-6">Đang chờ</span> @break
                                @default <span class="text-muted">-</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('interviews.edit', $interview->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa lịch phỏng vấn này?')">
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
@endsection

@push('scripts')
<script>
    // Khởi tạo select2 cho các bộ lọc nếu có (tuỳ chọn)
    $(document).ready(function() {
        $('#skills').select2({
            placeholder: "Chọn kỹ năng",
            allowClear: true
        });
    });
</script>
@endpush
