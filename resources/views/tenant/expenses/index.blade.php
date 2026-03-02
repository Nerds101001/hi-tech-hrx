@php use App\Enums\ExpenseRequestStatus; @endphp
@extends('layouts/layoutMaster')

@section('title', __('Expense Management'))

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    .btn:not(.rounded-pill), .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
        border-radius: 10px;
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

    /* Toggle Control Styling */
    .hitech-segmented-control .status-toggle-btn {
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        font-size: 0.72rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hitech-segmented-control .status-toggle-btn.active {
        background: #fff !important;
        color: #005a5a !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }
    .hitech-segmented-control .status-toggle-btn:not(.active):hover {
        color: #1e293b;
        background: rgba(0,0,0,0.02);
    }
    .hitech-segmented-control {
        border: 1px solid rgba(0,0,0,0.04);
        padding: 4px !important;
    }
  </style>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"/>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/apex-charts/apexcharts.js'
  ])
@endsection

@section('page-script')
  <script>
    const currencySymbol = @json($settings->currency_symbol);
  </script>
  @vite(['resources/assets/js/app/expense-requests-index.js'])
  <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
@endsection

@section('content')
<div class="px-4">
  <div class="row g-6 mb-6">
    <!-- Hero Banner -->
    <div class="col-lg-12">
      <x-hero-banner 
        title="Expense Management" 
        subtitle="Track and finalize employee reimbursement requests efficiently"
        icon="bx-receipt"
        gradient="emerald"
      />
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="row g-6 mb-6">
    <x-stat-card 
      title="Pending Requests" 
      value="{{ $pendingRequests ?? 0 }}" 
      icon="bx-time" 
      color="warning"
      animation-delay="0.1s"
    />
    
    <x-stat-card 
      title="Approved Today" 
      value="{{ $approvedToday ?? 0 }}" 
      icon="bx-check-double" 
      color="success"
      animation-delay="0.2s"
    />
    
    <x-stat-card 
      title="Monthly Distributed" 
      value="{{ $settings->currency_symbol }}{{ number_format($totalThisMonthAmount, 0) }}" 
      icon="bx-wallet" 
      color="primary"
      animation-delay="0.3s"
    />
    
    <x-stat-card 
      title="Pending Amount" 
      value="{{ $settings->currency_symbol }}{{ number_format($pendingAmount, 0) }}" 
      icon="bx-money" 
      color="danger"
      animation-delay="0.4s"
    />
  </div>

  <!-- Expense Requests Table Section -->
  <div class="row g-6 mb-6">
    <div class="col-12">
      <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
        <div class="hitech-card-header border-bottom">
          <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-4">
                <h5 class="title mb-0">Reimbursement Requests</h5>
                
                {{-- Hitech Segmented Toggle --}}
                <div class="hitech-segmented-control bg-light-soft rounded-pill p-1 d-flex" style="border: 1px solid rgba(0, 90, 90, 0.1);">
                    <button type="button" class="btn btn-sm rounded-pill px-4 status-toggle-btn active" data-status="pending">
                        <i class="bx bx-time-five me-1"></i>Pending
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-4 status-toggle-btn" data-status="approved">
                        <i class="bx bx-history me-1"></i>History
                    </button>
                </div>
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-primary" onclick="approveSelected()">
                <i class="bx bx-check me-1"></i>Approve Selected
              </button>
            </div>
          </div>
        </div>

        {{-- Integrated Filter Row --}}
        <div class="card-body p-4 border-bottom">
          <div class="d-flex flex-wrap align-items-center gap-3">
            {{-- Search --}}
            <div class="search-wrapper-hitech" style="width: 400px;">
              <i class="bx bx-search text-muted ms-3 fs-5"></i>
              <input type="text" class="form-control" placeholder="Search expenses..." id="customSearchInput">
              <button class="btn-search shadow-sm" id="btnSearch">
                <i class="bx bx-search fs-5"></i>
                <span>Search</span>
              </button>
            </div>

            {{-- Date Filter --}}
            <div style="width: 160px;">
              <input type="date" id="dateFilter" name="dateFilter" class="form-control filter-item-hitech">
            </div>

            {{-- Employee Filter --}}
            <div class="compact-select" style="min-width: 200px;">
              <select id="employeeFilter" name="employeeFilter" class="form-select select2 filter-item-hitech">
                <option value="">Emp: All</option>
                @foreach($employees as $employee)
                  <option value="{{ $employee->id }}">{{ $employee->getFullName() }}</option>
                @endforeach
              </select>
            </div>

            {{-- Expense Type Filter --}}
            <div class="compact-select">
              <select id="expenseTypeFilter" name="expenseTypeFilter" class="form-select select2 filter-item-hitech">
                <option value="">Type: All</option>
                @foreach($expenseTypes as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>

            {{-- Hidden Status Filter (Controlled by toggle) --}}
            <input type="hidden" id="statusFilter" value="pending">

            {{-- Spacer --}}
            <div class="flex-grow-1"></div>

            {{-- Records Per Page --}}
            <div class="d-flex align-items-center me-2">
              <select class="form-select w-px-70 filter-item-hitech border-light shadow-none fw-bold" id="customLengthMenu">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>

            {{-- Export --}}
            <div class="dropdown">
                <button class="btn btn-hitech-export dropdown-toggle hide-arrow shadow-sm px-4" data-bs-toggle="dropdown">
                    <i class="bx bx-export me-1"></i> EXPORT
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                    <a class="dropdown-item py-2" href="javascript:void(0);"><i class="bx bxs-file-pdf me-2 text-danger"></i>Download PDF</a>
                    <a class="dropdown-item py-2" href="javascript:void(0);"><i class="bx bxs-spreadsheet me-2 text-success"></i>Export Excel</a>
                </div>
            </div>
          </div>
        </div>

        <div class="card-datatable table-responsive p-0">
          <table class="datatables-expenseRequests table table-hover border-top mb-0">
            <thead>
              <tr>
                <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                <th class="text-uppercase text-muted small fw-bolder">SR No</th>
                <th class="text-uppercase text-muted small fw-bolder">Employee</th>
                <th class="text-uppercase text-muted small fw-bolder">Type</th>
                <th class="text-uppercase text-muted small fw-bolder">Date</th>
                <th class="text-uppercase text-muted small fw-bolder">Amount</th>
                <th class="text-uppercase text-muted small fw-bolder text-center">Receipt</th>
                <th class="text-uppercase text-muted small fw-bolder status-col">Status</th>
                <th class="text-uppercase text-muted small fw-bolder text-center action-col">Actions</th>
                <th class="text-uppercase text-muted small fw-bolder approved-by-col d-none">Approved By</th>
                <th class="text-uppercase text-muted small fw-bolder approved-at-col d-none">Approved At</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Analytics Section (Chart) -->
  <div class="row g-6">
    <div class="col-12">
      <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
        <div class="hitech-card-header border-bottom">
          <div class="d-flex align-items-center gap-3">
              <h5 class="title mb-0">Expense Analysis (Last 6 Months)</h5>
              <div class="d-flex align-items-center gap-2 ms-4">
                  <span class="badge badge-hitech-success rounded-pill px-3">Payouts</span>
              </div>
          </div>
        </div>
        <div class="card-body">
          <div id="expenseTrendChart" style="min-height: 350px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('_partials._modals.expense.expense_request_details')

@endsection
