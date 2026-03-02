@extends('layouts/layoutMaster')

@section('title', $job->title)

@php
  $pageConfigs = ['myLayout' => 'blank'];
  $logo = \App\Models\Utility::get_file('uploads/logo/');
@endphp

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/animate-css/animate-css.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    :root {
      --glass-bg: rgba(255, 255, 255, 0.7);
      --glass-border: rgba(255, 255, 255, 0.4);
      --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08);
    }
    body {
      background: radial-gradient(circle at top right, rgba(0, 128, 128, 0.12), transparent),
                  radial-gradient(circle at bottom left, rgba(0, 128, 128, 0.08), transparent),
                  #f5f7f9;
      min-height: 100vh;
      font-family: 'Public Sans', sans-serif;
    }
    .requirement-container {
      margin-top: -60px;
      position: relative;
      z-index: 10;
    }
    .glass-pannel {
      background: var(--glass-bg);
      backdrop-filter: blur(12px) saturate(180%);
      -webkit-backdrop-filter: blur(12px) saturate(180%);
      border: 1px solid var(--glass-border);
      border-radius: 2rem;
      box-shadow: var(--glass-shadow);
      overflow: hidden;
    }
    .job-header {
      background: linear-gradient(135deg, rgba(0, 128, 128, 0.1), rgba(0, 128, 128, 0.02));
      padding: 3rem;
      border-bottom: 1px solid rgba(var(--bs-primary-rgb), 0.05);
    }
    .rich-content h3 {
      font-weight: 700;
      color: var(--bs-heading-color);
      margin-top: 2rem;
      margin-bottom: 1rem;
    }
    .rich-content p {
      line-height: 1.8;
      color: var(--bs-body-color);
    }
    .sticky-apply {
      position: sticky;
      top: 100px;
    }
    .navbar-glass {
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }
  </style>
@endsection

@section('content')
<div class="requirement-page pb-20">
  {{-- Navbar --}}
  <nav class="navbar navbar-expand-lg navbar-glass sticky-top py-4">
    <div class="container d-flex justify-content-between">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('career', [\Illuminate\Support\Facades\Crypt::encrypt($job->created_by), $currantLang]) }}">
        @if(isset($companySettings['company_logo']->value))
          <img src="{{ $logo . '/' . $companySettings['company_logo']->value }}" alt="logo" style="height: 45px;">
        @else
          <span class="h4 mb-0 fw-bold text-primary">{{ !empty($companySettings['title_text']->value) ? $companySettings['title_text']->value : 'Hi Tech HRX' }}</span>
        @endif
      </a>
      <a href="{{ route('career', [\Illuminate\Support\Facades\Crypt::encrypt($job->created_by), $currantLang]) }}" class="btn btn-label-secondary btn-sm rounded-pill">
        <i class="bx bx-left-arrow-alt me-1"></i>Back to Jobs
      </a>
    </div>
  </nav>

  {{-- Subtle Hero Blur --}}
  <div style="height: 200px; background: rgba(var(--bs-primary-rgb), 0.05);"></div>

  <div class="container requirement-container">
    <div class="row g-8">
      {{-- Detail Section --}}
      <div class="col-lg-8 animate__animated animate__fadeInLeft">
        <div class="glass-pannel">
          <div class="job-header">
            <h1 class="display-5 fw-extrabold text-heading mb-4">{{ $job->title }}</h1>
            <div class="d-flex flex-wrap gap-4 align-items-center">
              <span class="badge bg-label-primary rounded-pill px-4 py-2">{{ $job->category->title ?? 'General' }}</span>
              <span class="text-muted"><i class="bx bx-map me-1"></i>{{ $job->branches->name ?? 'Remote' }}</span>
              <span class="text-muted"><i class="bx bx-calendar-event me-1"></i>Posted {{ $job->created_at->diffForHumans() }}</span>
            </div>
          </div>
          
          <div class="p-8 rich-content">
            <div class="mb-8 p-6 bg-label-primary rounded-3 d-flex align-items-center">
              <i class="bx bx-rocket fs-2 me-4"></i>
              <div>
                <h6 class="mb-0 fw-bold">Exciting Opportunity</h6>
                <p class="mb-0 small">We are looking for {{ $job->position }} {{ \Illuminate\Support\Str::plural('talent', $job->position) }} to join our mission-driven team.</p>
              </div>
            </div>

            <h3 class="mt-0">The Role</h3>
            <div class="mb-8">{!! $job->description !!}</div>

            <h3>Requirements</h3>
            <div class="mb-8">{!! $job->requirement !!}</div>

            <h3>Preferred Skills</h3>
            <div class="d-flex flex-wrap gap-2 mb-8">
              @foreach (explode(',', $job->skill) as $skill)
                <span class="badge bg-label-secondary rounded-pill px-4 py-2">{{ trim($skill) }}</span>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      {{-- Action Sidebar --}}
      <div class="col-lg-4 animate__animated animate__fadeInRight">
        <div class="sticky-apply">
          <div class="glass-pannel p-6 text-center">
            <h5 class="fw-bold mb-4">Ready to Apply?</h5>
            <p class="text-muted small mb-6">Take the first step towards your dream career. The application process takes less than 5 minutes.</p>
            <a href="{{ route('job.apply', [$job->code, $currantLang]) }}" class="btn btn-primary btn-lg w-100 rounded-pill shadow-lg py-3">
              Apply Now <i class="bx bx-send ms-2"></i>
            </a>
            <div class="mt-6 pt-6 border-top text-start">
              <h6 class="fw-bold small text-muted text-uppercase mb-4">Quick Facts</h6>
              <div class="d-flex align-items-center mb-3">
                <i class="bx bx-time-five text-primary me-3"></i>
                <span class="small">Full-time Position</span>
              </div>
              <div class="d-flex align-items-center mb-3">
                <i class="bx bx-建物 text-primary me-3"></i>
                <span class="small">On-site / Hybrid</span>
              </div>
              <div class="d-flex align-items-center mb-0">
                <i class="bx bx-id-card text-primary me-3"></i>
                <span class="small">Ref: #{{ strtoupper($job->code) }}</span>
              </div>
            </div>
          </div>

          <div class="mt-6 glass-pannel p-6 text-center">
            <h6 class="fw-bold mb-3">Share this Role</h6>
            <div class="d-flex justify-content-center gap-3">
              <button class="btn btn-icon btn-label-primary rounded-circle"><i class="bx bxl-linkedin"></i></button>
              <button class="btn btn-icon btn-label-info rounded-circle"><i class="bx bxl-twitter"></i></button>
              <button class="btn btn-icon btn-label-secondary rounded-circle"><i class="bx bx-link"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Footer --}}
  <footer class="mt-20 py-10 border-top bg-white">
    <div class="container text-center">
      <p class="mb-0 text-muted small">&copy; {{ date('Y') }} {{ isset($companySettings['footer_text']->value) ? $companySettings['footer_text']->value : 'Hi Tech HRX' }}. All rights reserved.</p>
    </div>
  </footer>
</div>
@endsection
