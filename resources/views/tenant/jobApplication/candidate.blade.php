@extends('layouts/layoutMaster')

@section('title', 'Manage Archive Applications')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
  ])
@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      var table = $('.datatable').DataTable({
        dom: 't<"d-flex justify-content-between align-items-center mx-3 mt-4 mb-2" <"small text-muted" i> <"pagination-wrapper" p>>',
        language: {
          info: 'Showing _START_ to _END_ of _TOTAL_ candidates',
          paginate: {
            next: '<i class="bx bx-chevron-right"></i>',
            previous: '<i class="bx bx-chevron-left"></i>'
          }
        }
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
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Archived Applications</h3>
    <a href="{{ route('job-application.index') }}" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center" style="font-weight: 500;">
      <i class="bx bx-left-arrow-alt me-1"></i>Back to Board
    </a>
  </div>

  <div class="px-4">
    <div class="hitech-card-white mb-6 overflow-hidden">
      <div class="card-body p-sm-5 p-4">
        <div class="row align-items-center g-4">
          <div class="col-md-9 d-flex align-items-center gap-3 w-100">
            <div class="search-wrapper-hitech flex-grow-1">
              <i class="bx bx-search text-muted ms-3"></i>
              <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Search Candidates..." id="customSearchInput">
            </div>
            
            <form action="{{ route('job.application.candidate') }}" method="GET" id="application_filter" class="d-flex align-items-center gap-2 m-0">
               <div class="d-flex flex-column" style="min-width: 200px;">
                    <select class="form-select border-0 shadow-sm" id="job_id" name="job" style="background-color: #f8f9fa;">
                        <option value="">All Positions</option>
                        @foreach($jobs as $key => $val)
                            <option value="{{ $key }}" {{ $filter['job'] == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
               </div>
               <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-1" style="background-color: #0f766e; border-color: #0f766e;">
                 <i class="bx bx-filter-alt"></i> Apply
               </button>
               <a href="{{ route('job.application.candidate') }}" class="btn btn-outline-secondary btn-sm btn-icon shadow-sm" data-bs-toggle="tooltip" title="Reset Filters">
                 <i class="bx bx-refresh"></i>
               </a>
            </form>
          </div>
          <div class="col-md-3 d-flex align-items-center justify-content-end gap-3 mt-0">
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
        <table class="datatable table m-0 shadow-none table-hover">
            <thead>
              <tr class="text-muted small text-uppercase">
                <th class="border-bottom">Candidate</th>
                <th class="border-bottom">Applied For</th>
                <th class="border-bottom">Rating</th>
                <th class="border-bottom">Applied Date</th>
                <th class="border-bottom">Resume</th>
                <th class="border-bottom text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($archive_application as $application)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      @php
                        $avatarPath = !empty($application->profile) ? 'uploads/job/profile/' . $application->profile : 'uploads/avatar/avatar.png';
                      @endphp
                      <div class="avatar avatar-sm me-3">
                        <img src="{{ Utility::get_file($avatarPath) }}" alt="Avatar" class="rounded-circle border border-2 border-primary">
                      </div>
                      <a href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}" class="fw-bold text-heading">{{ $application->name }}</a>
                    </div>
                  </td>
                  <td><span class="badge bg-label-info">{{ !empty($application->jobs) ? $application->jobs->title : '-' }}</span></td>
                  <td>
                    <div class="text-warning">
                      @for ($i = 1; $i <= 5; $i++)
                        <i class="bx {{ $i <= $application->rating ? 'bxs-star' : 'bx-star text-muted' }}"></i>
                      @endfor
                    </div>
                  </td>
                  <td>{{ auth()->user()->dateFormat($application->created_at) }}</td>
                  <td>
                    @if (!empty($application->resume))
                      <div class="d-flex gap-2">
                        <a href="{{ Utility::get_file('uploads/job/resume/' . $application->resume) }}" class="btn btn-icon btn-label-primary btn-sm" data-bs-toggle="tooltip" title="Download" download>
                          <i class="bx bx-download"></i>
                        </a>
                        <a href="{{ Utility::get_file('uploads/job/resume/' . $application->resume) }}" target="_blank" class="btn btn-icon btn-label-info btn-sm" data-bs-toggle="tooltip" title="Preview">
                          <i class="bx bx-show"></i>
                        </a>
                      </div>
                    @else
                      <span class="text-muted italic small">No CV</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <div class="dropdown">
                      <button type="button" class="btn btn-icon btn-label-secondary btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}">
                          <i class="bx bx-show-alt me-2"></i>View Profile
                        </a>
                        <form action="{{ route('job.application.archive', $application->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item">
                            <i class="bx bx-undo me-2"></i>Unarchive
                          </button>
                        </form>
                      </div>
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
</div>
@endsection
