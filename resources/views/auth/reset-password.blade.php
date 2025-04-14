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
    <style>
        body {
            background-color: #f8f9fa;
        }
        .password-reset-card {
            max-width: 500px;
            margin: 5rem auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .password-reset-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-submit {
            background-color: #0d6efd;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-submit:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card password-reset-card">
            <div class="card-header password-reset-header">
                <h4 class="mb-0">Yêu cầu đặt lại mật khẩu</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Email của bạn</label>
                        <input type="email" name="email" class="form-control py-2" id="email" required placeholder="Nhập email đã đăng ký">
                        <div class="form-text">Chúng tôi sẽ gửi yêu cầu đặt lại mật khẩu đến email này.</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-submit btn-lg">
                            <i class="bi bi-envelope-fill me-2"></i> Gửi yêu cầu
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-transparent text-center">
                <small class="text-muted">Nhớ mật khẩu? <a href="{{ route('login') }}">Đăng nhập ngay</a></small>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
