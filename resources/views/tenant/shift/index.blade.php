@php
    // $configData = Helper::appClasses(); // Only needed if layoutMaster requires it
@endphp

@extends('layouts.layoutMaster')

@section('title', __('Shifts Management'))

@section('vendor-style')
    @vite([
        'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', // Keep if using export buttons
        'resources/assets/vendor/libs/animate-css/animate.scss',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
        'resources/assets/vendor/libs/flatpickr/flatpickr.scss', // For time pickers in modal
        'resources/assets/vendor/libs/select2/select2.scss', // Needed if adding filters later
        'resources/assets/vendor/scss/pages/hitech-portal.scss'
    ])
@endsection

@section('vendor-script')
    @vite([
        'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
        'resources/assets/vendor/libs/flatpickr/flatpickr.js', // For time pickers in modal
        'resources/assets/vendor/libs/select2/select2.js', // Needed if adding filters later
    ])
@endsection

@section('page-style')
    <style>
        /* Ensure toggle switches align nicely in table cells */
        .datatables-shifts .form-check.form-switch {
            display: flex;
            justify-content: center;
        }

        /* Ensure Select2 dropdown appears over offcanvas */
        .select2-container--open {
            z-index: 1090;
        }
        
        .btn, .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
            border-radius: 10px !important;
        }
    </style>
@endsection

@section('page-script')
    <script>
        // Pass URLs and CSRF token using standardized route names
        const shiftListAjaxUrl = "{{ route('shifts.listAjax') }}"
        const shiftStoreUrl = "{{ route('shifts.store') }}"
        const shiftBaseUrl =
            "{{ url('shifts') }}" // Base URL for /shifts/{id}/edit, /shifts/{id} (PUT/DELETE), /shifts/{id}/toggle-status
        const csrfToken = "{{ csrf_token() }}"
    </script>
    @vite(['resources/assets/js/app/shift-index.js']) {{-- Link to the refactored JS file --}}
@endsection


@section('content')
<div class="row g-6 px-4">
  {{-- Hero Banner --}}
  <div class="col-lg-12">
    <x-hero-banner
      title="Shift Management"
      subtitle="Configure and allocate working shifts for your workforce"
      icon="bx-time-five"
      gradient="primary"
    />
  </div>

  {{-- Stats Cards --}}
  <x-stat-card
    title="Total Shifts"
    value="{{ $stats['total'] }}"
    icon="bx-list-ul"
    color="primary"
    animation-delay="0.1s"
  />

  <x-stat-card
    title="Active Shifts"
    value="{{ $stats['active'] }}"
    icon="bx-check-circle"
    color="success"
    animation-delay="0.2s"
  />

  <x-stat-card
    title="Inactive"
    value="{{ $stats['inactive'] }}"
    icon="bx-x-circle"
    color="secondary"
    animation-delay="0.3s"
  />

  {{-- Main Content Table --}}
  <div class="col-12">
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
      <div class="hitech-card-header p-sm-5 p-4 border-bottom">
        <div class="row align-items-center g-4">
          <div class="col-md-7 d-flex align-items-center gap-3">
            <div class="search-wrapper-hitech w-px-400">
              <i class="bx bx-search text-muted ms-3"></i>
              <input type="text" class="form-control" placeholder="Search shifts name or code..." id="customSearchInput">
              <button class="btn-search" id="customSearchBtn">
                <i class="bx bx-search fs-5"></i>
              </button>
            </div>
            <div class="segmented-control-hitech shadow-sm p-1 d-flex gap-1 bg-light rounded-pill ms-2">
              <input type="radio" name="statusFilter" value="All" id="status_all" checked>
              <label for="status_all" class="control-label px-4 py-1 rounded-pill mb-0 pointer fw-semibold small">All</label>

              <input type="radio" name="statusFilter" value="active" id="status_active">
              <label for="status_active" class="control-label px-4 py-1 rounded-pill mb-0 pointer fw-semibold small text-success">Active</label>

              <input type="radio" name="statusFilter" value="inactive" id="status_inactive">
              <label for="status_inactive" class="control-label px-4 py-1 rounded-pill mb-0 pointer fw-semibold small text-secondary">Inactive</label>
            </div>
          </div>
          <div class="col-md-5 d-flex align-items-center justify-content-end gap-3">
            <button type="button" class="btn btn-hitech add-new px-4 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAddOrUpdateShift">
              <i class="bx bx-plus-circle fs-5"></i>
              <span>@lang('Add New Shift')</span>
            </button>
          </div>
        </div>
      </div>

      <div class="card-datatable table-responsive p-0">
        <table class="datatables-shifts table table-hover border-top mb-0">
          <thead>
            <tr>
              <th>@lang('Id')</th>
              <th>@lang('Name')</th>
              <th>@lang('Code')</th>
              <th>@lang('Shift Days')</th>
              <th>@lang('Status')</th>
              <th>@lang('Actions')</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

    {{-- Include the Offcanvas partial --}}
    @include('_partials._modals.shift.add_or_update_shift') {{-- Ensure path is correct --}}

@endsection
