@extends('layouts/layoutMaster')

@section('title', 'Manage Job Stages')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/sortablejs/sortablejs.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    .stage-item {
      cursor: grab;
      border: 1px solid rgba(0, 0, 0, 0.05);
      border-radius: 0.75rem;
      transition: all 0.2s;
      background: #fff;
    }
    .stage-item:active {
      cursor: grabbing;
    }
    .stage-item:hover {
      border-color: var(--bs-primary);
      box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.08);
    }
    .ghost {
      opacity: 0.4;
      background: rgba(var(--bs-primary-rgb), 0.05) !important;
      border: 2px dashed var(--bs-primary) !important;
    }
  </style>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/sortablejs/sortablejs.js'
  ])
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
    const el = document.getElementById('sortable-stages');
    if (el) {
      Sortable.create(el, {
        animation: 150,
        ghostClass: 'ghost',
        onEnd: function() {
          let order = [];
          $('.stage-item').each(function() {
            order.push($(this).data('id'));
          });

          $.ajax({
            url: "{{ route('job.stage.order') }}",
            type: 'POST',
            data: {
              order: order,
              _token: '{{ csrf_token() }}'
            },
            success: function(data) {
              // Optionally show a subtle toast
            },
            error: function(data) {
              alert('Error updating order');
            }
          });
        }
      });
    }
  });
</script>
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <div>
      <h3 class="mb-1 fw-bold text-heading" style="font-size: 1.5rem;">Recruitment Stages</h3>
      <p class="text-muted mb-0 small">Define the journey of your candidates from application to hire.</p>
    </div>
    @can('Create Job Stage')
      <a href="#" data-url="{{ route('job-stage.create') }}" data-ajax-popup="true" data-size="md" data-title="Create New Job Stage" class="btn btn-hitech-primary shadow-sm">
        <i class="bx bx-plus me-1"></i>Add Stage
      </a>
    @endcan
  </div>

  <div class="px-4">
    <div class="hitech-card-white p-6">
      <div id="sortable-stages" class="row g-4">
        @forelse ($stages as $stage)
          <div class="col-12 stage-item p-4 mb-2 d-flex align-items-center justify-content-between" data-id="{{ $stage->id }}">
            <div class="d-flex align-items-center">
              <i class="bx bx-menu text-muted me-4 fs-4 handle"></i>
              <div>
                <h6 class="mb-0 fw-bold text-heading">{{ $stage->title }}</h6>
                <span class="text-muted small">Position: #{{ $loop->iteration }}</span>
              </div>
            </div>
            <div class="d-flex gap-2">
              @can('Edit Job Stage')
                <a href="#" data-url="{{ route('job-stage.edit', $stage->id) }}" data-ajax-popup="true" data-title="Edit Job Stage" class="btn btn-icon btn-label-info btn-sm">
                  <i class="bx bx-edit-alt"></i>
                </a>
              @endcan

              @can('Delete Job Stage')
                <form action="{{ route('job-stage.destroy', $stage->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-icon btn-label-danger btn-sm bs-pass-para">
                    <i class="bx bx-trash"></i>
                  </button>
                </form>
              @endcan
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-10">
            <i class="bx bx-layer-minus fs-1 text-muted mb-3"></i>
            <h6 class="text-muted">No stages defined</h6>
            <p class="small text-muted">Initialize your recruitment workflow by adding stages.</p>
          </div>
        @endforelse
      </div>
      <div class="mt-6 p-4 bg-label-primary rounded-3">
        <div class="d-flex">
          <i class="bx bx-info-circle me-3 mt-1 fs-5"></i>
          <p class="mb-0 small fw-medium">
            <strong>Pro Tip:</strong> You can reorder these stages by dragging the <i class="bx bx-menu ms-1"></i> handle. The sequence here reflects the columns in your Candidate Kanban board.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
