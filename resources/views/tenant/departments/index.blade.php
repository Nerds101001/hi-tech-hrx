@extends('layouts/layoutMaster')

@section('title', __('Department Management'))

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/apex-charts/apexcharts.js'
  ])
@endsection

@section('page-script')
  @vite(['resources/js/main-datatable.js'])
  @vite(['resources/js/main-helper.js'])
  @vite(['resources/assets/js/app/department-index.js'])
  <style>
    .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
        border-radius: 12px !important;
    }
    .search-light-badge {
        background: rgba(0, 90, 90, 0.08);
        color: #005a5a;
        border: 1px solid rgba(0, 90, 90, 0.1);
    }
    .filter-item-hitech {
        height: 42px !important;
        border-radius: 10px !important;
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
    /* Search Bar Refinement (Pill shape) */
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
        color: #1e293b !important;
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
    /* Stat Card Link Arrow */
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

@section('content')
<div class="row g-6 px-4">
  <!-- Hero Banner -->
  <div class="col-lg-12">
    <x-hero-banner 
      title="Department Management" 
      subtitle="Organize and manage company departments efficiently"
      icon="bx-building"
      gradient="primary"
    />
  </div>

  <!-- Stats Cards -->
  <x-stat-card 
    title="Total Departments" 
    value="{{ $totalDepartments ?? 0 }}" 
    icon="bx-building" 
    color="primary"
    trend="up"
    trendValue="1 new"
    trendLabel="vs last month"
    animation-delay="0.1s"
    link="javascript:void(0)"
  />
  
  <x-stat-card 
    title="Active Departments" 
    value="{{ $activeDepartments ?? 0 }}" 
    icon="bx-check-circle" 
    color="success"
    trend="up"
    trendValue="100%"
    trendLabel="active rate"
    animation-delay="0.2s"
    link="javascript:void(0)"
  />
  
  <x-stat-card 
    title="Total Employees" 
    value="{{ $totalEmployees ?? 0 }}" 
    icon="bx-group" 
    color="info"
    trend="up"
    trendValue="8%"
    trendLabel="growth"
    animation-delay="0.3s"
    link="javascript:void(0)"
  />
  
  <x-stat-card 
    title="Avg Team Size" 
    value="{{ $avgTeamSize ?? 0 }}" 
    icon="bx-user-voice" 
    color="amber"
    trend="down"
    trendValue="2%"
    trendLabel="efficiency"
    animation-delay="0.4s"
    link="javascript:void(0)"
  />

  <!-- Department Table -->
  <div class="col-12 mt-6">
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
      <div class="hitech-card-header border-bottom">
        <div class="d-flex align-items-center gap-3">
            <h5 class="title mb-0">Departments List</h5>
            <span class="badge search-light-badge rounded-pill px-3">Live Map</span>
        </div>
      </div>

      {{-- Unified White Filter Row --}}
      <div class="card-body p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
          {{-- Search --}}
          <div class="search-wrapper-hitech" style="width: 400px;">
            <i class="bx bx-search text-muted ms-3 fs-5"></i>
            <input type="text" class="form-control" placeholder="Search department name or code..." id="customSearchInput">
            <button class="btn-search shadow-sm" id="customSearchBtn">
              <i class="bx bx-search fs-5"></i>
              <span>Search</span>
            </button>
          </div>

          {{-- Spacer --}}
          <div class="flex-grow-1"></div>

          {{-- Records Per Page --}}
          <div class="d-flex align-items-center">
            <select class="form-select w-px-70 filter-item-hitech border-light shadow-none" id="customLengthMenu">
                <option value="7">7</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
          </div>

          {{-- Add New Action --}}
          <button type="button" class="btn btn-hitech shadow-sm px-4 add-new-department" data-bs-toggle="modal"
                  data-bs-target="#modalAddOrUpdateDepartment">
            <i class="bx bx-plus me-1"></i>Add Department
          </button>

          {{-- Export Icon --}}
          <button type="button" class="btn btn-hitech-icon shadow-sm" onclick="exportDepartments()" title="Export Data">
            <i class="bx bx-download fs-5"></i>
          </button>
        </div>
      </div>
      
      @include('_partials._loaders.center_loader')
      
      <div class="card-datatable table-responsive p-0" style="display: none;">
        <table class="datatables-departments table table-hover border-top mb-0">
          <thead>
            <tr>
              <th></th>
              <th>Id</th>
              <th>Department Name</th>
              <th>Code</th>
              <th>Parent Department</th>
              <th>Description</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

@include('_partials._modals.departments.add_or_update_departments')

<script>
function exportDepartments() {
  // Export department data
}

// Department Distribution Chart
document.addEventListener('DOMContentLoaded', function() {
  const options = {
    series: [{
      name: 'Employees',
      data: [45, 32, 28, 25, 22, 18, 15, 12]
    }],
    chart: {
      height: 300,
      type: 'bar',
      toolbar: { show: false },
      fontFamily: 'Inter, sans-serif'
    },
    colors: ['#005a5a'],
    plotOptions: {
      bar: {
        borderRadius: 8,
        horizontal: false,
        columnWidth: '60%',
        distributed: true
      }
    },
    dataLabels: {
      enabled: true,
      formatter: function(val) {
        return val + ' employees';
      },
      offsetY: -6,
      style: {
        fontSize: '12px',
        colors: ["#373d3f"]
      }
    },
    xaxis: {
      categories: ['Engineering', 'Sales', 'Marketing', 'HR', 'Finance', 'Operations', 'IT', 'Admin']
    },
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'light',
        type: "vertical",
        shadeIntensity: 0.5,
        gradientToColors: ['#008080'],
        stops: [0, 100]
      }
    }
  };
  
  const chart = new ApexCharts(document.querySelector("#departmentChart"), options);
  chart.render();
});
</script>
@endsection
