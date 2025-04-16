<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">Đặt lại mật khẩu</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $email) }}" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-key-fill me-2"></i>Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-transparent">
                    <small class="text-muted">
                        Nhớ mật khẩu? <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập ngay</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (nếu chưa có) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>

</html>
