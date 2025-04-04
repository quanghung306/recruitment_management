@extends('home')


@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Tổng ứng viên</h5>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Ứng viên trúng tuyển</h5>

            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Ứng viên bị loại</h5>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">PV sắp diễn ra</h5>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Tỷ lệ tuyển dụng</h5>
            </div>
            <div class="card-body">
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar"
                        style="width: 12%"
                        aria-valuenow="12"
                        aria-valuemin="0"
                        aria-valuemax="100">

                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
    /*
    <!-- <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Phỏng vấn sắp tới</h5>
                <a href="{{ route('interviews.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($upcomingInterviews as $interview)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $interview->candidate->name }}</strong>
                            <div class="text-muted small">
                                {{ $interview->interview_date->format('d/m/Y H:i') }}
                                ({{ $interview->interview_date->diffForHumans() }})
                            </div>
                        </div>
                        <a href="{{ route('interviews.show', $interview->id) }}" class="btn btn-sm btn-info">Chi tiết</a>
                    </li>
                    @empty
                    <li class="list-group-item text-center">Không có lịch phỏng vấn sắp tới</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div> -->
    */
    @endphp
</div>
@endsection
