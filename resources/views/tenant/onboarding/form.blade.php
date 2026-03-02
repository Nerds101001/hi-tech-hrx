@php
  use App\Enums\Gender;
  use App\Helpers\StaticDataHelpers;
  use Illuminate\Support\Facades\Auth;
  $banks = StaticDataHelpers::getIndianBanksList();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Onboarding Form')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/bs-stepper/bs-stepper.scss',
    'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
  ])
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
        --primary-teal: #006D77;
        --deep-teal: #004d54;
        --bg-light: #F8FAFC;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background-color: var(--bg-light) !important;
    }

    /* EXACT HEADER BRANDING */
    .hitech-header {
        background: #fff;
        border-bottom: 1px solid #E2E8F0;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 3rem;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .brand-logo-area {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .brand-icon-box {
        width: 40px;
        height: 40px;
        background: var(--primary-teal);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .brand-text {
        font-weight: 800;
        font-size: 1.25rem;
        letter-spacing: -0.02em;
        color: var(--deep-teal);
        margin: 0;
    }

    .brand-text span {
        color: var(--primary-teal);
        opacity: 0.9;
    }

    /* MINIMALIST STEPPER */
    .hitech-stepper-wrapper {
        max-width: 900px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        border: 2px solid #E2E8F0;
        background: white;
        color: #94A3B8;
        transition: all 0.3s ease;
    }

    .step-custom.active .step-circle {
        border-color: var(--primary-teal);
        background: var(--primary-teal);
        color: white;
        box-shadow: 0 8px 20px rgba(0, 109, 119, 0.25);
    }

    .step-custom.completed .step-circle {
        border-color: var(--primary-teal);
        background: white;
        color: var(--primary-teal);
    }

    .step-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-top: 0.5rem;
        color: #94A3B8;
    }

    .step-custom.active .step-label {
        color: var(--deep-teal);
    }

    .step-custom.completed .step-label {
        color: var(--primary-teal);
    }

    .stepper-line {
        height: 2px;
        background: #E2E8F0;
        flex: 1;
        margin: 0 1rem;
        transform: translateY(-12px);
    }

    .stepper-line.active {
        background: var(--primary-teal);
    }

    /* FORM CARD */
    .onboarding-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 10px 40px rgba(31, 38, 135, 0.05);
        overflow: hidden;
        margin-bottom: 5rem;
    }

    .card-header-hitech {
        background: var(--deep-teal);
        padding: 2.5rem 3rem;
        color: white;
    }

    .card-header-hitech h2 {
        font-weight: 800;
        font-size: 1.75rem;
        margin: 0;
        color: white;
    }

    .card-header-hitech p {
        opacity: 0.8;
        margin: 0.5rem 0 0;
        font-size: 0.95rem;
    }

    .card-body-hitech {
        padding: 3rem;
    }

    /* INPUTS */
    .hitech-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748B;
        letter-spacing: 0.08em;
        margin-bottom: 0.5rem;
    }

    .hitech-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        background: #F8FAFC;
        font-size: 0.95rem;
        transition: all 0.2s;
        outline: none;
        color: #0f172a;
    }
    
    .hitech-input::placeholder {
        color: #94A3B8;
    }

    .hitech-input:focus {
        background: white;
        border-color: var(--primary-teal);
        box-shadow: 0 0 0 4px rgba(0, 109, 119, 0.08);
    }

    /* SECTION TITLES INSIDE FORM */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--deep-teal);
        margin-top: 2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .section-title:first-child {
        margin-top: 0;
    }

    /* SECURITY BOX */
    .security-notice {
        background: rgba(0, 109, 119, 0.04);
        border: 1px solid rgba(0, 109, 119, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .security-notice-icon {
        color: var(--primary-teal);
        font-size: 1.5rem;
    }

    .security-notice h4 {
        font-size: 14px;
        font-weight: 800;
        color: var(--primary-teal);
        margin-bottom: 0.25rem;
    }

    .security-notice p {
        font-size: 12px;
        color: #64748B;
        margin: 0;
        line-height: 1.5;
    }

    /* STICKY FOOTER */
    .hitech-footer {
        background: white;
        border-top: 1px solid #E2E8F0;
        padding: 1.5rem 3rem;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
        display: flex;
        justify-content: center;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.03);
    }

    .footer-content {
        max-width: 900px;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-prev-hitech {
        background: white;
        border: 1px solid #E2E8F0;
        color: #64748B;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-prev-hitech:hover {
        color: var(--deep-teal);
        background: #F8FAFC;
    }

    .btn-next-hitech {
        background: var(--deep-teal);
        color: white !important;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        float: right;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 12px rgba(0, 77, 84, 0.15);
    }

    .btn-next-hitech:hover {
        background: var(--primary-teal);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 109, 119, 0.2);
    }

    /* HIDE DEFAULT STEPPER STUFF */
    .bs-stepper-header { display: none !important; }

    /* Custom Checkbox */
    .custom-checkbox {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
    }
    
    .custom-checkbox input[type="checkbox"] {
      width: 18px;
      height: 18px;
      border-radius: 4px;
      border: 1px solid #CBD5E1;
      accent-color: var(--primary-teal);
      cursor: pointer;
    }

  </style>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/bs-stepper/bs-stepper.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  ])
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const stepperEl = document.querySelector('#wizard-onboarding');
      if (stepperEl) {
        const onboardingStepper = new Stepper(stepperEl, {
          linear: false, // Set to false for testing
          animation: true
        });

        window.goNext = () => onboardingStepper.next();
        window.goPrev = () => onboardingStepper.previous();

        // Update custom indicator on change
        stepperEl.addEventListener('show.bs-stepper', function (event) {
          const stepIdx = event.detail.indexStep + 1;
          const circles = document.querySelectorAll('.step-circle-custom');
          const lines = document.querySelectorAll('.stepper-line-custom');
          const labels = document.querySelectorAll('.step-label-custom');

          circles.forEach((c, idx) => {
            if (idx < stepIdx - 1) {
              c.closest('.step-custom').classList.add('completed');
              c.closest('.step-custom').classList.remove('active');
            } else if (idx === stepIdx - 1) {
              c.closest('.step-custom').classList.add('active');
              c.closest('.step-custom').classList.remove('completed');
            } else {
              c.closest('.step-custom').classList.remove('active', 'completed');
            }
          });

          lines.forEach((l, idx) => {
            if (idx < stepIdx - 1) l.classList.add('active');
            else l.classList.remove('active');
          });
          
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
      }

      // Handle Marital Status Toggle
      const maritalSelect = document.getElementById('marital_status');
      if(maritalSelect) {
          maritalSelect.addEventListener('change', function() {
              const spouseDiv = document.getElementById('spouse-details-div');
              if (this.value === 'married') spouseDiv.style.display = 'block';
              else spouseDiv.style.display = 'none';
          });
      }

      // Handle Address Toggle
      const addressToggle = document.getElementById('same_as_permanent');
      if(addressToggle) {
          addressToggle.addEventListener('change', function() {
              const tempAddressDiv = document.getElementById('temporary-address-section');
              if (this.checked) tempAddressDiv.style.display = 'none';
              else tempAddressDiv.style.display = 'block';
          });
      }
    });

  </script>
@endsection

@section('content')
<div class="hitech-header">
    <div class="brand-logo-area">
        <div class="brand-icon-box">
            <i class="bx bx-layer"></i>
        </div>
        <h1 class="brand-text">HI TECH <span>HRX</span></h1>
    </div>
    <div class="header-actions">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0 align-items-center d-flex gap-2" href="javascript:void(0);" data-bs-toggle="dropdown" style="border: 1px solid rgba(0,0,0,0.05); border-radius: 50px; padding: 0.25rem 0.75rem !important; background-color: #f8f9fa;">
                <div class="avatar avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background-color: var(--primary-teal); color: white;">
                    @if (Auth::user() && !is_null(Auth::user()->profile_picture))
                        <img src="{{ Auth::user()->getProfilePicture() }}" alt class="w-100 h-100 rounded-circle object-fit-cover">
                    @else
                        <span class="fw-semibold" style="font-size: 0.8rem;">{{ Auth::user()->getInitials() }}</span>
                    @endif
                </div>
                <div class="d-none d-md-flex flex-column text-start justify-content-center" style="line-height: 1.1;">
                    <span class="fw-bold text-heading" style="font-size: 0.75rem;">{{ Auth::user() ? Auth::user()->getFullName() : 'Candidate' }}</span>
                    <span class="text-muted" style="font-size: 0.65rem;">Onboarding</span>
                </div>
                <i class="bx bx-chevron-down text-muted ms-1" style="font-size: 1rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 12px; margin-top: 10px;">
                <li>
                    <a class="dropdown-item text-danger fw-bold" href="{{ route('auth.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class='bx bx-log-out me-2'></i><span>Logout</span>
                    </a>
                </li>
            </ul>
            <form method="POST" id="logout-form" action="{{ route('auth.logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>

<div class="hitech-stepper-wrapper">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="step-custom active d-flex flex-column align-items-center">
            <div class="step-circle step-circle-custom">1</div>
            <span class="step-label step-label-custom">Personal</span>
        </div>
        <div class="stepper-line stepper-line-custom"></div>
        <div class="step-custom d-flex flex-column align-items-center">
            <div class="step-circle step-circle-custom">2</div>
            <span class="step-label step-label-custom">Contact</span>
        </div>
        <div class="stepper-line stepper-line-custom"></div>
        <div class="step-custom d-flex flex-column align-items-center">
            <div class="step-circle step-circle-custom">3</div>
            <span class="step-label step-label-custom">Banking</span>
        </div>
        <div class="stepper-line stepper-line-custom"></div>
        <div class="step-custom d-flex flex-column align-items-center">
            <div class="step-circle step-circle-custom">4</div>
            <span class="step-label step-label-custom">Docs</span>
        </div>
        <div class="stepper-line stepper-line-custom"></div>
        <div class="step-custom d-flex flex-column align-items-center">
            <div class="step-circle step-circle-custom">5</div>
            <span class="step-label step-label-custom">Review</span>
        </div>
    </div>

    <div id="wizard-onboarding" class="bs-stepper">
        <div class="bs-stepper-header" role="tablist">
          <div class="step" data-target="#personal-info"><button type="button" class="step-trigger" role="tab"></button></div>
          <div class="step" data-target="#contact-info"><button type="button" class="step-trigger" role="tab"></button></div>
          <div class="step" data-target="#bank-details"><button type="button" class="step-trigger" role="tab"></button></div>
          <div class="step" data-target="#documents"><button type="button" class="step-trigger" role="tab"></button></div>
          <div class="step" data-target="#legal-consent"><button type="button" class="step-trigger" role="tab"></button></div>
        </div>

        <div class="bs-stepper-content p-0 m-0">
          <form id="onboardingForm" method="POST" action="{{ route('onboarding.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Step 1: Personal Info -->
            <div id="personal-info" class="content onboarding-card">
              <div class="card-header-hitech">
                <h2>Personal Information</h2>
                <p>Please provide your basic family and personal details.</p>
              </div>
              <div class="card-body-hitech">
                
                <h4 class="section-title">Core Identity</h4>
                <div class="row g-4 mb-4">
                  <div class="col-md-6">
                    <label class="hitech-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="hitech-input" value="{{ $user->first_name }}" placeholder="First Name" required>
                  </div>
                  <div class="col-md-6">
                    <label class="hitech-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="hitech-input" value="{{ $user->last_name }}" placeholder="Last Name" required>
                  </div>
                  <div class="col-md-4">
                    <label class="hitech-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="dob" class="hitech-input" value="{{ $user->dob }}" required>
                  </div>
                   <div class="col-md-4">
                    <label class="hitech-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="hitech-input" required>
                      <option value="">Select Gender</option>
                      @foreach(Gender::cases() as $gender)
                        <option value="{{$gender->value}}" {{ $user->gender == $gender->value ? 'selected' : '' }}>{{ucfirst($gender->value)}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="hitech-label">Blood Group</label>
                    <select name="blood_group" class="hitech-input">
                      <option value="">Select</option>
                      @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                        <option value="{{$bg}}" {{ $user->blood_group == $bg ? 'selected' : '' }}>{{$bg}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <h4 class="section-title">Family Details</h4>
                <div class="row g-4 mb-4">
                  <div class="col-md-6">
                    <label class="hitech-label">Father's Name <span class="text-danger">*</span></label>
                    <input type="text" name="father_name" class="hitech-input" value="{{ $user->father_name }}" placeholder="Full Name" required>
                  </div>
                  <div class="col-md-6">
                    <label class="hitech-label">Mother's Name <span class="text-danger">*</span></label>
                    <input type="text" name="mother_name" class="hitech-input" value="{{ $user->mother_name }}" placeholder="Full Name" required>
                  </div>
                  
                  <div class="col-md-6">
                    <label class="hitech-label">Marital Status <span class="text-danger">*</span></label>
                    <select name="marital_status" id="marital_status" class="hitech-input" required>
                      <option value="single" {{ $user->marital_status == 'single' ? 'selected' : '' }}>Single</option>
                      <option value="married" {{ $user->marital_status == 'married' ? 'selected' : '' }}>Married</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                     <label class="hitech-label">No. of Children</label>
                     <input type="number" name="no_of_children" class="hitech-input" value="{{ $user->no_of_children ?? 0 }}">
                  </div>

                  <div class="col-12" id="spouse-details-div" style="display: {{ $user->marital_status == 'married' ? 'block' : 'none' }};">
                    <div class="row g-4">
                      <div class="col-md-6">
                        <label class="hitech-label">Spouse Name</label>
                        <input type="text" name="spouse_name" class="hitech-input" value="{{ $user->spouse_name }}" placeholder="Spouse Full Name">
                      </div>
                    </div>
                  </div>
                </div>

                <h4 class="section-title">Nationality & Citizenship</h4>
                <div class="row g-4">
                  <div class="col-md-6">
                    <label class="hitech-label">Citizenship</label>
                    <input type="text" name="citizenship" class="hitech-input" value="{{ $user->citizenship }}" placeholder="e.g. Indian">
                  </div>
                  <div class="col-md-6">
                    <label class="hitech-label">Birth Country</label>
                    <input type="text" name="birth_country" class="hitech-input" value="{{ $user->birth_country }}" placeholder="e.g. India">
                  </div>
                </div>

              </div>
              <div class="hitech-footer">
                <div class="footer-content">
                  <div style="flex:1"></div>
                  <button type="button" onclick="goNext()" class="btn-next-hitech">Continue <i class="bx bx-right-arrow-alt"></i></button>
                </div>
              </div>
            </div>

            <!-- Step 2: Contact & Address -->
            <div id="contact-info" class="content onboarding-card">
              <div class="card-header-hitech">
                <h2>Contact & Address</h2>
                <p>How we can reach you and your emergency contacts.</p>
              </div>
              <div class="card-body-hitech">
                
                <h4 class="section-title">Contact Methods</h4>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="hitech-label">Personal Email</label>
                        <!-- Disable email if you don't want them to change login details -->
                        <input type="email" name="email" class="hitech-input" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Primary Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="hitech-input" value="{{ $user->phone }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Alternate Phone</label>
                        <input type="text" name="alternate_number" class="hitech-input" value="{{ $user->alternate_number }}">
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Home Phone</label>
                        <input type="text" name="home_phone" class="hitech-input" value="{{ $user->home_phone }}">
                    </div>
                </div>

                <h4 class="section-title">Permanent Address</h4>
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <label class="hitech-label">Street Address <span class="text-danger">*</span></label>
                        <input type="text" name="perm_street" class="hitech-input" value="{{ $user->perm_street }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Building / Apt</label>
                        <input type="text" name="perm_building" class="hitech-input" value="{{ $user->perm_building }}">
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="perm_city" class="hitech-input" value="{{ $user->perm_city }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">State <span class="text-danger">*</span></label>
                        <input type="text" name="perm_state" class="hitech-input" value="{{ $user->perm_state }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">ZIP Code <span class="text-danger">*</span></label>
                        <input type="text" name="perm_zip" class="hitech-input" value="{{ $user->perm_zip }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Country <span class="text-danger">*</span></label>
                        <input type="text" name="perm_country" class="hitech-input" value="{{ $user->perm_country ?? 'India' }}" required>
                    </div>
                </div>

                <h4 class="section-title d-flex justify-content-between align-items-center">
                    Current Address
                    <label class="custom-checkbox fw-normal" style="font-size: 0.85rem;">
                        <input type="checkbox" id="same_as_permanent" name="same_as_permanent" value="1">
                        Same as Permanent
                    </label>
                </h4>
                <div class="row g-4 mb-4" id="temporary-address-section">
                    <div class="col-12">
                        <label class="hitech-label">Street Address</label>
                        <input type="text" name="temp_street" class="hitech-input" value="{{ $user->temp_street }}">
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Building / Apt</label>
                        <input type="text" name="temp_building" class="hitech-input" value="{{ $user->temp_building }}">
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">City</label>
                        <input type="text" name="temp_city" class="hitech-input" value="{{ $user->temp_city }}">
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">State</label>
                        <input type="text" name="temp_state" class="hitech-input" value="{{ $user->temp_state }}">
                    </div>
                     <div class="col-md-6">
                        <label class="hitech-label">ZIP Code</label>
                        <input type="text" name="temp_zip" class="hitech-input" value="{{ $user->temp_zip }}">
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Country</label>
                        <input type="text" name="temp_country" class="hitech-input" value="{{ $user->temp_country }}">
                    </div>
                </div>

                <h4 class="section-title">Emergency Contact</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="hitech-label">Contact Name <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_name" class="hitech-input" value="{{ $user->emergency_contact_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Relationship <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_relation" class="hitech-input" value="{{ $user->emergency_contact_relation }}" placeholder="e.g. Sister" required>
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_phone" class="hitech-input" value="{{ $user->emergency_contact_phone }}" required>
                    </div>
                </div>

              </div>
              <div class="hitech-footer">
                <div class="footer-content">
                  <button type="button" onclick="goPrev()" class="btn-prev-hitech"><i class="bx bx-left-arrow-alt"></i> Back</button>
                  <button type="button" onclick="goNext()" class="btn-next-hitech">Continue <i class="bx bx-right-arrow-alt"></i></button>
                </div>
              </div>
            </div>

            <!-- Step 3: Bank Details -->
            <div id="bank-details" class="content onboarding-card">
              <div class="card-header-hitech">
                <h2>Banking Information</h2>
                <p>Details for your monthly salary disbursement.</p>
              </div>
              <div class="card-body-hitech">
                <div class="security-notice">
                    <i class="bx bxs-shield-check security-notice-icon"></i>
                    <div>
                        <h4>Data Security</h4>
                        <p>Your financial information is encrypted and accessible only to the payroll department. Access will be granted after HR approval.</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="hitech-label">Bank Name <span class="text-danger">*</span></label>
                        <select name="bank_name" class="hitech-input" required>
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank) <option value="{{ $bank }}">{{ $bank }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Account Holder Name <span class="text-danger">*</span></label>
                        <input type="text" name="account_name" class="hitech-input" value="{{ $user->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">Account Number <span class="text-danger">*</span></label>
                        <input type="password" name="account_number" class="hitech-input" placeholder="•••• •••• ••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">IFSC / Routing Number <span class="text-danger">*</span></label>
                        <input type="text" name="ifsc_code" class="hitech-input" placeholder="8-12 character code" required>
                    </div>
                </div>
              </div>
              <div class="hitech-footer">
                <div class="footer-content">
                  <button type="button" onclick="goPrev()" class="btn-prev-hitech"><i class="bx bx-left-arrow-alt"></i> Back</button>
                  <button type="button" onclick="goNext()" class="btn-next-hitech">Continue <i class="bx bx-right-arrow-alt"></i></button>
                </div>
              </div>
            </div>

            <!-- Step 4: Documents -->
            <div id="documents" class="content onboarding-card">
              <div class="card-header-hitech">
                <h2>Identity & Documents</h2>
                <p>Upload digital copies of your identification certificates.</p>
              </div>
              <div class="card-body-hitech">
                
                <h4 class="section-title">Profile Photo</h4>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="hitech-label">Upload Display Picture <span class="text-danger">*</span></label>
                        <input type="file" name="photo" class="hitech-input" accept="image/*" required>
                    </div>
                </div>

                <h4 class="section-title">National Identity</h4>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="hitech-label">Aadhaar Card Number <span class="text-danger">*</span></label>
                        <input type="text" name="aadhaar_no" class="hitech-input mb-2" value="{{ $user->aadhaar_no }}" placeholder="12-digit number" required>
                        <input type="file" name="aadhaar_file" class="hitech-input" accept=".pdf,.png,.jpg,.jpeg" required>
                    </div>
                    <div class="col-md-6">
                        <label class="hitech-label">PAN Card Number <span class="text-danger">*</span></label>
                        <input type="text" name="pan_no" class="hitech-input mb-2" value="{{ $user->pan_no }}" placeholder="10-digit number" required>
                        <input type="file" name="pan_file" class="hitech-input" accept=".pdf,.png,.jpg,.jpeg" required>
                    </div>
                </div>

                <h4 class="section-title">Travel & Visa (Optional)</h4>
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">Complete these if relevant to your employment or citizenship status.</p>
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Passport Number</label>
                        <input type="text" name="passport_no" class="hitech-input" value="{{ $user->passport_no }}">
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Issue Date</label>
                        <input type="date" name="passport_issue_date" class="hitech-input" value="{{ $user->passport_issue_date }}">
                    </div>
                    <div class="col-md-4">
                        <label class="hitech-label">Expiry Date</label>
                        <input type="date" name="passport_expiry_date" class="hitech-input" value="{{ $user->passport_expiry_date }}">
                    </div>
                    
                    <div class="col-md-4 mt-3">
                        <label class="hitech-label">Visa Type</label>
                        <input type="text" name="visa_type" class="hitech-input" value="{{ $user->visa_type }}">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="hitech-label">Visa Issue Date</label>
                        <input type="date" name="visa_issue_date" class="hitech-input" value="{{ $user->visa_issue_date }}">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="hitech-label">Visa Expiry Date</label>
                        <input type="date" name="visa_expiry_date" class="hitech-input" value="{{ $user->visa_expiry_date }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="hitech-label">FRRO Registration</label>
                        <input type="text" name="frro_registration" class="hitech-input" value="{{ $user->frro_registration }}">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="hitech-label">FRRO Issue Date</label>
                        <input type="date" name="frro_issue_date" class="hitech-input" value="{{ $user->frro_issue_date }}">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="hitech-label">FRRO Expiry Date</label>
                        <input type="date" name="frro_expiry_date" class="hitech-input" value="{{ $user->frro_expiry_date }}">
                    </div>
                </div>

              </div>
              <div class="hitech-footer">
                <div class="footer-content">
                  <button type="button" onclick="goPrev()" class="btn-prev-hitech"><i class="bx bx-left-arrow-alt"></i> Back</button>
                  <button type="button" onclick="goNext()" class="btn-next-hitech">Continue <i class="bx bx-right-arrow-alt"></i></button>
                </div>
              </div>
            </div>

            <!-- Step 5: Consent -->
            <div id="legal-consent" class="content onboarding-card">
              <div class="card-header-hitech">
                <h2>Declaration & Review</h2>
                <p>Please verify all information before final submission.</p>
              </div>
              <div class="card-body-hitech">
                <div class="security-notice mb-8">
                    <i class="bx bx-check-shield security-notice-icon"></i>
                    <div>
                        <h4>Final Declaration</h4>
                        <p>I hereby declare that the information provided is true and correct to the best of my knowledge and belief. I understand that any misrepresentation may result in termination of onboarding.</p>
                    </div>
                </div>
                <div class="p-4 border rounded" style="background-color: #F8FAFC; border-color: #E2E8F0;">
                  <label class="custom-checkbox fw-bold text-dark w-100" style="font-size: 0.95rem;">
                      <input type="checkbox" id="consent_accepted" name="consent_accepted" required>
                      I confirm that all banking, personal, and identity details are accurate.
                  </label>
                </div>
              </div>
              <div class="hitech-footer">
                <div class="footer-content">
                  <button type="button" onclick="goPrev()" class="btn-prev-hitech"><i class="bx bx-left-arrow-alt"></i> Back</button>
                  <button type="submit" class="btn-next-hitech">Complete Onboarding <i class="bx bx-check-circle"></i></button>
                </div>
              </div>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection
