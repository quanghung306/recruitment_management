<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? 'Thư mời phỏng vấn' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .info-box {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .footer {
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }

        .btn {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Thư mời phỏng vấn</h2>
        </div>

        <p>Xin chào {{$candidateName ?? 'Ứng viên' }}</p>
        <p>{!! nl2br(e($content)) !!}</p>

        <div class="info-box">
            <!-- <p><strong>Vị trí:</strong> {{ $position }}</p> -->
            <p><strong>Thời gian:</strong> {{ $interviewDate }} lúc {{ $interviewTime }}</p>
            <p><strong>Hình thức:</strong> {{ $interviewMode }}</p>
            @if($interviewLocation)
            <p><strong>Địa điểm:</strong> {{ $interviewLocation }}</p>
            @endif
        </div>

        @if($confirmUrl)
        <p>
            <a href="{{ $confirmUrl }}" class="btn">Xác nhận tham gia</a>
        </p>
        @endif

        <p>Trân trọng,<br>Đội ngũ tuyển dụng</p>

        <div class="footer">
            © {{ date('Y') }} Công ty TNHH ABC. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>

</html>
