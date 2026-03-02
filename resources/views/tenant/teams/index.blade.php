@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Teams'))

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
  @vite(['resources/assets/js/app/team-index.js'])
@endsection


@section('content')
<div class="px-4">
    {{-- HERO SECTION --}}
    <x-hero-banner 
      title="Team Management" 
      subtitle="Organize your workforce into functional teams."
      icon="bx-group"
      gradient="teal"
    />

    {{-- TABLE SECTION --}}
    <div class="hitech-card animate__animated animate__fadeInUp mt-6" style="animation-delay: 0.1s">
        <div class="hitech-card-header border-bottom">
            <div class="d-flex align-items-center gap-3">
                <h5 class="title mb-0">Teams List</h5>
                <span class="badge search-light-badge rounded-pill px-3">Active Units</span>
            </div>
        </div>

        {{-- Unified White Filter Row --}}
        <div class="card-body p-4 border-bottom">
            <div class="d-flex flex-wrap align-items-center gap-3">
                {{-- Search --}}
                <div class="search-wrapper-hitech" style="width: 400px;">
                    <i class="bx bx-search text-muted ms-3 fs-5"></i>
                    <input type="text" class="form-control" placeholder="Search team name or code..." id="customSearchInput">
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
                <button type="button" class="btn btn-hitech shadow-sm px-4 add-new" data-bs-toggle="modal"
                        data-bs-target="#modalAddOrUpdateTeam">
                    <i class="bx bx-plus me-1"></i>Add Team
                </button>

                {{-- Export Icon --}}
                <button type="button" class="btn btn-hitech-icon shadow-sm" onclick="exportData()" title="Export Teams">
                    <i class="bx bx-download fs-5"></i>
                </button>
            </div>
        </div>
        <div class="card-datatable table-responsive p-0">
            <table class="datatables-teams table table-hover border-top mb-0">
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
  @include('_partials._modals.team.add_or_update_team')
@endsection
