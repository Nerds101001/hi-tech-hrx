@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login - Hitech Secure Gateway')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
<div class="hitech-gateway-wrapper">
  <div class="gateway-container">
    <div class="gateway-card animate__animated animate__zoomIn">

      {{-- LEFT IDENTITY PANEL (desktop only) --}}
      <div class="identity-panel animate__animated animate__fadeInLeft">
        <div class="content">
          <img src="{{ asset('assets/img/logo-white.png') }}" alt="Logo" class="identity-logo">
          <h1>Administrative Control Center</h1>
          <div class="features">
            <div class="feature-item">
              <i class="bx bx-check-shield"></i>
              <p>End-to-end encrypted administrative session</p>
            </div>
            <div class="feature-item">
              <i class="bx bx-bar-chart-alt-2"></i>
              <p>All access attempts are logged and audited</p>
            </div>
            <div class="feature-item">
              <i class="bx bx-lock-alt"></i>
              <p>256-bit SSL secured connection</p>
            </div>
          </div>
        </div>
        <div class="footer-note">SYSTEM V4.2.0-ADMIN</div>
      </div>

      {{-- RIGHT PANEL --}}
      <div class="form-panel animate__animated animate__fadeInRight" style="animation-delay: 0.1s">

        {{-- MOBILE BRAND BANNER — shown only on phones via CSS --}}
        <div class="mobile-brand-banner">
          <img src="{{ asset('assets/img/logo-white.png') }}" alt="Logo" class="mobile-logo">
          <h2>Administrative Control Center</h2>
          <p>Secure Gateway — Verify your identity to continue</p>
          <span class="version-badge">SYSTEM V4.2.0-ADMIN</span>
        </div>

        {{-- THE ONE AND ONLY FORM — wrapped in .form-inner for styling --}}
        <div class="form-inner">

          {{-- ROLE SWITCHER --}}
          <div class="hitech-role-switcher">
            <div class="switcher-pill">
              <button type="button" class="role-option role-employee-btn" onclick="switchRole('employee')">Employee / Manager</button>
              <button type="button" class="role-option active role-admin-btn" onclick="switchRole('admin')">Admin / HR</button>
            </div>
          </div>

          <div class="form-header">
            <h2 class="login-title">Secure Login</h2>
            <p class="login-subtitle">Please verify your identity to access the management portal.</p>
          </div>

          <form action="{{ route('auth.loginPost') }}" method="POST" onsubmit="return validateAndSubmit(event)">
            @csrf

            <div class="mb-2">
              <label class="form-label label-username">ADMINISTRATOR EMAIL</label>
              <div class="hitech-input-group">
                <i class="bx bx-envelope group-icon"></i>
                <input type="email" class="form-control input-email" name="email"
                  placeholder="admin@example.com" value="{{ old('email') }}" required autofocus>
              </div>
              @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="mb-2">
              <label class="form-label">PASSWORD</label>
              <div class="hitech-input-group">
                <i class="bx bx-lock-alt group-icon"></i>
                <input type="password" class="form-control" name="password"
                  placeholder="············" required />
              </div>
              @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            {{-- CAPTCHA --}}
            <div class="mb-3">
              <label class="form-label">VERIFICATION</label>
              <div class="hitech-captcha-box">
                <div class="captcha-display">
                  <span class="captcha-code-display code-text">8B2K</span>
                  <button type="button" class="refresh-btn" onclick="generateCaptcha()"><i class="bx bx-refresh"></i></button>
                </div>
                <input type="text" class="form-control captcha-input-field" placeholder="Enter code" required>
              </div>
              <div class="captcha-error text-danger small mt-1" style="display:none;">Invalid captcha code.</div>
            </div>

            <button class="btn btn-primary d-flex align-items-center justify-content-center w-100 hitech-btn-admin" type="submit">
              <span class="btn-text">Admin Access</span>
              <i class="bx bx-right-arrow-alt ms-2"></i>
            </button>
          </form>

          <div class="form-footer">
            <a href="{{ route('password.request') }}" class="footer-link link-forgot">Forgot Admin Password?</a>
          </div>

        </div>{{-- end .form-inner --}}
      </div>{{-- end .form-panel --}}

    </div>
  </div>
</div>

<script>
  let currentCaptcha = '';

  function generateCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let code = '';
    for (let i = 0; i < 4; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
    currentCaptcha = code;
    document.querySelectorAll('.captcha-code-display').forEach(el => el.innerText = code);
  }

  function switchRole(role) {
    const empBtns  = document.querySelectorAll('.role-employee-btn');
    const adminBtns = document.querySelectorAll('.role-admin-btn');
    const isEmployee = role === 'employee';

    empBtns.forEach(b => b.classList.toggle('active', isEmployee));
    adminBtns.forEach(b => b.classList.toggle('active', !isEmployee));

    document.querySelectorAll('.label-username').forEach(l => l.innerText = isEmployee ? 'EMPLOYEE ID / EMAIL' : 'ADMINISTRATOR EMAIL');
    document.querySelectorAll('.input-email').forEach(i => i.placeholder = isEmployee ? 'employee@example.com' : 'admin@example.com');
    document.querySelectorAll('.login-title').forEach(t => t.innerText = isEmployee ? 'Staff Login' : 'Secure Login');
    document.querySelectorAll('.login-subtitle').forEach(s => s.innerText = isEmployee ? 'Access your personal portal and records.' : 'Please verify your identity to access the management portal.');
    document.querySelectorAll('.btn-text').forEach(b => b.innerText = isEmployee ? 'Portal Access' : 'Admin Access');
    document.querySelectorAll('.link-forgot').forEach(l => l.innerText = isEmployee ? 'Forgot Password?' : 'Forgot Admin Password?');
  }

  function validateAndSubmit(e) {
    const input = document.querySelector('.captcha-input-field');
    if (!input || input.value.toUpperCase() !== currentCaptcha) {
      e.preventDefault();
      document.querySelectorAll('.captcha-error').forEach(el => el.style.display = 'block');
      generateCaptcha();
      return false;
    }
    document.querySelectorAll('.captcha-error').forEach(el => el.style.display = 'none');
    return true;
  }

  window.onload = generateCaptcha;
</script>
@endsection
