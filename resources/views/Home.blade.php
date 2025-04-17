<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    @if(session('info'))
    <div id="toast-info" data-info="{{ session('info') }}"></div>
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 38px; height: 38px; font-weight: 600;">
                                {{ strtoupper(Auth::user()->name[0]) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="#">
                                    <span><i class="bi bi-bell me-2"></i>Thông báo</span>
                                    <span class="badge bg-danger" id="notification-count">0</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
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
                @if (isHR() || isAdmin())
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active text-dark bg-white' : 'text-white' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                @endif
                @if (isHR() || isAdmin())
                <li>
                    <a href="{{ route('candidates.index') }}" class="nav-link {{ request()->routeIs('candidates.*') ? 'active text-dark bg-white' : 'text-white' }}">
                        <i class="fas fa-users me-2"></i>
                        Candidate Management
                    </a>
                </li>
                @endif
                @if (isHR())
                <li>
                    <a href="{{ route('interviews.index') }}" class="nav-link {{ request()->routeIs('interviews.*') ? 'active text-dark bg-white' : 'text-white' }}">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Interview Schedule
                    </a>
                </li>
                @endif
                @if (isAdmin())
                <li>
                    <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active text-dark bg-white' : 'text-white' }}">
                        <i class="fas fa-user-shield me-2"></i>
                        Account Management
                    </a>
                </li>
                @endif
                @guest
                <a href="{{ route('candidates.form') }}" class="nav-link {{request()->routeIs('candidates.*') ? 'active text-dark bg-white' : 'text-white'}}">
                    <i class="fas fa-user-shield me-2"></i>
                    Apply for Job
                </a>
                @endguest
            </ul>
        </div>
        <!-- Main Content -->
        <main class="content-wrapper flex-grow-1 p-4 " style="overflow-y: auto;">
            @yield('content')
        </main>
    </div>
    @vite('resources/js/app.js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    <script>
        $(document).ready(function() {
            window.Echo.channel('hr-channel')
                .listen('.candidate.applied', (e) => {
                    console.log('Ứng viên mới:', e.candidate);
                    let count = parseInt(document.getElementById('notification-count').innerText);
                    console.log("Before update, notification count:", count);
                    document.getElementById('notification-count').innerText = count + 1;
                    console.log("After update, notification count:", document.getElementById('notification-count').innerText);
                    const infoEl = document.getElementById('toast-info');
                    if (infoEl?.dataset.info) {
                    Swal.fire({
                        icon: 'info',
                        title: '🎉 Ứng viên mới',
                        text: `${e.candidate.name} vừa apply!`,
                        showConfirmButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }}).error((error) => {
                    console.log('Echo error:', error);
                });
        });
    </script>
</body>


</html>
