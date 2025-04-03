@extends('app')

@section('title', 'Quản lý ứng viên')

@section('content')
<div class="card">
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
                        <th>Hr</th> <!-- Thêm cột này -->
                        <th>Actions</th>
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
                            @foreach($skills as $skill)
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
                        <a href="{{ route('candidates.update', $candidate->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                            <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
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

@push('scripts')
<script>
    // Khởi tạo select2 cho multiselect kỹ năng
    $(document).ready(function() {
        $('#skills').select2({
            placeholder: "Chọn kỹ năng",
            allowClear: true
        });
    });
</script>
@endpush
