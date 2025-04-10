@extends('home')

@section('title', 'Quản lý tài khoản HR')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Danh sách tài khoản HR</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"> Thêm tài khoản</button>
    </div>
    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ session('errors') }}"></div>
    @endif

    @if(session('success'))
    <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            <span class="badge fs-6 {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $user->is_active ? 'Hoạt động' : 'Ngưng' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal-{{ $user->id }}">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>

                                <form method="POST" action="{{ route('admin.reset', $user) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-key"></i> Reset mật khẩu
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Xoá tài khoản này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i> Xoá
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có tài khoản nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.modal-add-users')
        @foreach ($users as $user)
        @include('admin.partials.modal-edit-users', ['user' => $user])
        @endforeach
        {{-- Nếu có phân trang --}}
        {{-- {{ $users->links() }} --}}
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorEl = document.getElementById('toast-errors');
        if (errorEl) {
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
        if (successEl) {
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
