@extends('layouts/layoutMaster')

@section('title', 'Manage Interview Schedules')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/fullcalendar/fullcalendar.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/quill/editor.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/scss/pages/app-calendar.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    .fc-event-primary {
      background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
      color: var(--bs-primary) !important;
      border-left: 3px solid var(--bs-primary) !important;
    }
    .schedule-list-item {
      border: 1px solid rgba(0, 0, 0, 0.05);
      border-radius: 0.75rem;
      transition: all 0.2s;
    }
    .schedule-list-item:hover {
      background: rgba(var(--bs-primary-rgb), 0.02);
      transform: translateX(5px);
    }
  </style>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/fullcalendar/fullcalendar.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
    const calendarEl = document.getElementById('calendar');
    
    // Initialize FullCalendar
    let calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        start: 'prev,next today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: {
        url: "{{ route('interview-schedule.data') }}",
        method: 'GET',
        failure: function() {
          alert('There was an error while fetching events!');
        }
      },
      eventClick: function(info) {
        info.jsEvent.preventDefault();
        // Here we could open a modal for quick view or redirect to show
        window.location.href = info.event.url;
      },
      height: 'auto',
      themeSystem: 'bootstrap5'
    });

    calendar.render();
  });
</script>
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Interview Schedules</h3>
    @can('Create Interview Schedule')
      <a href="#" data-url="{{ route('interview-schedule.create') }}" data-ajax-popup="true" data-size="md" data-title="Create New Interview Schedule" class="btn btn-hitech-primary shadow-sm">
        <i class="bx bx-plus me-1"></i>New Interview
      </a>
    @endcan
  </div>

  <div class="px-4">
    <div class="row g-6">
      {{-- Calendar Section --}}
      <div class="col-lg-8">
        <div class="hitech-card-white h-100">
          <div class="card-body">
            <div id="calendar"></div>
          </div>
        </div>
      </div>

      {{-- Upcoming Interviews List --}}
      <div class="col-lg-4">
        <div class="hitech-card-white h-100">
          <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-4">
            <h5 class="mb-0 fw-bold">This Month's Schedule</h5>
            <span class="badge bg-label-primary rounded-pill">{{ count($current_month_event) }}</span>
          </div>
          <div class="card-body pt-5">
            <div class="schedule-list overflow-auto" style="max-height: 600px;">
              @forelse($current_month_event as $schedule)
                <div class="schedule-list-item p-4 mb-4">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h6 class="mb-1 fw-bold text-primary">{{ $schedule->applications->jobs->title ?? 'N/A' }}</h6>
                      <p class="mb-0 small fw-medium">{{ $schedule->applications->name ?? 'Candidate' }}</p>
                    </div>
                    <div class="dropdown">
                      <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        @can('Edit Interview Schedule')
                          <a class="dropdown-item" href="#" data-url="{{ route('interview-schedule.edit', $schedule->id) }}" data-ajax-popup="true" data-title="Edit Schedule">
                            <i class="bx bx-edit-alt me-1"></i>Edit
                          </a>
                        @endcan
                        @can('Delete Interview Schedule')
                          <form action="{{ route('interview-schedule.destroy', $schedule->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                              <i class="bx bx-trash me-1"></i>Delete
                            </button>
                          </form>
                        @endcan
                      </div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center text-muted small mt-3">
                    <span class="me-3"><i class="bx bx-calendar me-1"></i>{{ auth()->user()->dateFormat($schedule->date) }}</span>
                    <span><i class="bx bx-time-five me-1"></i>{{ auth()->user()->timeFormat($schedule->time) }}</span>
                  </div>
                  @if($schedule->comment)
                    <div class="mt-3 p-2 bg-light rounded-2">
                      <p class="mb-0 small text-muted italic">"{{ Str::limit($schedule->comment, 60) }}"</p>
                    </div>
                  @endif
                </div>
              @empty
                <div class="text-center py-10">
                  <div class="mb-3">
                    <i class="bx bx-calendar-x fs-1 text-muted"></i>
                  </div>
                  <h6 class="text-muted">No interviews scheduled</h6>
                  <p class="small text-muted">Scheduled interviews for this month will appear here.</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
