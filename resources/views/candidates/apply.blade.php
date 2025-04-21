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
                    <!-- Progress bar -->
                    <div class="progress mt-2 d-none" id="uploadProgress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small id="uploadStatus" class="text-muted"></small>
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
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
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

        // Form Submit via AJAX
        $('form.needs-validation').on('submit', function(event) {
            event.preventDefault();

            const form = this;
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("candidates.submit") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', $('input[name="_token"]').val());

            $('#submitBtn').prop('disabled', true).text('Đang tải...');
            $('#uploadProgress').removeClass('d-none');

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total * 100).toFixed(2);
                    $('.progress-bar').css('width', percent + '%');
                    $('#uploadStatus').text(`Đang tải lên: ${percent}%`);
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'CV đã được nộp thành công!',
                        willClose: () => window.location.reload()
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: xhr.responseText || 'Đã xảy ra lỗi khi nộp CV.',
                    });
                    $('#submitBtn').prop('disabled', false).text('Nộp CV');
                }
            };

            xhr.onerror = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi mạng!',
                    text: 'Không thể kết nối đến máy chủ.',
                });
                $('#submitBtn').prop('disabled', false).text('Nộp CV');
            };

            xhr.send(formData);
        });
    });
</script>

@endsection
