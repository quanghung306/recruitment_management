<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #f8f9fa;
            padding: 1rem;
        }

        .content-wrapper {
            margin-left: 280px;
            flex-grow: 1;
            height: 100vh;
            overflow-y: auto;
            padding: 1rem;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <form action="{{ route('logout')}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Đăng xuất</button>
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4>Dashboard</h4>
            <ul class="nav nav-pills flex-column">
                <li><a href="{{ route('dashboard')}}" class="nav-link link-dark">Dashboard</a></li>
                <li><a href="{{ route('candidates.index')}}" class="nav-link link-dark">Ứng viên</a></li>
                <li><a href="#" class="nav-link link-dark">Lịch phỏng vấn</a></li>
            </ul>
        </div>
        <!-- Main Content -->
        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
