<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 rounded" style="width: 400px;">
            <h2 class="text-center text-primary">Đăng Nhập</h2>
            @if (session('errors') && !session('account_locked'))
            <div id="toast-errors" data-errors="{{ session('errors') }}"></div>
            @endif

            @if(session('success'))
            <div id="toast-success" data-success="{{ session('success') }}"></div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>

            <div class="mt-3 text-center">
                <p><a href="{{ route('password.request') }}" class="text-decoration-none">Quên mật khẩu?</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
@if (session('account_locked'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Tài khoản đã bị khóa',
        text: 'Vui lòng liên hệ admin để được hỗ trợ.',
        confirmButtonText: 'Đã hiểu',
        timer: 5000,
        timerProgressBar: true,
    });
</script>
@endif
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

</html>
