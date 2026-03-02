@extends('layouts/layoutMaster')

@section('title', 'My Attendance')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/animate-css/animate.scss'])
@vite(['resources/assets/vendor/scss/pages/hitech-portal.scss'])
@endsection

@section('content')



<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- HERO SECTION --}}
    <div class="attendance-hero animate__animated animate__fadeIn">
        <div class="attendance-hero-text">
            <div class="greeting">Attendance Tracking</div>
            <div class="sub-text">Monitor your working hours and daily attendance logs.</div>
        </div>
        <div>
            <div class="text-white text-end">
                <div style="font-size:0.75rem; font-weight:700; opacity:0.7; text-transform:uppercase;">Today's Date</div>
                <div style="font-size:1.25rem; font-weight:800;">{{ now()->format('l, d M') }}</div>
            </div>
        </div>
    </div>

    @php
        $presentDays = $attendances->where('status', 'present')->count();
        $totalHours = 0;
        $workCount = 0;
        foreach($attendances as $a) {
            if($a->check_in_time && $a->check_out_time) {
                $totalHours += $a->check_in_time->diffInMinutes($a->check_out_time) / 60;
                $workCount++;
            }
        }
        $avgHours = $workCount > 0 ? round($totalHours / $workCount, 1) : 0;
    @endphp

    {{-- STATS SECTION --}}
    <div class="row g-4 mb-6">
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.05s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-teal"><i class="bx bx-check-double"></i></div>
                <div class="stat-label">Present Days</div>
                <div class="stat-value">{{ $presentDays }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-blue"><i class="bx bx-time"></i></div>
                <div class="stat-label">Avg. Work Hours</div>
                <div class="stat-value">{{ $avgHours }}h</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-amber"><i class="bx bx-calendar-exclamation"></i></div>
                <div class="stat-label">Late Entries</div>
                <div class="stat-value">0</div> {{-- Static for now as schema doesn't seem to track 'late' explicitly --}}
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-red"><i class="bx bx-calendar-x"></i></div>
                <div class="stat-label">Absences</div>
                <div class="stat-value">{{ $attendances->where('status', 'absent')->count() }}</div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
        <div class="hitech-card-header">
            <h5 class="title">Attendance History</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-label-secondary"><i class="bx bx-export me-1"></i> Export</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Working Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-teal me-3 mb-0" style="width:32px; height:32px; font-size:0.9rem;">
                                        <i class="bx bx-calendar-event"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $attendance->created_at->format('D, d M Y') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-semibold">
                                    <i class="bx bx-log-in-circle text-success me-1"></i>
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : '--:--' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-semibold">
                                    <i class="bx bx-log-out-circle text-danger me-1"></i>
                                    {{ $attendance->check_out_time ? $attendance->check_out_time->format('h:i A') : '--:--' }}
                                </div>
                            </td>
                            <td>
                                @if($attendance->check_in_time && $attendance->check_out_time)
                                    @php
                                        $duration = $attendance->check_in_time->diff($attendance->check_out_time);
                                        $hours = $duration->h + ($duration->i / 60);
                                        $barWidth = min(100, ($hours / 9) * 100);
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-primary">{{ $duration->format('%H:%I') }}</span>
                                        <div class="progress w-px-75" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $barWidth }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">--:--</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-hitech bg-label-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'warning') }}">
                                    <i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bx bx-info-circle fs-2 d-block mb-2 opacity-50"></i>
                                No attendance logs found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
