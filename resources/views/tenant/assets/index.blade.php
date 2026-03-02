@extends('layouts/layoutMaster')

@section('title', 'Asset Management')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
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

@section('content')
<div class="row g-6">
  <div class="col-lg-12">
    <x-hero-banner
      title="Asset Management"
      subtitle="Track company assets, assignments, and lifecycle status"
      icon="bx-archive"
      gradient="primary"
    />
  </div>

  <x-stat-card
    title="Total Assets"
    value="{{ $stats['total'] ?? 0 }}"
    icon="bx-package"
    color="primary"
    animation-delay="0.1s"
  />

  <x-stat-card
    title="Available"
    value="{{ $stats['available'] ?? 0 }}"
    icon="bx-check-circle"
    color="success"
    animation-delay="0.2s"
  />

  <x-stat-card
    title="Assigned"
    value="{{ $stats['assigned'] ?? 0 }}"
    icon="bx-user"
    color="info"
    animation-delay="0.3s"
  />

  <x-stat-card
    title="Maintenance"
    value="{{ $stats['maintenance'] ?? 0 }}"
    icon="bx-wrench"
    color="warning"
    animation-delay="0.4s"
  />

  {{-- Main Content Table --}}
  <div class="col-12">
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
      <div class="hitech-card-header p-sm-5 p-4 border-bottom">
        <div class="row align-items-center g-4">
          <div class="col-md-7 d-flex align-items-center gap-3">
            <div class="search-wrapper-hitech w-px-400">
              <i class="bx bx-search text-muted ms-3"></i>
              <input type="text" class="form-control" placeholder="Search assets..." id="customSearchInput">
              <button class="btn-search" id="customSearchBtn">
                <i class="bx bx-search fs-5"></i>
              </button>
            </div>
          </div>
          <div class="col-md-5 d-flex align-items-center justify-content-end gap-3">
            <a href="{{ route('assets.create') }}" class="btn btn-hitech add-new px-4 d-flex align-items-center gap-2">
              <i class="bx bx-plus-circle fs-5"></i>
              <span>@lang('Add Asset')</span>
            </a>
          </div>
        </div>
      </div>
      <div class="card-datatable table-responsive p-0">
        <table class="datatables-assets table table-hover border-top mb-0">
          <thead>
            <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Category</th>
              <th>Assigned To</th>
              <th>Status</th>
              <th>Purchase Cost</th>
              <th>Current Value</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const dt = $('.datatables-assets').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('assets.getListAjax') }}',
          data: function (d) {
            d.searchTerm = $('#customSearchInput').val();
          }
        },
        columns: [
          { data: 'asset_code', name: 'asset_code' },
          { data: 'name', name: 'name' },
          { data: 'category_name', name: 'category.name', orderable: false, searchable: false },
          { data: 'assigned_user', name: 'assignedUser.first_name', orderable: false, searchable: false },
          { data: 'status_badge', name: 'status', orderable: false, searchable: false },
          { data: 'formatted_purchase_cost', name: 'purchase_cost', orderable: false, searchable: false },
          { data: 'formatted_current_value', name: 'current_value', orderable: false, searchable: false },
          { data: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        dom: '<"row"<"col-sm-12"tr>><"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
          sLengthMenu: '_MENU_',
          search: '',
          searchPlaceholder: 'Search Asset',
          info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
          paginate: {
            next: '<i class="bx bx-chevron-right bx-sm"></i>',
            previous: '<i class="bx bx-chevron-left bx-sm"></i>'
          }
        },
        responsive: true
      });

      $('#customSearchInput').on('keyup', function () {
        dt.draw();
      });

      $('#customSearchBtn').on('click', function () {
        dt.draw();
      });
    });
  </script>
@endpush
