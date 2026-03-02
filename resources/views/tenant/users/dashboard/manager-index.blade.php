@extends('layouts/layoutMaster')

@section('title', 'Team Manager View')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/animate-css/animate.scss'])
@vite(['resources/assets/vendor/scss/pages/hitech-portal.scss'])
@endsection
@section('content')
<div class="row g-6">
  <!-- Welcome Section -->
  <div class="col-lg-12">
    <div class="manager-hero animate__animated animate__fadeIn">
      <div class="manager-hero-text">
        <div class="greeting">Welcome Back, {{ auth()->user()->first_name }}! 👋</div>
        <div class="sub-text">Team Manager | Overview & Approvals</div>
      </div>
      <div class="d-none d-md-block" style="position:relative; z-index:1;">
        <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="120" alt="Welcome" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));">
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-lg-7">
    <!-- Pending Approvals Row -->
    <div class="row g-6 mb-6">
      <div class="col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.05s">
        <div class="hitech-stat-card dashboard-variant card-red h-100">
          <div class="stat-card-header">
             <div class="stat-icon-wrap icon-red"><i class="bx bx-git-pull-request"></i></div>
             <a href="{{ route('leaveRequests.index') }}" class="btn btn-sm btn-outline-danger border-0 rounded-pill fw-bold">Review All <i class="bx bx-chevron-right"></i></a>
          </div>
          <div class="mt-2">
            <h3 class="stat-value mb-1" style="color:#dc2626;">{{ $pendingLeaveRequests }}</h3>
            <small class="stat-label text-danger">Pending Leaves</small>
          </div>
        </div>
      </div>

      <div class="col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
        <div class="hitech-stat-card dashboard-variant card-amber h-100">
          <div class="stat-card-header">
             <div class="stat-icon-wrap icon-amber"><i class="bx bx-receipt"></i></div>
             <a href="{{ route('expenseRequests.index') }}" class="btn btn-sm btn-outline-warning border-0 rounded-pill fw-bold" style="color:#d97706;">Review All <i class="bx bx-chevron-right"></i></a>
          </div>
          <div class="mt-2">
            <h3 class="stat-value mb-1" style="color:#d97706;">{{ $pendingExpenseRequests }}</h3>
            <small class="stat-label text-warning">Pending Expenses</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Team Availability Section -->
    <div class="hitech-card mb-6">
      <div class="hitech-card-header">
        <h5 class="title mb-0">Team Availability (Out Today)</h5>
        <span class="badge bg-label-secondary rounded-pill">{{ $teamOutToday->count() }} Out</span>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-borderless mb-0">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Leave Type</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($teamOutToday as $request)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                      @if($request->user->profile_picture)
                        <img src="{{ $request->user->getProfilePicture() }}" alt="Avatar" class="rounded-circle">
                      @else
                        <span class="avatar-initial rounded-circle bg-label-teal" style="background:rgba(0,90,90,0.1); color:#005a5a;">{{ $request->user->getInitials() }}</span>
                      @endif
                    </div>
                    <div>
                      <h6 class="mb-0 fw-bold">{{ $request->user->full_name }}</h6>
                      <small class="text-muted">{{ $request->user->designation->name ?? 'Staff' }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-secondary badge-hitech">{{ $request->leaveType->name ?? 'General' }}</span>
                </td>
                <td>
                  <span class="badge bg-label-success badge-hitech">Approved</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center py-4 text-muted">Everyone is in today! 🚀</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Personal Stats Row -->
    <div class="row g-6 mt-2">
      <div class="col-12">
        <h5 class="mb-2 ms-1 fw-bold text-dark opacity-75">My Personal Stats</h5>
      </div>
      <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
        <div class="hitech-stat-card dashboard-variant card-teal pt-3 pb-3 text-center align-items-center">
          <div class="avatar bg-label-teal p-2 rounded mb-2" style="background:rgba(0,90,90,0.1); color:#005a5a;">
            <i class="bx bx-calendar bx-sm"></i>
          </div>
          <h3 class="stat-value mb-0">{{ $myLeavesCount }}</h3>
          <small class="stat-label text-muted">My Leaves</small>
        </div>
      </div>
      <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
        <div class="hitech-stat-card dashboard-variant card-amber pt-3 pb-3 text-center align-items-center">
          <div class="avatar bg-label-warning p-2 rounded mb-2" style="background:rgba(245,158,11,0.1); color:#d97706;">
            <i class="bx bx-wallet bx-sm"></i>
          </div>
          <h3 class="stat-value mb-0">{{ $myExpensesCount }}</h3>
          <small class="stat-label text-muted">My Expenses</small>
        </div>
      </div>
      <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
        <div class="hitech-stat-card dashboard-variant card-red pt-3 pb-3 text-center align-items-center">
          <div class="avatar bg-label-danger p-2 rounded mb-2" style="background:rgba(239,68,68,0.1); color:#dc2626;">
            <i class="bx bx-error-circle bx-sm"></i>
          </div>
          <h3 class="stat-value mb-0" style="color:#dc2626;">{{ $mySOSCount }}</h3>
          <small class="stat-label text-muted">My SOS Alerts</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Sidebar: Holidays & News -->
  <div class="col-xl-4 col-lg-5">
    <div class="row g-6">
      <div class="col-12">
        <!-- Next Holiday -->
        <div class="holiday-card animate__animated animate__fadeInRight" style="animation-delay:0.05s">
          <i class="bx bx-party holiday-icon"></i>
          <div style="font-size:0.68rem; font-weight:700; color:rgba(255,255,255,0.55); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.6rem;">Upcoming Holiday</div>
          @if($nextHoliday)
            <div style="font-size:1.15rem; font-weight:800; color:#fff; margin-bottom:4px;">{{ $nextHoliday->name }}</div>
            <div style="font-size:0.8rem; color:rgba(255,255,255,0.65);">{{ $nextHoliday->date->format('l, F jS') }}</div>
            <div class="holiday-chip mt-2">In {{ now()->diffInDays($nextHoliday->date) }} Days</div>
          @else
            <div style="font-size:0.875rem; color:rgba(255,255,255,0.6);">No upcoming holidays.</div>
          @endif
        </div>

        <!-- Announcements -->
        <div class="announce-card mt-6 animate__animated animate__fadeInRight" style="animation-delay:0.12s">
          <div class="announce-header">
            <h6 class="mb-0">Company Announcements</h6>
            <i class="bx bx-news bx-sm text-muted"></i>
          </div>
          <div class="announce-body">
            @forelse($recentNotices as $notice)
              <div class="announce-item">
                <div class="announce-dot"></div>
                <div>
                  <div class="announce-title">{{ $notice->title }}</div>
                  <div class="announce-desc">{{ \Illuminate\Support\Str::limit($notice->description, 100) }}</div>
                </div>
              </div>
            @empty
              <p class="text-center text-muted py-4 small">No recent notices.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
