@extends('layouts/layoutMaster')

@section('title', 'Join Our Team')

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
      --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }
    body {
      background: radial-gradient(circle at top right, rgba(0, 128, 128, 0.12), transparent),
                  radial-gradient(circle at bottom left, rgba(0, 128, 128, 0.08), transparent),
                  #f5f7f9;
      min-height: 100vh;
      font-family: 'Public Sans', sans-serif;
    }
    .career-hero {
      padding: 100px 0;
      background: url('/assets/img/pages/career-banner.jpg') center/cover no-repeat;
      position: relative;
      overflow: hidden;
      border-radius: 0 0 3rem 3rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      margin-bottom: -50px;
    }
    .career-hero::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(135deg, rgba(0, 128, 128, 0.85), rgba(20, 184, 166, 0.7));
      backdrop-filter: blur(4px);
    }
    .hero-content {
      position: relative;
      z-index: 1;
    }
    .glass-card {
      background: var(--glass-bg);
      backdrop-filter: blur(12px) saturate(180%);
      -webkit-backdrop-filter: blur(12px) saturate(180%);
      border: 1px solid var(--glass-border);
      border-radius: 1.5rem;
      box-shadow: var(--glass-shadow);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.15);
      background: rgba(255, 255, 255, 0.85);
    }
    .job-badge {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 0.4rem 0.8rem;
    }
    .navbar-glass {
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }
  </style>
@endsection

@section('content')
<div class="career-page">
  {{-- Navbar --}}
  <nav class="navbar navbar-expand-lg navbar-glass sticky-top py-4">
    <div class="container d-flex justify-content-between">
      <a class="navbar-brand d-flex align-items-center" href="#">
        @if(isset($companySettings['company_logo']->value))
          <img src="{{ $logo . '/' . $companySettings['company_logo']->value }}" alt="logo" style="height: 45px;">
        @else
          <span class="h4 mb-0 fw-bold text-primary">{{ !empty($companySettings['title_text']->value) ? $companySettings['title_text']->value : 'Hi Tech HRX' }}</span>
        @endif
      </a>
      <div class="d-none d-md-block">
        <span class="text-muted small fw-medium">Powered by HITECH Recruitment</span>
      </div>
    </div>
  </nav>

  {{-- Hero Section --}}
  <section class="career-hero d-flex align-items-center">
    <div class="container hero-content text-center">
      <h1 class="display-3 fw-extrabold text-white mb-4 animate__animated animate__fadeInDown">Build the Future With Us</h1>
      <p class="lead text-white opacity-90 mb-8 max-w-700 mx-auto animate__animated animate__fadeInUp">We're on a mission to redefine excellence. Explore our openings and find where you belong in our growing team.</p>
      <div class="animate__animated animate__zoomIn animate__delay-1s">
        <a href="#openings" class="btn btn-white btn-lg px-8 rounded-pill shadow-lg text-primary fw-bold">View Openings</a>
      </div>
    </div>
  </section>

  {{-- Main Content --}}
  <div id="openings" class="container py-15">
    <div class="row mb-12">
      <div class="col-lg-6">
        <h2 class="fw-bold mb-2">Current Openings</h2>
        <p class="text-muted">Showing {{ $jobs->count() }} active roles across all departments.</p>
      </div>
    </div>

    <div class="row g-6">
      @forelse($jobs as $job)
        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="glass-card h-100 p-6 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
              <div class="avatar avatar-lg bg-label-primary rounded-3">
                <i class="bx bx-briefcase fs-3"></i>
              </div>
              <span class="badge bg-label-success rounded-pill job-badge">{{ $job->category->title ?? 'General' }}</span>
            </div>
            
            <h4 class="fw-bold text-heading mb-2">{{ $job->title }}</h4>
            
            <div class="d-flex flex-wrap gap-3 mb-4 text-muted small">
              <span><i class="bx bx-map me-1"></i>{{ $job->branches->name ?? 'Remote' }}</span>
              <span><i class="bx bx-user-plus me-1"></i>{{ $job->position }} {{ \Illuminate\Support\Str::plural('Opening', $job->position) }}</span>
            </div>

            <div class="flex-grow-1">
              <p class="text-muted small line-clamp-3 mb-6">
                {{ \Illuminate\Support\Str::limit(strip_tags($job->description), 120) }}
              </p>
              
              <div class="d-flex flex-wrap gap-1 mb-6">
                @foreach (explode(',', $job->skill) as $skill)
                  <span class="badge bg-label-secondary rounded-pill small" style="font-size: 0.65rem;">{{ trim($skill) }}</span>
                @endforeach
              </div>
            </div>

            <a href="{{ route('job.requirement', [$job->code, $currantLang]) }}" class="btn btn-primary w-100 rounded-pill shadow-sm mt-auto">
              View Details <i class="bx bx-right-arrow-alt ms-1"></i>
            </a>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="glass-card p-12 text-center">
            <div class="avatar avatar-xl bg-label-secondary mx-auto mb-6">
              <i class="bx bx-folder-open fs-1"></i>
            </div>
            <h3 class="fw-bold">No Openings Right Now</h3>
            <p class="text-muted">We don't have any active job postings at the moment. Please check back soon!</p>
          </div>
        </div>
      @endforelse
    </div>
  </div>

  {{-- Footer --}}
  <footer class="py-10 border-top bg-white">
    <div class="container text-center">
      <p class="mb-0 text-muted small">&copy; {{ date('Y') }} {{ isset($companySettings['footer_text']->value) ? $companySettings['footer_text']->value : 'Hi Tech HRX' }}. All rights reserved.</p>
    </div>
  </footer>
</div>
@endsection
