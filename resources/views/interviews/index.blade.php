@extends('layouts.app')

@section('title', 'Quản lý phỏng vấn')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Danh sách phỏng vấn</h5>
        <a href="{{ route('interviews.create') }}" class="btn btn-primary">Tạo lịch phỏng vấn</a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ứng viên</th>
                        <th>Người phỏng vấn</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                    <tr>
                        <td>{{ $interview->id }}</td>
                        <td>{{ $interview->candidate->name }}</td>
                        <td>{{ $interview->recruiter->name }}</td>
                        <td>
                            {{ $interview->scheduled_at->format('d/m/Y H:i') }}
                            <div class="text-muted small">{{ $interview->scheduled_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <span class="badge
                                @if($interview->status == 'scheduled') bg-info
                                @elseif($interview->status == 'completed') bg-success
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($interview->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('interviews.show', $interview->id) }}" class="btn btn-sm btn-info">Xem</a>
                            @if($interview->status == 'scheduled')
                                <a href="{{ route('interviews.edit', $interview->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có lịch phỏng vấn nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $interviews->links() }}
    </div>
</div>
@endsection
