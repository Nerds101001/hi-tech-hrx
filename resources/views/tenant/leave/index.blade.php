@php use App\Enums\LeaveRequestStatus; @endphp
@extends('layouts/layoutMaster')

@section('title', __('Leave Management'))

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

    /* Pagination Override - NO BLUE */
    .page-item.active .page-link {
        background-color: #005a5a !important;
        border-color: #005a5a !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(0, 90, 90, 0.2);
    }
    .page-link {
        color: #005a5a !important;
        border-radius: 8px !important;
        margin: 0 3px;
    }
    .page-link:hover {
        background: rgba(0, 90, 90, 0.05) !important;
        color: #004d4d !important;
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

    /* Button Global Overrides - NO BLUE */
    .btn-primary {
        background-color: #005a5a !important;
        border-color: #005a5a !important;
        color: #fff !important;
    }
    .btn-primary:hover {
        background-color: #004d4d !important;
        border-color: #004d4d !important;
    }
    .btn-outline-primary {
        color: #005a5a !important;
        border-color: #005a5a !important;
    }
    .btn-outline-primary:hover {
        background-color: #005a5a !important;
        color: #fff !important;
    }

    /* Custom DataTables Processing (Loading) Indicator - NO BLUE dots */
    .dataTables_processing {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(0, 90, 90, 0.1) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        border-radius: 12px !important;
        color: #005a5a !important;
        padding: 2rem !important;
        z-index: 9999 !important;
    }
    
    /* Targets ALL potential loader dots/spinners inside processing div */
    .dataTables_processing div, 
    .dataTables_processing span,
    .dataTables_processing .sk-primary div,
    .dataTables_processing .sk-wave div,
    .dataTables_processing .sk-bounce div,
    .dataTables_processing .sk-circle::before,
    .dataTables_processing .spinner-border,
    .dataTables_processing .spinner-grow {
        background-color: #005a5a !important;
        color: #005a5a !important;
    }

    /* Fixed dots from the user screenshot */
    .dataTables_processing .sk-chase-dot:before,
    .dataTables_processing .sk-pulse,
    .dataTables_processing .sk-swing-dot,
    .dataTables_processing .sk-circle-dot:before {
        background-color: #005a5a !important;
    }
    
    /* Paging and other primary blue overrides */
    .pagination .page-item.active .page-link {
        background-color: #005a5a !important;
        border-color: #005a5a !important;
    }
    .text-primary { color: #005a5a !important; }
    .bg-primary { background-color: #005a5a !important; }
  </style>
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
  @vite(['resources/assets/js/app/leave-requests-index.js'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"/>
  <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
@endsection

@section('content')
<div class="px-4">
  <div class="row g-6 mb-6">
    <!-- Hero Banner -->
    <div class="col-lg-12">
      <x-hero-banner 
        title="Leave Management" 
        subtitle="Manage employee leave requests and balances efficiently"
        icon="bx-calendar"
        gradient="teal"
      />
    </div>
  </div>

  <!-- Stats Cards -->
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
      icon="bx-check-circle" 
      color="success"
      animation-delay="0.2s"
    />
    
    <x-stat-card 
      title="On Leave Now" 
      value="{{ $onLeaveNow ?? 0 }}" 
      icon="bx-calendar-minus" 
      color="info"
      animation-delay="0.3s"
    />
    
    <x-stat-card 
      title="Leave Balance" 
      value="{{ $totalLeaveBalance ?? 0 }}" 
      icon="bx-wallet" 
      color="primary"
      animation-delay="0.4s"
    />
  </div>



  <!-- Leave Requests Table Section -->
  <div class="row g-6 mb-6">
    <div class="col-12">
      <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
        <div class="hitech-card-header border-bottom">
          <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-4">
                <h5 class="title mb-0">Leave Management</h5>
                
                {{-- Hitech Segmented Toggle --}}
                <div class="hitech-segmented-control bg-light-soft rounded-pill p-1 d-flex" style="border: 1px solid rgba(0, 90, 90, 0.1);">
                    <button type="button" class="btn btn-sm rounded-pill px-4 status-toggle-btn active" data-status="pending">
                        <i class="bx bx-time-five me-1"></i>Pending
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-4 status-toggle-btn" data-status="approved">
                        <i class="bx bx-check-double me-1"></i>Approved
                    </button>
                </div>
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-primary" onclick="approveSelected()">
                <i class="bx bx-check me-1"></i>Approve Selected
              </button>
              <button type="button" class="btn btn-sm btn-danger" onclick="rejectSelected()">
                <i class="bx bx-x me-1"></i>Reject Selected
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
              <input type="text" class="form-control" placeholder="Search requests..." id="customSearchInput">
              <button class="btn-search shadow-sm" id="customSearchBtn">
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

            {{-- Leave Type Filter --}}
            <div class="compact-select">
              <select id="leaveTypeFilter" name="leaveTypeFilter" class="form-select select2 filter-item-hitech">
                <option value="">Type: All</option>
                @foreach($leaveTypes as $leaveType)
                  <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                @endforeach
              </select>
            </div>

            {{-- Hidden Status Filter (Controlled by toggle) --}}
            <input type="hidden" id="statusFilter" value="pending">

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
          </div>
        </div>

        <div class="card-datatable table-responsive p-0">
          <table class="datatables-leaveRequests table table-hover border-top mb-0">
            <thead>
              <tr>
                <th></th> <!-- Control -->
                <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                <th>SR No</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Leave Type</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Days</th>
                <th>Reason</th>
                <th class="status-col">Status</th>
                <th>Attachment</th>
                <th class="action-col">Actions</th>
                <th class="approved-by-col" style="display:none;">Approved By</th>
                <th class="approved-at-col" style="display:none;">Approved At</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Analytics Section (Chart) at Bottom -->
  <div class="row g-6">
    <div class="col-12">
      <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
        <div class="hitech-card-header border-bottom">
          <div class="d-flex align-items-center gap-3">
              <h5 class="title mb-0">Leave Balance Overview</h5>
              <div class="d-flex align-items-center gap-2 ms-4">
                  <span class="badge badge-hitech-success rounded-pill px-3">Available</span>
                  <span class="badge border rounded-pill px-3" style="background: rgba(100,116,139,0.08); color: #64748b;">Used</span>
              </div>
          </div>
        </div>
        <div class="card-body">
          <div id="leaveBalanceChart" style="min-height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('_partials._modals.leave.leave_request_details')

<script>
function filterLeaveRequests() {
  const employee = document.getElementById('employeeFilter').value;
  const date = document.getElementById('dateFilter').value;
  const leaveType = document.getElementById('leaveTypeFilter').value;
  const status = document.getElementById('statusFilter').value;
  
  const url = new URL(window.location.href);
  if (employee) url.searchParams.set('employee', employee);
  if (date) url.searchParams.set('date', date);
  if (leaveType) url.searchParams.set('leaveType', leaveType);
  if (status) url.searchParams.set('status', status);
  
  window.location.href = url.toString();
}

function getSelectedIds() {
  const selected = [];
  $('.dt-checkboxes:checked').each(function() {
    selected.push($(this).val());
  });
  return selected;
}

function processBulkAction(status) {
  const ids = getSelectedIds();
  if (ids.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Warning',
      text: 'Please select at least one leave request',
      customClass: { confirmButton: 'btn btn-primary' }
    });
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: `You want to mark ${ids.length} request(s) as ${status}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, proceed!',
    cancelButtonText: 'Cancel',
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: '{{ route("leaveRequests.bulkActionAjax") }}',
        type: 'POST',
        data: {
          ids: ids,
          status: status,
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: response.message,
              customClass: { confirmButton: 'btn btn-success' }
            });
            $('.datatables-leaveRequests').DataTable().ajax.reload();
            $('#selectAll').prop('checked', false);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: response.message,
              customClass: { confirmButton: 'btn btn-danger' }
            });
          }
        },
        error: function(err) {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong',
            customClass: { confirmButton: 'btn btn-danger' }
          });
        }
      });
    }
  });
}

function approveSelected() {
  processBulkAction('approved');
}

function rejectSelected() {
  processBulkAction('rejected');
}

// Select All Checkbox Logic
document.addEventListener('DOMContentLoaded', function() {
  $('#selectAll').on('change', function() {
    $('.dt-checkboxes').prop('checked', $(this).prop('checked'));
  });
  
  // Also uncheck selectAll if any individual checkbox is unchecked
  $(document).on('change', '.dt-checkboxes', function() {
    if ($('.dt-checkboxes:checked').length === $('.dt-checkboxes').length) {
      $('#selectAll').prop('checked', true);
    } else {
      $('#selectAll').prop('checked', false);
    }
  });
});

// Leave Balance Chart
document.addEventListener('DOMContentLoaded', function() {
  const options = {
    series: [{
      name: 'Entitled/Available',
      data: [15, 12, 10, 14, 20, 10, 8]
    }, {
      name: 'Used To Date',
      data: [3, 2, 4, 1, 5, 2, 0]
    }],
    chart: {
      height: 400,
      type: 'bar',
      stacked: true,
      toolbar: { show: false },
      fontFamily: 'Outfit, sans-serif'
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '20%',
        borderRadius: 8,
        dataLabels: { position: 'center' },
      }
    },
    colors: ['#005a5a', '#f1f5f9'],
    dataLabels: {
      enabled: true,
      formatter: function(val) { return val > 0 ? val : '' },
      style: {
        fontSize: '10px',
        fontWeight: 600,
        colors: ['#fff', '#64748b']
      }
    },
    grid: {
        borderColor: '#f1f5f9',
        strokeDashArray: 4,
        padding: { top: 10, right: 0, bottom: 0, left: 10 }
    },
    xaxis: {
      categories: ['Sick', 'Casual', 'Annual', 'Mat.', 'Pat.', 'Study', 'Unpaid'],
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
          style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 500 }
      }
    },
    yaxis: {
        labels: {
            style: { colors: '#475569', fontSize: '12px', fontWeight: 600 }
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        theme: 'light',
        shared: true,
        intersect: false,
        y: {
            formatter: function(val) { return val + " Days" }
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'right',
        fontSize: '13px',
        fontWeight: 600,
        markers: { radius: 12, width: 8, height: 8 },
        itemMargin: { horizontal: 15, vertical: 0 }
    }
  };
  
  window.leaveChart = new ApexCharts(document.querySelector("#leaveBalanceChart"), options);
  window.leaveChart.render();
});
</script>
@endsection
