@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Leave Types'))

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    .btn, .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
        border-radius: 10px !important;
    }
  </style>
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection

@section('page-script')
  @vite(['resources/js/main-datatable.js'])
  @vite(['resources/js/main-helper.js'])
  @vite(['resources/assets/js/app/leave-type-index.js'])
@endsection


@section('content')
<div class="px-4">
  {{-- HERO SECTION --}}
  <div class="hitech-page-hero animate__animated animate__fadeIn">
      <div class="hitech-page-hero-text">
          <div class="greeting">@lang('Leave Type Management')</div>
          <div class="sub-text">Define and manage different types of leave available to employees.</div>
      </div>
      <div>
          <button type="button" class="btn btn-hitech add-new" data-bs-toggle="modal" data-bs-target="#modalAddOrUpdateLeaveType">
              <i class="bx bx-plus-circle me-2"></i> @lang('Add New Leave Type')
          </button>
      </div>
  </div>

  <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
    <div class="hitech-card-header">
      <h5 class="title">Leave Types List</h5>
    </div>
    <div class="card-datatable table-responsive p-0">
      <table class="datatables-leaveTypes table table-hover border-top mb-0">
        <thead>
        <tr>
          <th>@lang('')</th>
          <th>@lang('Id')</th>
          <th>@lang('Name')</th>
          <th>@lang('Code')</th>
          <th>@lang('Description')</th>
          <th>@lang('Status')</th>
          <th>@lang('Actions')</th>
        </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
  @include('_partials._modals.leaveType.add_or_update_leave_type')
@endsection
