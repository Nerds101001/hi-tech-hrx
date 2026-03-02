@extends('layouts/layoutMaster')

@section('title', __('Payroll Management'))

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize DataTable for Payroll
      const dt_payroll = $('.datatables-payroll');
      if (dt_payroll.length) {
        dt_payroll.DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('payroll.indexAjax') }}",
          columns: [
            { data: 'id' },
            { data: 'employee_name' },
            { data: 'month' },
            { data: 'net_salary' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
          ],
          dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
          buttons: [
            {
              extend: 'collection',
              className: 'btn btn-label-primary dropdown-toggle me-2',
              text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
              buttons: [
                { extend: 'print', className: 'dropdown-item' },
                { extend: 'csv', className: 'dropdown-item' },
                { extend: 'excel', className: 'dropdown-item' },
                { extend: 'pdf', className: 'dropdown-item' },
                { extend: 'copy', className: 'dropdown-item' }
              ]
            }
          ]
        });
      }
    });
  </script>
@endsection

@section('content')
<div class="row g-6 px-4">
  <!-- Hero Banner -->
  <div class="col-lg-12">
    <x-hero-banner 
      title="Payroll Management" 
      subtitle="Process salaries, management adjustments and generate payslips"
      icon="bx-money"
      gradient="primary"
    />
  </div>

  <!-- Stats Cards -->
  <div class="col-12 mt-4">
    <div class="row g-4">
      <x-stat-card 
        title="Pending Processing" 
        value="0" 
        icon="bx-time" 
        color="warning"
        animation-delay="0.1s"
      />
      <x-stat-card 
        title="Processed This Month" 
        value="0" 
        icon="bx-check-double" 
        color="success"
        animation-delay="0.2s"
      />
      <x-stat-card 
        title="Total Payout" 
        value="$0" 
        icon="bx-wallet" 
        color="info"
        animation-delay="0.3s"
      />
    </div>
  </div>

  <!-- Payroll Table -->
  <div class="col-12 mt-6">
    <div class="hitech-card animate__animated animate__fadeInUp">
      <div class="hitech-card-header border-bottom">
        <h5 class="title mb-0">Payroll List</h5>
      </div>
      <div class="card-datatable table-responsive p-0">
        <table class="datatables-payroll table table-hover border-top mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Employee</th>
              <th>Month</th>
              <th>Net Salary</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
