@extends('layouts/layoutMaster')

@section('title', 'Super Admin Dashboard')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/scss/pages/hitech-portal.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const orderHistoryChartEl = document.querySelector('#orderHistoryChart');
      const orderHistoryData = @json($orderHistory);
      
      if (orderHistoryChartEl) {
        const options = {
          chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false },
            parentHeightOffset: 0,
            background: 'transparent'
          },
          series: [{
            name: 'Total Amount',
            data: orderHistoryData.map(item => item.total)
          }],
          xaxis: {
            categories: orderHistoryData.map(item => `Month ${item.month}`),
            labels: { style: { colors: '#b6bee3', fontSize: '13px' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
          },
          yaxis: {
            labels: { style: { colors: '#b6bee3', fontSize: '13px' } }
          },
          grid: {
            borderColor: 'rgba(255, 255, 255, 0.1)',
            padding: { top: -20, bottom: -10 }
          },
          colors: ['#00cfe8'],
          fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
          },
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth', width: 3 },
          tooltip: { theme: 'dark' }
        };

        const chart = new ApexCharts(orderHistoryChartEl, options);
        chart.render();
      }
    });
  </script>
@endsection

@section('content')

  <!-- Hero Section -->
  <div class="row mb-4 animate__animated animate__fadeInDown">
    <div class="col-12">
      <div class="admin-hero hitech-card border-0 position-relative overflow-hidden">
        <div class="d-flex align-items-center position-relative z-1 p-4">
          <div class="avatar avatar-xl me-4 border-2 border-white rounded-circle">
             <img src="{{ Auth::user()->profile_photo_url ?? asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle">
          </div>
          <div>
            <h2 class="text-white mb-1 fw-bold">Welcome back, Super Admin! 🚀</h2>
            <p class="text-white opacity-75 mb-0 text-large">Here's what's happening in your system today.</p>
          </div>
        </div>
        <!-- Decorative bg elements -->
        <div class="position-absolute top-0 end-0 h-100 w-50" 
             style="background: linear-gradient(90deg, transparent, rgba(0, 207, 232, 0.1)); clip-path: polygon(20% 0%, 100% 0, 100% 100%, 0% 100%);">
        </div>
      </div>
    </div>
  </div>

  <!-- Overview Cards -->
  <div class="row animate__animated animate__fadeInUp">
    <div class="col-sm-6 col-xl-3 mb-4">
      <div class="hitech-stat-card h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="text-white opacity-75 mb-1">Total Orders</h6>
                <h3 class="text-white fw-bold mb-0">{{ $totalOrders }}</h3>
            </div>
            <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary p-2">
                    <i class="bx bx-cart bx-sm"></i>
                </span>
            </div>
        </div>
        <small class="text-success fw-semibold"><i class='bx bx-check-circle'></i> {{ $completedOrders }} Completed</small>
        <div class="progress mt-3" style="height: 6px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3 mb-4">
      <div class="hitech-stat-card h-100 d-flex flex-column justify-content-between" style="border-left-color: #ffab00 !important;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="text-white opacity-75 mb-1">Pending Requests</h6>
                <h3 class="text-white fw-bold mb-0">{{ $pendingRequests }}</h3>
            </div>
            <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning p-2">
                    <i class="bx bx-time-five bx-sm"></i>
                </span>
            </div>
        </div>
        <small class="text-warning fw-semibold"><i class='bx bx-error'></i> Action Required</small>
         <div class="progress mt-3" style="height: 6px;">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3 mb-4">
      <div class="hitech-stat-card h-100 d-flex flex-column justify-content-between" style="border-left-color: #71dd37 !important;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="text-white opacity-75 mb-1">Active Domains</h6>
                <h3 class="text-white fw-bold mb-0">{{ $activeDomains }}</h3>
            </div>
            <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success p-2">
                    <i class="bx bx-globe bx-sm"></i>
                </span>
            </div>
        </div>
        <small class="text-success fw-semibold"><i class='bx bx-check'></i> Operational</small>
        <div class="progress mt-3" style="height: 6px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3 mb-4">
      <div class="hitech-stat-card h-100 d-flex flex-column justify-content-between" style="border-left-color: #03c3ec !important;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="text-white opacity-75 mb-1">New Customers</h6>
                <h3 class="text-white fw-bold mb-0">{{ $newCustomers }}</h3>
            </div>
            <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info p-2">
                    <i class="bx bx-user-plus bx-sm"></i>
                </span>
            </div>
        </div>
        <small class="text-info fw-semibold"><i class='bx bx-calendar'></i> This Month</small>
        <div class="progress mt-3" style="height: 6px;">
            <div class="progress-bar bg-info" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Order History Graph & Offline Requests -->
  <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
    <!-- Order History Graph -->
    <div class="col-lg-8 mb-4">
      <div class="hitech-card h-100">
        <div class="hitech-card-header">
            <div>
               <h5 class="mb-0 text-white">Order History</h5>
               <small class="text-muted">Monthly Revenue Overview</small>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Last 6 Months</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:void(0);">Last 12 Months</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">Last 30 Days</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
          <div id="orderHistoryChart"></div>
        </div>
      </div>
    </div>

    <!-- Offline Requests -->
    <div class="col-lg-4 mb-4">
      <div class="hitech-card h-100">
        <div class="hitech-card-header">
           <div>
               <h5 class="mb-0 text-white">Offline Requests</h5>
               <small class="text-muted">Pending Approvals</small>
           </div>
           <a href="{{ route('offlineRequests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0 overflow-auto" style="max-height: 400px;">
          @if($offlineRequests->count() > 0)
            <ul class="list-group list-group-flush bg-transparent">
              @foreach($offlineRequests as $request)
                <li class="list-group-item bg-transparent border-bottom border-dark px-4 py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-white d-block">{{ $request->user->name }}</strong>
                        <small class="text-muted">{{ $request->created_at->format('d M, Y') }}</small>
                    </div>
                    <span class="badge bg-label-warning">{{ $request->status }}</span>
                  </div>
                </li>
              @endforeach
            </ul>
          @else
            <div class="text-center p-4">
               <div class="avatar avatar-md bg-label-secondary rounded-circle mx-auto mb-3">
                   <i class="bx bx-check bx-sm"></i>
               </div>
               <h6 class="text-white mb-1">No Pending Requests</h6>
               <small class="text-muted">All caught up!</small>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Domain Requests and Recent Customers -->
  <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
    <!-- Domain Requests -->
    <div class="col-lg-6 mb-4">
      <div class="hitech-card h-100">
        <div class="hitech-card-header">
           <div>
               <h5 class="mb-0 text-white">Domain Requests</h5>
               <small class="text-muted">Pending Domains</small>
           </div>
           <a href="{{ route('domainRequests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0 overflow-auto" style="max-height: 400px;">
          @if($domainRequests->count() > 0)
            <ul class="list-group list-group-flush bg-transparent">
              @foreach($domainRequests as $request)
                <li class="list-group-item bg-transparent border-bottom border-dark px-4 py-3">
                  <div class="d-flex align-items-center">
                     @include('_partials._profile-avatar', ['user' => $request->user])
                     <div class="ms-3 flex-grow-1">
                        <strong class="text-white d-block">{{ $request->name }}</strong>
                        <small class="text-muted">{{ $request->created_at->format('d M, Y') }}</small>
                     </div>
                     <span class="badge bg-label-info">{{ $request->status }}</span>
                  </div>
                </li>
              @endforeach
            </ul>
          @else
            <div class="text-center p-4">
               <div class="avatar avatar-md bg-label-secondary rounded-circle mx-auto mb-3">
                   <i class="bx bx-globe bx-sm"></i>
               </div>
               <h6 class="text-white mb-1">No Pending Domains</h6>
               <small class="text-muted">Everything is running smoothly.</small>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Recent Customers -->
    <div class="col-lg-6 mb-4">
      <div class="hitech-card h-100">
        <div class="hitech-card-header">
           <div>
               <h5 class="mb-0 text-white">Recent Customers</h5>
               <small class="text-muted">Newest Signups</small>
           </div>
           <a href="{{ route('account.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0 overflow-auto" style="max-height: 400px;">
          @if($recentCustomers->count() > 0)
            <ul class="list-group list-group-flush bg-transparent">
              @foreach($recentCustomers as $customer)
                <li class="list-group-item bg-transparent border-bottom border-dark px-4 py-3">
                    <div class="d-flex align-items-center">
                        @include('_partials._profile-avatar', ['user' => $customer])
                        <div class="ms-3 flex-grow-1">
                            <strong class="text-white d-block">{{ $customer->name ?? $customer->email }}</strong>
                            <small class="text-muted">Joined {{ $customer->created_at->format('d M, Y') }}</small>
                        </div>
                        <button class="btn btn-sm btn-icon btn-label-secondary"><i class="bx bx-show"></i></button>
                    </div>
                </li>
              @endforeach
            </ul>
          @else
            <div class="text-center p-4">
               <div class="avatar avatar-md bg-label-secondary rounded-circle mx-auto mb-3">
                   <i class="bx bx-user-x bx-sm"></i>
               </div>
               <h6 class="text-white mb-1">No Recent Customers</h6>
               <small class="text-muted">Quiet day today.</small>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

@endsection
