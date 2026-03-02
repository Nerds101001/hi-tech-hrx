@extends('layouts/layoutMaster')

@section('title', 'Job Details')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Job Details: {{ $job->title }}</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('job.index') }}" class="btn btn-label-secondary shadow-sm">
        <i class="bx bx-chevron-left me-1"></i>Back
      </a>
      @can('Edit Job')
        <a href="{{ route('job.edit', $job->id) }}" class="btn btn-hitech-primary shadow-sm">
          <i class="bx bx-edit me-1"></i>Edit Job
        </a>
      @endcan
    </div>
  </div>

  <div class="px-4">
    <div class="row g-6">
      <div class="col-lg-8">
        <div class="hitech-card-white mb-6">
          <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Overview</h5>
            @if($job->status == 'active')
              <span class="badge bg-label-success rounded-pill">Active</span>
            @else
              <span class="badge bg-label-danger rounded-pill">Inactive</span>
            @endif
          </div>
          <div class="card-body pt-5">
            <div class="row g-4 mb-6">
              <div class="col-md-4">
                <div class="p-4 rounded border bg-light">
                  <span class="d-block text-muted small fw-bold text-uppercase mb-1">Branch / Site</span>
                  <span class="h6 mb-0">{{ !empty($job->branches) ? $job->branches->name : __('All') }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-4 rounded border bg-light">
                  <span class="d-block text-muted small fw-bold text-uppercase mb-1">Category</span>
                  <span class="h6 mb-0">{{ !empty($job->categories) ? $job->categories->title : '-' }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-4 rounded border bg-light">
                  <span class="d-block text-muted small fw-bold text-uppercase mb-1">Positions</span>
                  <span class="h6 mb-0">{{ $job->position }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-4 rounded border bg-light">
                  <span class="d-block text-muted small fw-bold text-uppercase mb-1">Created At</span>
                  <span class="h6 mb-0">{{ auth()->user()->dateFormat($job->created_at) }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-4 rounded border bg-light">
                  <span class="d-block text-muted small fw-bold text-uppercase mb-1">Start Date</span>
                  <span class="h6 mb-0">{{ auth()->user()->dateFormat($job->start_date) }}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-4 rounded border bg-light">
                  <span class="d-block text-muted small fw-bold text-uppercase mb-1">End Date</span>
                  <span class="h6 mb-0">{{ auth()->user()->dateFormat($job->end_date) }}</span>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <h6 class="fw-bold text-heading mb-3 border-bottom pb-2">Skills Required</h6>
              <div class="d-flex flex-wrap gap-2">
                @foreach($job->skill as $skill)
                  <span class="badge bg-label-primary rounded-pill px-3">{{ $skill }}</span>
                @endforeach
              </div>
            </div>

            <div class="mb-6">
              <h6 class="fw-bold text-heading mb-3 border-bottom pb-2">Description</h6>
              <div class="text-muted">
                {!! $job->description !!}
              </div>
            </div>

            <div class="mb-6">
              <h6 class="fw-bold text-heading mb-3 border-bottom pb-2">Requirements</h6>
              <div class="text-muted">
                {!! $job->requirement !!}
              </div>
            </div>

            @if (!empty($job->terms_and_conditions))
              <div class="mb-6">
                <h6 class="fw-bold text-heading mb-3 border-bottom pb-2">Terms & Conditions</h6>
                <div class="text-muted italic">
                  {!! $job->terms_and_conditions !!}
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="hitech-card-white mb-6">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Application Configuration</h5>
          </div>
          <div class="card-body pt-5">
            <div class="mb-6">
              <h6 class="fw-bold small text-muted text-uppercase mb-3">Questions & Fields</h6>
              <ul class="list-group list-group-flush border rounded overflow-hidden">
                @if($job->applicant)
                  @foreach($job->applicant as $applicant)
                    <li class="list-group-item d-flex align-items-center gap-3">
                      <i class="bx bx-check-circle text-success fs-5"></i>
                      <span>Ask For: <strong>{{ ucfirst($applicant) }}</strong></span>
                    </li>
                  @endforeach
                @endif
                @if($job->visibility)
                  @foreach($job->visibility as $visibility)
                    <li class="list-group-item d-flex align-items-center gap-3">
                      <i class="bx bx-show text-info fs-5"></i>
                      <span>Show: <strong>{{ ucfirst($visibility) }}</strong></span>
                    </li>
                  @endforeach
                @endif
              </ul>
            </div>

            @if(count($job->questions()) > 0)
              <div>
                <h6 class="fw-bold small text-muted text-uppercase mb-3">Custom Questions</h6>
                <div class="d-flex flex-column gap-3">
                  @foreach($job->questions() as $question)
                    <div class="p-3 rounded border bg-light">
                      <div class="d-flex align-items-start gap-2">
                        <i class="bx bx-question-mark text-primary mt-1"></i>
                        <span class="small fw-semibold">{{ $question->question }}</span>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        </div>

        <div class="hitech-card-white">
          <div class="card-body text-center py-6">
            <p class="text-muted small mb-4">Share this job listing with candidates using the unique link below.</p>
            <div class="d-grid">
              <button class="btn btn-label-primary copy_link" href="{{ route('job.requirement', [$job->code, 'en']) }}">
                <i class="bx bx-copy me-1"></i>Copy Career Link
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
