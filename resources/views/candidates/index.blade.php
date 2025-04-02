@extends('app')

@section('content')
<div class="container">
    <h1>Danh sách ứng viên</h1>
    <a href="{{ route('candidates.create') }}" class="btn btn-primary">Thêm ứng viên</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên ứng viên</th>
                <th>Kỹ năng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidates as $candidate)
                <tr>
                    <td>{{ $candidate->id }}</td>
                    <td>{{ $candidate->user->name }}</td>
                    <td>{{ $candidate->skills }}</td>
                    <td>{{ $candidate->status }}</td>
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
