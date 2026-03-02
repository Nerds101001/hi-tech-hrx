@extends('layouts.layoutMaster')

@section('title', __('Attendance Management'))

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    .btn, .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
        border-radius: 10px !important;
    }
    /* Quick Action Refinement */
    .quick-action-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border-color: #005a5a;
        background: #fdfefe;
    }
    .quick-action-card .action-icon {
        width: 48px;
        height: 48px;
        background: rgba(0, 90, 90, 0.08);
        color: #005a5a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .quick-action-card:hover .action-icon {
        background: #005a5a;
        color: #fff;
        transform: rotate(10deg);
    }
    .quick-action-card .action-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    .quick-action-card .action-subtitle {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Integrated Filter Styling */
    .bg-light-soft {
        background-color: rgba(0, 90, 90, 0.04) !important;
        border-bottom: 1px solid rgba(0, 90, 90, 0.08);
    }
    .search-wrapper-hitech .form-control:focus {
        background: #fff !important;
    }
    .btn-hitech-export {
        background: #005a5a;
        color: white;
        border: none;
        border-radius: 10px !important; 
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-hitech-export:hover {
        background: #004d4d;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 90, 90, 0.2);
    }

    /* Badge Customization */
    .badge-hitech-success {
        background: rgba(0, 90, 90, 0.08);
        color: #005a5a;
        border: 1px solid rgba(0, 90, 90, 0.1);
    }
    .badge-hitech-danger {
        background: rgba(255, 77, 73, 0.1);
        color: #ff4d49;
        border: 1px solid rgba(255, 77, 73, 0.2);
    }
    .search-light-badge {
        background: rgba(0, 90, 90, 0.08);
        color: #005a5a;
        border: 1px solid rgba(0, 90, 90, 0.1);
    }
    .compact-select {
        min-width: 140px;
    }
    .filter-item-hitech {
        height: 42px !important;
        border-radius: 10px !important;
    }
    .select2-container--bootstrap-5 .select2-selection {
        height: 42px !important;
        display: flex !important;
        align-items: center !important;
    }
    .btn-hitech-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #005a5a;
        color: white;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .btn-hitech-icon:hover {
        background: #004d4d;
        color: white;
        transform: translateY(-2px);
    }
    .search-wrapper-hitech {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 50px !important;
        padding: 4px 4px 4px 0.5rem;
        display: flex;
        align-items: center;
        height: 50px;
        transition: all 0.3s ease;
    }
    .search-wrapper-hitech:focus-within {
        border-color: #005a5a;
        box-shadow: 0 0 0 4px rgba(0, 90, 90, 0.05);
    }
    .search-wrapper-hitech .form-control {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        height: 100% !important;
        font-size: 0.95rem;
        color: #1e293b;
    }
    .search-wrapper-hitech .btn-search {
        height: 40px !important;
        border-radius: 50px !important;
        background: #005a5a !important;
        color: #fff !important;
        padding: 0 1.75rem !important;
        font-weight: 600 !important;
        border: none !important;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .search-wrapper-hitech .btn-search:hover {
        background: #004d4d !important;
        box-shadow: 0 4px 12px rgba(0, 90, 90, 0.2);
    }
    .stat-card-link-arrow {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #94a3b8;
        transition: all 0.3s ease;
    }
    .hitech-stat-card:hover .stat-card-link-arrow {
        background: rgba(0, 90, 90, 0.08);
        color: #005a5a;
        transform: translateX(3px);
    }
  </style>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/apex-charts/apexcharts.js'
  ])
@endsection

@section('content')
<div class="px-4">
<div class="row g-6 mb-6">
  <!-- Hero Banner -->
  <div class="col-lg-12">
    <x-hero-banner 
      title="Attendance Management" 
      subtitle="Track, monitor and optimize employee presence in real-time"
      icon="bx-time-five"
      gradient="teal"
      quote="Punctuality is not just about being on time, it's about respecting other people's time."
    />
  </div>
</div>

<!-- Stats Cards -->
<div class="row g-6 mb-6">
  <x-stat-card 
    title="Today's Present" 
    value="{{ $todayPresentCount ?? 0 }}" 
    icon="bx-user-check" 
    color="success"
    trend="up"
    trendValue="+{{ $todayPresentCount > 0 ? round(($todayPresentCount / ($activeUsersCount ?: 1)) * 100) : 0 }}%"
    animation-delay="0.1s"
  />
  
  <x-stat-card 
    title="Today's Absent" 
    value="{{ $todayAbsentCount ?? 0 }}" 
    icon="bx-user-x" 
    color="danger"
    trend="down"
    trendValue="{{ $todayAbsentCount }}"
    animation-delay="0.2s"
  />
  
  <x-stat-card 
    title="On Leave" 
    value="{{ $onLeaveCount ?? 0 }}" 
    icon="bx-calendar-minus" 
    color="warning"
    animation-delay="0.3s"
  />
  
  <x-stat-card 
    title="Late Arrivals" 
    value="{{ $lateCount ?? 0 }}" 
    icon="bx-time" 
    color="amber"
    trend="up"
    trendValue="{{ $lateCount }} today"
    animation-delay="0.4s"
  />
</div>



<!-- Attendance Records Table -->
<div class="row g-6 mb-6">
  <div class="col-12">
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
      <div class="hitech-card-header border-bottom">
        <div class="d-flex align-items-center gap-3">
            <h5 class="title mb-0">Attendance Records</h5>
            <span class="badge search-light-badge rounded-pill px-3">Live Log</span>
        </div>
      </div>

      {{-- Unified White Filter Row --}}
      <div class="card-body p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
          {{-- Search --}}
          <div class="search-wrapper-hitech" style="width: 400px;">
            <i class="bx bx-search text-muted ms-3 fs-5"></i>
            <input type="text" class="form-control" placeholder="Search employee..." id="customSearchInput">
            <button class="btn-search shadow-sm" id="customSearchBtn">
              <i class="bx bx-search fs-5"></i>
              <span>Search</span>
            </button>
          </div>

          {{-- Date Filter --}}
          <div style="width: 160px;">
            <input type="date" id="date" name="date" class="form-control filter-item-hitech"
                   value="{{ request()->get('date', now()->format('Y-m-d')) }}">
          </div>

          {{-- Department --}}
          <div class="compact-select">
              <select id="teamId" name="teamId" class="form-select select2 filter-item-hitech">
                <option value="">Dept: All</option>
                @foreach($teams as $team)
                  <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
              </select>
          </div>

          {{-- Employee --}}
          <div class="compact-select" style="min-width: 200px;">
            <select id="userId" name="userId" class="form-select select2 filter-item-hitech">
              <option value="">Emp: All</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request()->get('user') == $user->id ? 'selected' : '' }}>
                  {{ $user->getFullName() }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Shift --}}
          <div class="compact-select">
            <select id="shiftId" name="shiftId" class="form-select select2 filter-item-hitech">
              <option value="">Shift: All</option>
              @foreach($shifts as $shift)
                <option value="{{ $shift->id }}" {{ request()->get('shift') == $shift->id ? 'selected' : '' }}>
                  {{ $shift->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Spacer --}}
          <div class="flex-grow-1"></div>

          {{-- Records Per Page --}}
          <div class="d-flex align-items-center">
            <select class="form-select w-px-70 filter-item-hitech border-light shadow-none fw-bold" id="customLengthMenu">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>

          {{-- Export Icon --}}
          <button type="button" class="btn btn-hitech-icon shadow-sm" onclick="exportData()" title="Export Data">
            <i class="bx bx-download fs-5"></i>
          </button>
        </div>
      </div>

      <div class="card-datatable table-responsive p-0">
        <table id="attendanceTable" class="table table-hover border-top mb-0">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Date</th>
              <th>Shift</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Working Hours</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Full Width Interactive Chart -->
<div class="row g-6">
  <div class="col-12">
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
      <div class="hitech-card-header border-bottom">
        <div class="d-flex align-items-center gap-3">
            <h5 class="title mb-0">Weekly Attendance Analytics</h5>
            <div class="d-flex align-items-center gap-2 ms-4">
                <span class="badge badge-hitech-success rounded-pill px-3">Present</span>
                <span class="badge badge-hitech-danger rounded-pill px-3">Absent</span>
            </div>
        </div>
      </div>
      {{-- Streamlined Chart Filters (Internal) --}}
      <div class="card-body p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
          {{-- Period Filter --}}
          <div class="compact-select" style="min-width: 140px;">
              <select id="chartPeriod" class="form-select select2 filter-item-hitech">
                  <option value="today">Today</option>
                  <option value="yesterday">Yesterday</option>
                  <option value="7days" selected>7 Days</option>
                  <option value="1month">30 Days</option>
                  <option value="3months">3 Months</option>
                  <option value="1year">1 Year</option>
              </select>
          </div>

          {{-- Department Filter --}}
          <div class="compact-select" style="min-width: 160px;">
              <select id="chartTeamFilter" class="form-select select2 filter-item-hitech">
                  <option value="">Dept: All</option>
                  @foreach($teams as $team)
                      <option value="{{ $team->id }}">{{ $team->name }}</option>
                  @endforeach
              </select>
          </div>

          {{-- Employee Filter --}}
          <div class="compact-select" style="min-width: 200px;">
              <select id="chartUserFilter" class="form-select select2 filter-item-hitech">
                  <option value="">Emp: All</option>
                  @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->getFullName() }}</option>
                  @endforeach
              </select>
          </div>

          {{-- Spacer --}}
          <div class="flex-grow-1"></div>

          {{-- Refresh Action --}}
          <button type="button" class="btn btn-hitech-icon shadow-sm" onclick="refreshChart()" title="Refresh Analytics">
            <i class="bx bx-refresh fs-5"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div id="weeklyAttendanceChart" style="min-height: 400px;"></div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('page-script')
@vite([
  'resources/js/main-select2.js',
  'resources/assets/js/app/attendance-index.js'
])
<script>
function exportData() {
  // Export functionality
}

// Weekly Chart
document.addEventListener('DOMContentLoaded', function() {
  const options = {
    series: [{
      name: 'Present',
      data: [44, 55, 41, 67, 22, 43, 65]
    }, {
      name: 'Absent',
      data: [13, 23, 20, 8, 13, 27, 15]
    }],
    chart: {
      height: 400,
      type: 'area',
      toolbar: { 
          show: true,
          tools: {
              download: false,
              selection: true,
              zoom: true,
              zoomin: true,
              zoomout: true,
              pan: true,
              reset: true
          }
      },
      fontFamily: 'Inter, sans-serif'
    },
    colors: ['#005a5a', '#ff4d49'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100]
      }
    },
    grid: {
        borderColor: '#f1f5f9',
        strokeDashArray: 4,
        padding: { bottom: 0 }
    },
    xaxis: {
      categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
          style: { colors: '#64748b', fontSize: '12px' }
      }
    },
    yaxis: {
        labels: {
            style: { colors: '#64748b', fontSize: '12px' }
        }
    },
    tooltip: {
        theme: 'light',
        x: { show: true },
        marker: { show: true }
    },
    legend: { show: false }
  };
  
  window.attendanceChart = new ApexCharts(document.querySelector("#weeklyAttendanceChart"), options);
  window.attendanceChart.render();
});
</script>
@endsection
