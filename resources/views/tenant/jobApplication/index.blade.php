@extends('layouts/layoutMaster')

@section('title', 'Manage Job Applications')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
  <style>
    .kanban-wrapper {
      display: flex;
      gap: 1.5rem;
      overflow-x: auto;
      padding-bottom: 1rem;
      align-items: flex-start;
      min-height: calc(100vh - 300px);
    }
    .kanban-column {
      flex: 0 0 320px;
      background: rgba(245, 245, 249, 0.5);
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      max-height: 100%;
    }
    .kanban-header {
      padding: 1.25rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(67, 89, 113, 0.1);
    }
    .kanban-items {
      padding: 1rem;
      flex-grow: 1;
      min-height: 150px;
    }
    .kanban-card {
      background: #fff;
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      margin-bottom: 1rem;
      cursor: grab;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .kanban-card:active {
      cursor: grabbing;
    }
    .kanban-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .gu-mirror {
      position: fixed !important;
      margin: 0 !important;
      z-index: 9999 !important;
      opacity: 0.8;
      transform: rotate(2deg);
    }
    .gu-transit {
      opacity: 0.2;
    }
    .star.voted {
      color: #ffc107;
    }
  </style>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js'
  ])
  <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      $('.flatpickr-date').flatpickr({
        dateFormat: 'Y-m-d'
      });

      @can('Move Job Application')
      var containers = [];
      $('.kanban-items').each(function() {
        containers.push(this);
      });

      dragula(containers).on('drop', function(el, target, source, sibling) {
        var order = [];
        $(target).find('.kanban-card').each(function(index) {
          order.push($(this).data('id'));
        });

        var id = $(el).data('id');
        var stage_id = $(target).data('stage-id');

        $.ajax({
          url: '{{ route('job.application.order') }}',
          type: 'POST',
          data: {
            application_id: id,
            stage_id: stage_id,
            order: order,
            "_token": $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
            $(source).closest('.kanban-column').find('.count').text($(source).find('.kanban-card').length);
            $(target).closest('.kanban-column').find('.count').text($(target).find('.kanban-card').length);
            // Optional: show toastr if available in your system
          }
        });
      });
      @endcan
    });
  </script>
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Job Applications</h3>
    @can('Create Job Application')
      <a href="#" data-url="{{ route('job-application.create') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Job Application') }}" class="btn btn-hitech-primary shadow-sm">
        <i class="bx bx-plus me-1"></i>Create New Application
      </a>
    @endcan
  </div>

  {{-- Filters --}}
  <div class="px-4 mb-6">
    <div class="hitech-card-white">
      <div class="card-body">
        <form action="{{ route('job-application.index') }}" method="GET" id="application_filter" class="m-0">
        <div class="row g-4 align-items-center">
            
          <div class="col-md-3">
            <div class="d-flex flex-column">
                <label class="form-label fw-bold small text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Start Date</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-end-0" style="background-color: #f8f9fa;"><i class="bx bx-calendar text-muted"></i></span>
                    <input class="form-control flatpickr-date border-start-0 ps-0 text-sm" name="start_date" type="text" value="{{ $filter['start_date'] }}" placeholder="Select start date" style="background-color: #f8f9fa;">
                </div>
            </div>
          </div>
          <div class="col-md-3">
             <div class="d-flex flex-column">
                <label class="form-label fw-bold small text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">End Date</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-end-0" style="background-color: #f8f9fa;"><i class="bx bx-calendar text-muted"></i></span>
                    <input class="form-control flatpickr-date border-start-0 ps-0 text-sm" name="end_date" type="text" value="{{ $filter['end_date'] }}" placeholder="Select end date" style="background-color: #f8f9fa;">
                </div>
             </div>
          </div>
          <div class="col-md-4">
             <div class="d-flex flex-column">
                <label class="form-label fw-bold small text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Job Posting</label>
                <select class="form-select form-select-sm select2 shadow-none" id="job_id" name="job" style="background-color: #f8f9fa;">
                    <option value="">All Positions</option>
                    @foreach($jobs as $key => $val)
                        <option value="{{ $key }}" {{ $filter['job'] == $key ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
             </div>
          </div>
          <div class="col-md-2 d-flex gap-2 align-items-end justify-content-end h-100" style="padding-top: 1.6rem;">
            <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-1" style="background-color: #0f766e; border-color: #0f766e;">
              <i class="bx bx-filter-alt"></i> Apply
            </button>
            <a href="{{ route('job-application.index') }}" class="btn btn-outline-secondary btn-sm btn-icon shadow-sm" data-bs-toggle="tooltip" title="Reset Filters">
              <i class="bx bx-refresh"></i>
            </a>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Kanban Board --}}
  <div class="px-4">
    <div class="kanban-wrapper horizontal-scroll-cards">
      @foreach ($stages as $stage)
        @php $applications = $stage->applications($filter) @endphp
        <div class="kanban-column">
          <div class="kanban-header">
            <h6 class="mb-0 fw-bold text-heading">{{ $stage->title }}</h6>
            <span class="badge bg-label-primary rounded-pill count">{{ count($applications) }}</span>
          </div>
          <div class="kanban-items" data-stage-id="{{ $stage->id }}">
            @foreach ($applications as $application)
              <div class="kanban-card" data-id="{{ $application->id }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="mb-0">
                    <a href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}" class="text-heading fw-bold">{{ $application->name }}</a>
                  </h6>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                      @can('Show Job Application')
                        <a class="dropdown-item" href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}">
                          <i class="bx bx-show me-1"></i> Show
                        </a>
                      @endcan
                      @can('Delete Job Application')
                        <form action="{{ route('job-application.destroy', $application->id) }}" method="POST" id="delete-form-{{ $application->id }}">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="dropdown-item text-danger" onclick="confirmDelete('{{ $application->id }}')">
                            <i class="bx bx-trash me-1"></i> Delete
                          </button>
                        </form>
                      @endcan
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <span class="small text-muted d-block">{{ !empty($application->jobs) ? $application->jobs->title : '' }}</span>
                  <div class="d-flex align-items-center mt-1">
                    <div class="static-rating static-rating-sm">
                      @for ($i = 1; $i <= 5; $i++)
                        <i class="bx bxs-star {{ $i <= $application->rating ? 'voted' : 'text-light' }} fs-tiny"></i>
                      @endfor
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center text-muted small">
                    <i class="bx bx-time-five me-1"></i>
                    {{ auth()->user()->dateFormat($application->created_at) }}
                  </div>
                  <div class="avatar-group">
                    <div class="avatar avatar-xs" data-bs-toggle="tooltip" title="{{ $application->name }}">
                      @php
                        $avatarPath = !empty($application->profile) ? 'uploads/job/profile/' . $application->profile : 'uploads/avatar/avatar.png';
                      @endphp
                      <img src="{{ Utility::get_file($avatarPath) }}" alt="Avatar" class="rounded-circle shadow-sm">
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<script>
  function confirmDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "This application will be permanently removed.",
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
