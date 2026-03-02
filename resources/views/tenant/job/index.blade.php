@extends('layouts/layoutMaster')

@section('title', 'Manage Job')

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
    $(document).ready(function() {
      $('.copy_link').click(function(e) {
        e.preventDefault();
        var copyText = $(this).attr('href');
        navigator.clipboard.writeText(copyText).then(function() {
             Swal.fire({
                title: 'Success!',
                text: 'Url copied to clipboard',
                icon: 'success',
                customClass: {
                  confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
              });
        });
      });

      var table = $('.datatables-jobs').DataTable({
        dom: 't<"d-flex justify-content-between align-items-center mx-3 mt-4 mb-2" <"small text-muted" i> <"pagination-wrapper" p>>',
        language: {
          info: 'Showing _START_ to _END_ of _TOTAL_ jobs',
          paginate: {
            next: '<i class="bx bx-chevron-right"></i>',
            previous: '<i class="bx bx-chevron-left"></i>'
          }
        },
        scrollX: true
      });
      $('#customSearchInput').on('keyup', function() {
        table.search(this.value).draw();
      });
      $('#customSearchBtn').on('click', function() {
        table.search($('#customSearchInput').val()).draw();
      });
      $('#customLengthMenu').on('change', function() {
        table.page.len($(this).val()).draw();
      });
    });
  </script>
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  {{-- Page Header --}}
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Manage Jobs</h3>
    @can('Create Job')
      <a href="{{ route('job.create') }}" class="btn btn-hitech-primary shadow-sm" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Job') }}">
        <i class="bx bx-plus me-1"></i>Create New Job
      </a>
    @endcan
  </div>

  <div class="px-4">
    <div class="row g-6 mb-6">
      <div class="col-lg-4 col-md-6">
        <div class="hitech-stat-card dashboard-variant card-teal">
          <div class="stat-card-header">
            <div class="stat-icon-wrap icon-teal">
              <i class="bx bx-briefcase"></i>
            </div>
            <span class="stat-card-label">Total Jobs</span>
          </div>
          <div class="stat-card-value">{{$data['total']}}</div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="hitech-stat-card dashboard-variant card-blue">
          <div class="stat-card-header">
            <div class="stat-icon-wrap icon-blue">
              <i class="bx bx-check-circle"></i>
            </div>
            <span class="stat-card-label">Active Jobs</span>
          </div>
          <div class="stat-card-value">{{$data['active']}}</div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="hitech-stat-card dashboard-variant card-red">
          <div class="stat-card-header">
            <div class="stat-icon-wrap icon-red">
              <i class="bx bx-x-circle"></i>
            </div>
            <span class="stat-card-label">Inactive Jobs</span>
          </div>
          <div class="stat-card-value">{{$data['in_active']}}</div>
        </div>
      </div>
    </div>

    <div class="hitech-card-white mb-6 overflow-hidden">
      <div class="card-body p-sm-5 p-4">
        <div class="row g-4">
          <div class="col-lg-9 d-flex flex-wrap align-items-center gap-3 w-100">
            <div class="search-wrapper-hitech flex-grow-1 mw-100" style="max-width: 400px;">
              <i class="bx bx-search text-muted ms-3"></i>
              <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Search Jobs..." id="customSearchInput">
            </div>
            <button class="btn btn-primary d-none d-sm-flex px-3 shadow-sm align-items-center justify-content-center gap-1" id="customSearchBtn" style="background-color: #0f766e; border-color: #0f766e;">
              <i class="bx bx-search"></i> Search
            </button>
          </div>
          <div class="col-lg-3 d-flex flex-wrap align-items-center justify-content-between justify-content-lg-end gap-3 mt-3 mt-lg-0">
            <span class="text-muted fw-semibold small text-nowrap">Per Page:</span>
            <select class="form-select flex-shrink-0 w-px-80 rounded text-center border-light shadow-none fw-bold" id="customLengthMenu" style="background-color: #f8f9fa;">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="hitech-card-white p-0 overflow-hidden">
      <div class="card-datatable table-responsive">
        <table class="datatables-jobs table m-0 shadow-none">
          <thead>
            <tr>
              <th>{{ __('Branch') }}</th>
              <th>{{ __('Title') }}</th>
              <th>{{ __('Start Date') }}</th>
              <th>{{ __('End Date') }}</th>
              <th>{{ __('Status') }}</th>
              <th>{{ __('Created At') }}</th>
              <th class="text-center">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($jobs as $job)
              <tr>
                <td>{{ !empty($job->branches) ? $job->branches->name : __('All') }}</td>
                <td><span class="fw-bold text-heading">{{ $job->title }}</span></td>
                <td>{{ auth()->user()->dateFormat($job->start_date) }}</td>
                <td>{{ auth()->user()->dateFormat($job->end_date) }}</td>
                <td>
                  @if ($job->status == 'active')
                    <span class="badge bg-label-success rounded-pill">{{ App\Models\Job::$status[$job->status] }}</span>
                  @else
                    <span class="badge bg-label-danger rounded-pill">{{ App\Models\Job::$status[$job->status] }}</span>
                  @endif
                </td>
                <td>{{ auth()->user()->dateFormat($job->created_at) }}</td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    @can('Show Job')
                      <a href="{{ route('job.show', $job->id) }}" class="btn btn-icon btn-label-info" data-bs-toggle="tooltip" title="{{ __('Job Detail') }}">
                        <i class="bx bx-show"></i>
                      </a>
                    @endcan
                    @can('Edit Job')
                      <a href="{{ route('job.edit', $job->id) }}" class="btn btn-icon btn-label-warning" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                        <i class="bx bx-edit"></i>
                      </a>
                    @endcan
                    @can('Delete Job')
                      <form action="{{ route('job.destroy', $job->id) }}" method="POST" class="d-inline" id="delete-form-{{ $job->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-icon btn-label-danger bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}" onclick="confirmDelete('{{ $job->id }}')">
                          <i class="bx bx-trash"></i>
                        </button>
                      </form>
                    @endcan
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function(result) {
      if (result.value) {
        document.getElementById('delete-form-' + id).submit();
      }
    });
  }
</script>
@endsection
