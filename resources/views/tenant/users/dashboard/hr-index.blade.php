@extends('layouts/layoutMaster')

@section('title', __('HR Management Hub'))

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/select2/select2.full.min.js',
    'resources/assets/vendor/libs/apex-charts/apex-charts.min.js',
    'resources/assets/vendor/js/bootstrap.js',
  ])
@endsection

@section('content')

<div class="emp-hub-wrapper">

  {{-- ============================================================ --}}
  {{-- HERO SECTION                                                  --}}
  {{-- ============================================================ --}}
  <div class="emp-hero animate__animated animate__fadeIn">
    <div class="emp-hero-text">
      <div class="greeting">
        Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
        {{ auth()->user()->first_name }}! 👋
      </div>
      <div class="date-badge mt-2">
        <i class="bx bx-calendar" style="font-size:0.85rem;"></i>
        {{ now()->format('l, F jS') }}
      </div>
    </div>
    <div class="emp-hero-meta">
      <div class="hero-quick-stat">
        <div class="stat-value">{{ $totalUser }}</div>
        <div class="stat-label">Total Staff</div>
      </div>
      <div class="hero-quick-stat">
        <div class="stat-value">{{ $todayPresentUsers }}</div>
        <div class="stat-label">Present Now</div>
      </div>
      <div class="hero-quick-stat">
        <div class="stat-value">{{ $onLeaveUsersCount }}</div>
        <div class="stat-label">On Leave</div>
      </div>
    </div>
  </div>

  <div class="row g-4">

    {{-- ============================================================ --}}
    {{-- LEFT: STAT CARDS + CHARTS                                     --}}
    {{-- ============================================================ --}}
    <div class="col-xl-8">
      <div class="row g-4 mb-4">
        <!-- Stat Cards -->
        <div class="col-md-6 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay:0.05s">
          <div class="hitech-stat-card dashboard-variant card-teal">
            <div class="stat-card-header">
              <div class="stat-icon-wrap icon-teal"><i class="bx bx-group"></i></div>
              <a href="{{ route('employees.index') }}" class="stat-card-link"><i class="bx bx-right-arrow-alt"></i></a>
            </div>
            <div>
              <div class="stat-card-label">Total Employees</div>
              <div class="stat-card-value">{{ $totalUser }}</div>
              <div class="stat-card-sub">+12% vs last month</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay:0.1s">
          <div class="hitech-stat-card dashboard-variant card-blue">
            <div class="stat-card-header">
              <div class="stat-icon-wrap icon-blue"><i class="bx bx-user-check"></i></div>
              <a href="{{ route('attendance.index') }}" class="stat-card-link"><i class="bx bx-right-arrow-alt"></i></a>
            </div>
            <div>
              <div class="stat-card-label">Present Today</div>
              <div class="stat-card-value">{{ $todayPresentUsers }}</div>
              <div class="stat-card-sub">+8% vs last week</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay:0.15s">
          <div class="hitech-stat-card dashboard-variant card-amber">
            <div class="stat-card-header">
              <div class="stat-icon-wrap icon-amber"><i class="bx bx-calendar-x"></i></div>
              <a href="{{ route('leaveRequests.index') }}" class="stat-card-link"><i class="bx bx-right-arrow-alt"></i></a>
            </div>
            <div>
              <div class="stat-card-label">On Leave Today</div>
              <div class="stat-card-value">{{ $onLeaveUsersCount }}</div>
              <div class="stat-card-sub">-3% vs last week</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay:0.2s">
          <div class="hitech-stat-card dashboard-variant card-red">
            <div class="stat-card-header">
              <div class="stat-icon-wrap icon-red"><i class="bx bx-user-minus"></i></div>
              <a href="{{ route('attendance.index') }}" class="stat-card-link"><i class="bx bx-right-arrow-alt"></i></a>
            </div>
            <div>
              <div class="stat-card-label">Absent Today</div>
              <div class="stat-card-value">{{ $todayAbsentUsers }}</div>
              <div class="stat-card-sub">+5% vs last week</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Requests Summary Card -->
      <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay:0.25s">
        <div class="hitech-card-header">
          <h5 class="title">Pending Operational Requests</h5>
        </div>
        <div class="p-4 pt-2">
          <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
              <a href="{{ route('leaveRequests.index') }}" class="text-decoration-none">
                <div class="stat-card-value {{ $pendingLeaveRequests > 0 ? 'text-danger' : 'text-muted' }} mb-1" style="font-size: 1.8rem;">
                  {{ $pendingLeaveRequests }}
                </div>
                <div class="stat-card-label" style="font-size: 0.8rem;">Leaves</div>
              </a>
            </div>
            <div class="col-6 col-md-3">
              <a href="{{ route('expenseRequests.index') }}" class="text-decoration-none">
                <div class="stat-card-value {{ $pendingExpenseRequests > 0 ? 'text-warning' : 'text-muted' }} mb-1" style="font-size: 1.8rem;">
                  {{ $pendingExpenseRequests }}
                </div>
                <div class="stat-card-label" style="font-size: 0.8rem;">Expenses</div>
              </a>
            </div>
            <div class="col-6 col-md-3">
              <a href="{{ route('documentmanagement.index') }}" class="text-decoration-none">
                <div class="stat-card-value {{ $pendingDocumentRequests > 0 ? 'text-info' : 'text-muted' }} mb-1" style="font-size: 1.8rem;">
                  {{ $pendingDocumentRequests }}
                </div>
                <div class="stat-card-label" style="font-size: 0.8rem;">Documents</div>
              </a>
            </div>
            <div class="col-6 col-md-3">
              <a href="{{ route('loan.index') }}" class="text-decoration-none">
                <div class="stat-card-value {{ $pendingLoanRequests > 0 ? 'text-success' : 'text-muted' }} mb-1" style="font-size: 1.8rem;">
                  {{ $pendingLoanRequests }}
                </div>
                <div class="stat-card-label" style="font-size: 0.8rem;">Loans</div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Productivity Chart -->
      <div class="hitech-card mt-4 animate__animated animate__fadeInUp" style="animation-delay:0.3s">
        <div class="hitech-card-header">
          <h5 class="title">Weekly Productivity Overview</h5>
        </div>
        <div class="p-3">
          <div id="productivityChart" style="height: 300px;"></div>
        </div>
      </div>
    </div>

    {{-- ============================================================ --}}
    {{-- RIGHT: QUICK ACTIONS + AVAILABILITY                             --}}
    {{-- ============================================================ --}}
    <div class="col-xl-4">
      <!-- Quick Actions -->
      <div class="hitech-card mb-4 animate__animated animate__fadeInRight" style="animation-delay:0.1s">
        <div class="hitech-card-header">
          <h5 class="title">Quick Actions</h5>
        </div>
        <div class="p-4">
          <div class="d-grid gap-3">
            <a href="{{ route('employees.index') }}" class="btn btn-hitech">
              <i class="bx bx-user-plus me-2"></i>Add Employee
            </a>
            <a href="{{ route('leaveRequests.index') }}" class="btn btn-label-info btn-hitech-alt">
              <i class="bx bx-calendar me-2"></i>Manage Leave
            </a>
            <a href="{{ route('expenseRequests.index') }}" class="btn btn-label-warning btn-hitech-alt">
              <i class="bx bx-wallet me-2"></i>Process Expenses
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-label-success btn-hitech-alt">
              <i class="bx bx-refresh me-2"></i>Lifecycle Management
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-label-secondary btn-hitech-alt">
              <i class="bx bx-file-blank me-2"></i>Generate Reports
            </a>
          </div>
        </div>
      </div>

      <!-- Company Availability -->
      <div class="hitech-card animate__animated animate__fadeInRight" style="animation-delay:0.2s">
        <div class="hitech-card-header">
          <h5 class="title">Status: Out Today</h5>
          <span class="badge bg-label-primary rounded-pill">{{ $teamOutToday->count() }} Employees</span>
        </div>
        <div class="p-0">
          <div class="table-responsive">
            <table class="table table-hitech mb-0">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Type</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($teamOutToday->take(6) as $request)
                  <tr>
                    <td class="fw-bold text-heading">{{ $request->user->name ?? 'N/A' }}</td>
                    <td><small class="text-muted">{{ $request->leave_type->name ?? 'N/A' }}</small></td>
                    <td><span class="badge bg-label-warning badge-hitech">On Leave</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <div class="text-muted mb-2"><i class="bx bx-check-circle" style="font-size: 2rem;"></i></div>
                      <div class="small fw-bold">Everyone is present today!</div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Dept Distribution -->
      <div class="hitech-card mt-4 animate__animated animate__fadeInRight" style="animation-delay:0.3s">
        <div class="hitech-card-header">
          <h5 class="title">Dept. Distribution</h5>
        </div>
        <div class="p-3">
          <div id="departmentChart" style="height: 250px;"></div>
        </div>
      </div>
    </div>

  </div>

</div>

<style>
  .btn-hitech-alt {
    border-radius: 50px;
    padding: 0.6rem 1.5rem;
    font-size: 0.85rem;
    font-weight: 700;
    text-align: left;
    display: flex;
    align-items: center;
  }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Productivity Overview Chart
  const productivityChart = new ApexCharts(document.querySelector("#productivityChart"), {
    series: [{
      name: 'Working Hours',
      data: [42, 38, 45, 40, 48, 52, 41]
    }, {
      name: 'Efficiency',
      data: [85, 88, 82, 90, 87, 92, 89]
    }],
    chart: {
      height: 300,
      type: 'area',
      fontFamily: 'Public Sans',
      toolbar: { show: false },
    },
    colors: ['#005a5a', '#00c9a7'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
      }
    },
    legend: { position: 'top', horizontalAlign: 'right' },
    xaxis: {
      categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      axisBorder: { show: false },
    },
  });

  productivityChart.render();

  // Department Distribution Chart
  const departmentChart = new ApexCharts(document.querySelector("#departmentChart"), {
    series: [44, 55, 41, 17, 15],
    chart: {
      height: 250,
      type: 'donut',
      fontFamily: 'Public Sans',
    },
    labels: ['Eng.', 'Sales', 'Mkt.', 'Supp.', 'HR'],
    colors: ['#005a5a', '#00c9a7', '#ffab00', '#dc2626', '#696cff'],
    legend: { position: 'bottom' },
    plotOptions: {
      pie: {
        donut: {
          size: '75%',
          labels: {
            show: true,
            total: {
              show: true,
              label: 'Total',
              fontSize: '0.8rem',
              fontWeight: 600,
              color: '#94a3b8'
            }
          }
        }
      }
    },
    dataLabels: { enabled: false }
  });

  departmentChart.render();
});
</script>
@endpush
