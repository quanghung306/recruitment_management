@extends('home')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with date picker -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4 mb-0 fw-bold">Dashboard Report</h1>
        <!-- <div class="d-flex align-items-center">
            <span class="text-muted me-2"><i class="far fa-calendar-alt me-2"></i>{{ now()->format('d M, Y') }}</span>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div> -->
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Candidates -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-hover border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Total Candidates</span>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $totalCandidates }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 visually-hidden">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-arrow-up"></i> 12.5%
                        </span>
                        <span class="text-muted small ms-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hired Candidates -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-hover border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Hired Candidates</span>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $hiredCandidates }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 visually-hidden">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-arrow-up"></i> 8.3%
                        </span>
                        <span class="text-muted small ms-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Candidates -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-hover border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Rejected Candidates</span>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $rejectedCandidates }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-times-circle text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 visually-hidden ">
                        <span class="badge bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-arrow-down"></i> 3.2%
                        </span>
                        <span class="text-muted small ms-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Interviews -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-hover border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small">Upcoming Interviews</span>
                            <h2 class="mt-2 mb-0 fw-bold">{{ $upcomingInterviews->count() }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-alt text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 visually-hidden">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-arrow-up"></i> 5.7%
                        </span>
                        <span class="text-muted small ms-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="row g-4">
        <!-- Recruitment Rate Chart -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recruitment Analytics</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-muted" type="button" id="chartDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartDropdown">
                                <li><a class="dropdown-item" href="#">This Week</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="recruitmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Interviews List -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Upcoming Interviews</h5>
                        <a href="{{ route('interviews.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingInterviews as $interview)
                        <div class="list-group-item list-group-item-action border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($interview->candidate->name) }}&background=random"
                                        class="rounded-circle me-3" width="40" height="40" alt="Candidate">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">{{ $interview->candidate->name }}</h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $interview->round }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <span class="text-muted small">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $interview->interview_date->format('d M, Y H:i') }}
                                        </span>
                                        <span class="text-muted small">
                                            {{ $interview->interview_date->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="far fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No upcoming interviews scheduled</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Recruitment Chart
        const ctx = document.getElementById('recruitmentChart').getContext('2d');
        const recruitmentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                        label: 'Hired',
                        data: [12, 19, 15, 27, 22, 18],
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderRadius: 4
                    },
                    {
                        label: 'Rejected',
                        data: [8, 12, 6, 15, 10, 7],
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderRadius: 4
                    },
                    {
                        label: 'Pending',
                        data: [30, 42, 38, 45, 50, 35],
                        backgroundColor: 'rgba(255, 193, 7, 0.8)',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end'
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        bodyColor: '#000000',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        // Add hover effect to cards
        document.querySelectorAll('.card-hover').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.transition = 'transform 0.2s ease';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });
    </script>

    @endsection
