@extends('home')
@section('content')
<div class="container">
    <h1>Danh sách phỏng vấn</h1>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Ứng viên</th>
                <th>HR thực hiện</th>
                <th>Ngày phỏng vấn</th>
                <th>Trạng thái</th>
                <th>Điểm</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($interviews as $interview)
                <tr>
                    <td>{{ $interview->id }}</td>
                    <td>{{ $interview->candidate->user->name }}</td>
                    <td>{{ $interview->hrUser->user->name }}</td>
                    <td>{{ $interview->interview_date }}</td>
                    <td>{{ $interview->status }}</td>
                    <td>{{ $interview->score }}</td>
                    <td>
                        <a href="#" class="btn btn-warning">Sửa</a>
                        <a href="#" class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
