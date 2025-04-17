
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 text-center">Nộp CV Ứng Tuyển</h2>
                </div>
                <div class="card-body p-4">
                    @if (session('errors'))
                    <div id="toast-errors" data-errors="{{ json_encode($errors)}}"></div>
                    @endif

                    @if (session('success'))
                    <div id="toast-success" data-success="{{ session('success') }}"></div>
                    @endif
                    <form action="{{ route('candidates.submit') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Nhập họ tên đầy đủ" required>
                            </div>
                            <div class="invalid-feedback">
                                Vui lòng nhập họ tên của bạn
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="example@email.com" required>
                            </div>
                            <div class="invalid-feedback">
                                Vui lòng nhập email hợp lệ
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control form-control-lg" placeholder="0987 654 321" required>
                            </div>
                            <div class="invalid-feedback">
                                Vui lòng nhập số điện thoại
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="cv" class="form-label fw-bold">Tải lên CV <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" name="cv" id="cv" class="form-control form-control-lg" accept=".pdf,.doc,.docx" required>
                                <button class="btn btn-outline-secondary" type="button" id="cvHelpBtn"><i class="bi bi-question-circle"></i></button>
                            </div>
                            <small class="text-muted">Chấp nhận file PDF, DOC, DOCX (tối đa 5MB)</small>
                            <div class="invalid-feedback">
                                Vui lòng chọn file CV
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-fill me-2"></i>Nộp CV
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <p class="text-muted mb-0 text-center">Thông tin của bạn sẽ được bảo mật theo chính sách của chúng tôi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Enable Bootstrap form validation
        (function () {
            'use strict'
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // CV Help Button
        $('#cvHelpBtn').click(function() {
            Swal.fire({
                title: 'Hướng dẫn tải lên CV',
                html: `
                    <div class="text-start">
                        <p><strong>Yêu cầu file:</strong></p>
                        <ul>
                            <li>Định dạng: PDF, DOC, DOCX</li>
                            <li>Dung lượng tối đa: 5MB</li>
                            <li>Tên file không chứa ký tự đặc biệt</li>
                        </ul>
                        <p><strong>Nội dung CV nên có:</strong></p>
                        <ul>
                            <li>Thông tin liên hệ</li>
                            <li>Kinh nghiệm làm việc</li>
                            <li>Trình độ học vấn</li>
                            <li>Kỹ năng liên quan</li>
                        </ul>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Đã hiểu'
            });
        });

        // Phone number formatting
        $('#phone').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Toast notifications
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
        if (successEl?.dataset.success) {
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
@endsection
