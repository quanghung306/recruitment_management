@foreach ($users as $user)
<div class="modal fade" id="editModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.update', $user->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $user->id }}">Chỉnh sửa tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="name-{{ $user->id }}" class="form-label">Tên</label>
                    <input type="text" class="form-control" id="name-{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email-{{ $user->id }}" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email-{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="is_active-{{ $user->id }}" class="form-label">Trạng thái</label>
                    <select name="is_active" id="is_active-{{ $user->id }}" class="form-select" required>
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Ngưng</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button type="submit" class="btn btn-primary" >Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endforeach
