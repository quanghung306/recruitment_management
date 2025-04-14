<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Gửi email mời phỏng vấn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendEmailForm" method="POST" action="{{ route('interviews.sendEmail') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="interviewId" name="interview_id">
                    <input type="hidden" id="candidateEmail" name="candidate_email">

                    <div class="mb-3">
                        <label for="emailType" class="form-label">Loại email</label>
                        <select class="form-select" id="emailType" name="email_type" required>
                            <option value="invitation">Thư mời phỏng vấn</option>
                            <option value="reminder">Nhắc lịch phỏng vấn</option>
                            <option value="result">Kết quả phỏng vấn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="emailSubject" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="emailSubject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="emailContent" class="form-label">Nội dung</label>
                        <textarea class="form-control" id="emailContent" name="content" rows="5" required></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="sendCopy" name="send_copy">
                        <label class="form-check-label" for="sendCopy">
                            Gửi bản sao cho tôi
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Gửi email</button>
                </div>
            </form>
        </div>
    </div>
</div>

