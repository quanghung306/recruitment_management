<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','landingPage')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body>
    @if (session('errors'))
    <div id="toast-errors" data-errors="{{ json_encode($errors)}}"></div>
    @endif

    @if (session('success'))
    <div id="toast-success" data-success="{{ session('success') }}"></div>
    @endif
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
        @include('layouts.hero')
    </section>
    <!-- Stats Section -->
    <section class="py-5 bg-light">
        @include('layouts.status')
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5">
        @include('layouts.how-it-works')
    </section>

    <!-- Benefits Section -->
    <section class="py-5 bg-light">
        @include('layouts.benefits')
    </section>

    <!-- Testimonials Section -->
    <!-- <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Cảm nhận của ứng viên</h2>
                <p class="text-muted lead">Những câu chuyện thành công</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://via.placeholder.com/60" alt="Person" class="rounded-circle me-3">
                                <div>
                                    <h5 class="mb-0">Nguyễn Văn A</h5>
                                    <small class="text-muted">Nhân viên Marketing</small>
                                </div>
                            </div>
                            <p class="mb-0">"Sau khi nộp CV, tôi đã nhận được 3 cuộc gọi từ các công ty phù hợp chỉ trong 2 ngày. Thật tuyệt vời!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://via.placeholder.com/60" alt="Person" class="rounded-circle me-3">
                                <div>
                                    <h5 class="mb-0">Trần Thị B</h5>
                                    <small class="text-muted">Kế toán trưởng</small>
                                </div>
                            </div>
                            <p class="mb-0">"Tôi đã tìm được công việc mơ ước với mức lương tốt hơn 30% nhờ nộp CV tại đây."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://via.placeholder.com/60" alt="Person" class="rounded-circle me-3">
                                <div>
                                    <h5 class="mb-0">Lê Văn C</h5>
                                    <small class="text-muted">Lập trình viên</small>
                                </div>
                            </div>
                            <p class="mb-0">"Hệ thống rất chuyên nghiệp, tôi nhận được phản hồi nhanh chóng và chính xác với nhu cầu của mình."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- CV Submission Section -->
    <section id="submit-cv" class="py-5 text-white ">
        @include('candidates.apply')
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        @include('layouts.faq')
    </section>


    <!-- CTA Section -->
    <section class="py-5 bg-dark text-white">
        <div class="container text-center py-4">
            <h2 class="fw-bold mb-4">Sẵn sàng cho công việc mơ ước?</h2>
            <p class="lead mb-4">Hàng ngàn nhà tuyển dụng đang chờ đợi hồ sơ của bạn</p>
            <a href="#submit-cv" class="btn btn-primary btn-lg px-4">Nộp CV ngay bây giờ</a>
        </div>
    </section>


    <!-- Bootstrap Icons -->
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>
