@extends('home')

@section('title', 'Nộp CV')

@section('content')
<div class="container">
    <h2>Nộp CV</h2>

    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ json_encode($errors)}}"></div>
    @endif

    @if (session('success'))
    <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif

    <form action="{{ route('candidates.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cv" class="form-label">CV</label>
            <input type="file" name="cv" id="cv" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Nộp CV</button>
    </form>
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
