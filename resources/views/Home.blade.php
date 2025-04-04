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
                    <li class="nav-item ">
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

    <div class="d-flex vh-100">
        <!-- Sidebar  -->
        <div class="sidebar bg-dark text-white d-flex flex-column flex-shrink-0 p-3" style="width: 280px;">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="fas fa-briefcase me-2 fs-4"></i>
                <span class="fs-5 fw-bold"> Dashboard</span>
            </a>

            <hr class="my-4">

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('candidates.index') }}" class="nav-link text-white {{ request()->routeIs('candidates.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>
                        Candidate Management
                    </a>
                </li>
                <li>
                    <a href="{{ route('interviews.index') }}" class="nav-link text-white {{ request()->routeIs('interviews.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Interview Schedule
                    </a>
                </li>
                <!-- <li>
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-chart-bar me-2"></i>
                        Report
                    </a>
                </li> -->
            </ul>
        </div>
        <!-- Main Content -->
        <main class="content-wrapper flex-grow-1 p-4 " style="overflow-y: auto;">
            @yield('content')
        </main>
    </div>

</body>

</html>
