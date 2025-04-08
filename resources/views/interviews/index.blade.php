@extends('home')

@section('title', 'Quản lý lịch phỏng vấn')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Danh sách lịch phỏng vấn</h5>
        <a href="{{ route('interviews.create') }}" class="btn btn-primary">Thêm lịch phỏng vấn</a>
    </div>

    <div class="card-body">
        <!-- Bộ lọc (nếu có) -->

        <!-- Bảng danh sách -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Candidate</th>
                        <th>Interviewer</th>
                        <th>DateTime</th>
                        <th>Round</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                    <tr>
                        <td>{{ $loop->iteration + ($interviews->currentPage() - 1) * $interviews->perPage() }}</td>
                        <td>{{ $interview->candidate->name }}</td>
                        <td>{{ $interview->interviewer?->name ?? '-' }}</td>
                        <td>{{ $interview->interview_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $interview->round }}</td>
                        <td>
                            @switch($interview->interview_result)
                            @case('pass') <span class="badge bg-success ls-5">Đậu</span> @break
                            @case('fail') <span class="badge bg-danger fs-6 ">Rớt</span> @break
                            @case('pending') <span class="badge bg-warning text-dark fs-6">Đang chờ</span> @break
                            @default <span class="text-muted">-</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="d-flex  gap-2">
                                <!-- Nút gửi email mời phỏng vấn -->
                                <button class="btn btn-sm btn-outline-info send-email-btn"
                                    data-interview-id="{{ $interview->id }}"
                                    data-candidate-email="{{ $interview->candidate->email }}"
                                    title="Gửi email mời">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <a href="{{ route('interviews.edit', $interview->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa lịch phỏng vấn này?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Chưa có lịch phỏng vấn nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        {{ $interviews->links() }}
    </div>

</div>
@include('interviews.partials.email_modal')
@endsection

@push('scripts')
<script>
    // Khởi tạo select2 cho các bộ lọc nếu có (tuỳ chọn)
    $(document).ready(function() {
        $('#skills').select2({
            placeholder: "Chọn kỹ năng",
            allowClear: true
        });
        // Mở modal khi click nút gửi email
        $('.send-email-btn').click(function() {
            const interviewId = $(this).data('interview-id');
            const candidateEmail = $(this).data('candidate-email');

            $('#interviewId').val(interviewId);
            $('#candidateEmail').val(candidateEmail);

            // Set tiêu đề mặc định
            const candidateName = $(this).closest('tr').find('td:nth-child(2)').text();
            $('#emailSubject').val(`Thư mời phỏng vấn - ${candidateName}`);

            $('#emailModal').modal('show');
        });

        // Xử lý submit form gửi email
        $('#sendEmailForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const interviewId = $('#interviewId').val();

            $.ajax({
                url: `/interviews/${interviewId}/send-email`,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#emailModal').modal('hide');
                    showToast('success', 'Email đã được gửi thành công!');
                },
                error: function(xhr) {
                    showToast('error', 'Có lỗi xảy ra khi gửi email: ' + xhr.responseJSON.message);
                }
            });
        });

        // Thay đổi nội dung mẫu khi chọn loại email
        $('#emailType').change(function() {
            updateEmailTemplate();
        });

        // Cập nhật tiêu đề khi thay đổi loại email
        function updateEmailTemplate() {
            const type = $('#emailType').val();
            const candidateName = $('#emailSubject').val().replace('Thư mời phỏng vấn - ', '');

            const templates = {
                invitation: {
                    subject: `Thư mời phỏng vấn - ${candidateName}`,
                    content: `Kính gửi ${candidateName},\n\nCông ty chúng tôi trân trọng mời bạn tham gia buổi phỏng vấn với thông tin như sau:\n\n- Vị trí: [Vị trí phỏng vấn]\n- Thời gian: [Ngày giờ]\n- Hình thức: [Trực tiếp/Online]\n- Địa điểm/Link: [Chi tiết]\n\nVui lòng xác nhận tham gia.\n\nTrân trọng,\n[Phòng Nhân sự]`
                },
                reminder: {
                    subject: `Nhắc lịch phỏng vấn - ${candidateName}`,
                    content: `Kính gửi ${candidateName},\n\nĐây là email nhắc nhở về buổi phỏng vấn sắp tới:\n\n- Thời gian: [Ngày giờ]\n- Hình thức: [Trực tiếp/Online]\n- Địa điểm/Link: [Chi tiết]\n\nVui lòng đến đúng giờ.\n\nTrân trọng,\n[Phòng Nhân sự]`
                },
                result: {
                    subject: `Kết quả phỏng vấn - ${candidateName}`,
                    content: `Kính gửi ${candidateName},\n\nCảm ơn bạn đã tham gia phỏng vấn tại công ty chúng tôi.\n\nKết quả: [Đậu/Rớt/Chờ]\n\n[Thông tin phản hồi]\n\n[Nếu đậu: Hướng dẫn tiếp theo]\n\nTrân trọng,\n[Phòng Nhân sự]`
                }
            };

            $('#emailSubject').val(templates[type].subject);
            $('#emailContent').val(templates[type].content);
        }

        function showToast(type, message) {
            // Sử dụng toastr hoặc thư viện thông báo khác
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                alert(message);
            }
        }
    });
</script>
@endpush
