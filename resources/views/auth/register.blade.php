<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 rounded" style="width: 400px;">
            <h2 class="text-center text-primary">Đăng Ký</h2>
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                </div>

                <div class="mb-3 visually-hidden">
                    <label for="role" class="form-label">Vai trò</label>
                    <select name="role" class="form-select" id="role" required>
                        <option value="hr">HR</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Đăng ký</button>

                <div class="mt-3 text-center">
                    <p>Đã có tài khoản? <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập ngay</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
