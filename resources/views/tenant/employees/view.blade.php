@php
    use App\Enums\IncentiveType;
    use App\Enums\UserAccountStatus;
    use App\Services\AddonService\IAddonService;
    use Carbon\Carbon;
    use App\Helpers\StaticDataHelpers;
    $role = $user->roles()->first()->name ?? '';
    $addonService = app(IAddonService::class);
@endphp
@extends('layouts.layoutMaster')

@section('title', 'Employee Details')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-user-view.scss', 'resources/assets/css/employee-view.css'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection
@section('content')

@php
    $settings = \App\Models\Settings::first();
@endphp

<div class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $user->getFullName() }}</h4>
            <span class="text-muted" style="font-size: 0.85rem;">Manage employee details and financial information.</span>
        </div>
        <div>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm d-flex align-items-center" style="font-size: 0.8rem; font-weight: 500;">
                <i class="bx bx-arrow-back me-1" style="font-size: 1rem;"></i> Back to Employees
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-4">
            <!-- User Card -->
            <div class="card mb-4 border-0 shadow-sm position-relative overflow-hidden" style="border-radius: 12px;">
                <div style="height: 6px; background-color: #127464; position: absolute; top: 0; left: 0; right: 0;"></div>
                <div class="card-body pt-5">
                    <div class="user-avatar-section text-center position-relative mb-4">
                        <!-- Profile Picture -->
                        <div class="profile-picture-container position-relative d-inline-block" style="width: 110px; height: 110px;">
                            @if ($user->profile_picture)
                                <img class="img-fluid rounded-circle w-100 h-100 border border-4 border-white shadow-sm" src="{{ $user->getProfilePicture() }}" alt="User avatar" id="userProfilePicture" style="object-fit: cover;" />
                            @else
                                <div class="rounded-circle w-100 h-100 d-flex align-items-center justify-content-center border border-4 border-white shadow-sm" style="background-color: #127464; color: white;">
                                    <h2 class="mb-0 text-white fw-bold">{{ $user->getInitials() }}</h2>
                                </div>
                            @endif
                        </div>
                        <h5 class="mt-3 mb-1 fw-bold" style="color: #1E293B; font-size: 1.25rem;">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        <p class="text-muted mb-2" style="font-size: 0.9rem; font-weight: 500;">{{ $user->designation ? $user->designation->name : 'N/A' }}</p>
                        
                        @if($user->status == UserAccountStatus::ACTIVE)
                            <span class="badge" style="background-color: #E0F2F1; color: #127464; font-size: 0.7rem; font-weight: 700; padding: 0.4em 1em; border-radius: 50px;">ACTIVE</span>
                        @elseif($user->status == UserAccountStatus::TERMINATED)
                            <span class="badge bg-label-danger" style="font-size: 0.7rem; font-weight: 700; padding: 0.4em 1em; border-radius: 50px;">TERMINATED</span>
                        @else
                            <span class="badge bg-label-warning" style="font-size: 0.7rem; font-weight: 700; padding: 0.4em 1em; border-radius: 50px;">{{ strtoupper($user->status->value) }}</span>
                        @endif
                    </div>

                    <div class="border-top pt-4">
                        <ul class="list-unstyled mb-0" style="font-size: 0.85rem; color: #64748B;">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bx bx-qr text-muted me-2" style="font-size: 1.1rem; width: 20px;"></i>
                                ID: {{ $user->code }}
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bx bx-envelope text-muted me-2" style="font-size: 1.1rem; width: 20px;"></i>
                                {{ $user->email }}
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bx bx-phone text-muted me-2" style="font-size: 1.1rem; width: 20px;"></i>
                                {{ $user->phone ? ($settings->phone_country_code ? '+' . ltrim($settings->phone_country_code, '+') . '-' : '') . $user->phone : 'N/A' }}
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bx bx-calendar text-muted me-2" style="font-size: 1.1rem; width: 20px;"></i>
                                {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : 'N/A' }}
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bx bx-building-house text-muted me-2" style="font-size: 1.1rem; width: 20px;"></i>
                                {{ $user->department ? $user->department->name : 'N/A Dept.' }} | Head Office
                            </li>
                            <li class="mb-0 d-flex align-items-center">
                                <i class="bx bx-file text-muted me-2" style="font-size: 1.1rem; width: 20px;"></i>
                                Contract: Full-Time
                            </li>
                        </ul>
                    </div>

                    <!-- Hidden File Input for Profile Picture Upload -->
                    <form id="profilePictureForm" action="{{ route('employees.changeEmployeeProfilePicture') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
                        <input type="file" id="file" name="file" accept="image/*">
                    </form>
                </div>
            </div>
            <!-- /User Card -->


            <!-- Management Control Section -->
            <div class="card emp-card mb-4 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                    <h6 class="fw-bold mb-0 text-white small text-uppercase" style="letter-spacing: 1.5px;"><i class="bx bx-shield-quarter me-2"></i>Management Control</h6>
                    <span class="badge {{ $user->status == UserAccountStatus::ACTIVE ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 0.6rem; letter-spacing: 1px;">{{ strtoupper($user->status->name ?? $user->status) }}</span>
                </div>
                <div class="card-body p-4 bg-white">
                    @if ($user->status == \App\Enums\UserAccountStatus::TERMINATED || $user->status == \App\Enums\UserAccountStatus::RELIEVED || $user->status == \App\Enums\UserAccountStatus::RETIRED)
                        <div class="p-4 rounded-4 mb-0 text-center border" style="background-color: #f8fafc; border-style: dashed !important; border-width: 2px !important; border-color: #e2e8f0 !important;">
                            @if($user->status == \App\Enums\UserAccountStatus::TERMINATED)
                                <div class="icon-stat-danger mb-3 mx-auto" style="width: 50px; height: 50px; background: #fee2e2; color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="bx bx-block fs-3"></i></div>
                                <h6 class="fw-extrabold text-danger mb-1">TERMINATED</h6>
                                <p class="text-muted mb-0 small">Access Revoked: {{ $user->exit_date ? Carbon::parse($user->exit_date)->format('d M Y') : 'N/A' }}</p>
                            @else
                                <div class="icon-stat-warning mb-3 mx-auto" style="width: 50px; height: 50px; background: #fef3c7; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="bx bx-exit fs-3"></i></div>
                                <h6 class="fw-extrabold text-warning mb-1">{{ strtoupper($user->status->name ?? $user->status) }}</h6>
                                <p class="text-muted mb-0 small">Effective On: {{ Carbon::parse($user->relieved_at ?? $user->retired_at)->format('d M Y') }}</p>
                            @endif
                        </div>
                    @else
                        <!-- Status Selection -->
                        <div class="p-3 rounded-3 mb-4 bg-light border-start border-primary border-4 shadow-xs">
                             <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-0 fw-bold text-dark small">Active Employment</p>
                                    <span class="smallest text-muted">Toggle access to HR portal</span>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input hitech-awesome-toggle" style="width: 3em !important; height: 1.5em !important;" type="checkbox" id="employeeStatusToggle" 
                                        @if ($user->status == \App\Enums\UserAccountStatus::ACTIVE) checked @endif 
                                        onchange="toggleEmployeeStatus({{ $user->id }}, this.checked)">
                                </div>
                            </div>
                        </div>

                        @if ($user->status == \App\Enums\UserAccountStatus::ONBOARDING_SUBMITTED || $user->status == \App\Enums\UserAccountStatus::ONBOARDING_REQUESTED)
                            <!-- Onboarding Actions -->
                            <div class="d-flex gap-2 mb-4">
                                <button type="button" class="btn btn-success flex-fill rounded-pill fw-bold small py-2 d-flex align-items-center justify-content-center" onclick="approveOnboarding({{ $user->id }})">
                                    <i class="bx bx-check-circle me-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-warning flex-fill rounded-pill fw-bold small py-2 d-flex align-items-center justify-content-center text-dark" onclick="requestModification({{ $user->id }})">
                                    <i class="bx bx-edit-alt me-1"></i> Modification
                                </button>
                            </div>
                        @endif

                        <!-- Probation Section Rewritten -->
                        <div class="probation-awesome-box p-3 rounded-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-bold text-dark small"><i class="bx bx-time-five me-1 text-primary"></i> Probation Period</div>
                                @if ($user->isUnderProbation())
                                    <div class="dropdown">
                                        <button class="btn btn-xs bg-white border text-dark shadow-xs rounded-pill" type="button" data-bs-toggle="dropdown">Process <i class="bx bx-chevron-down ms-1"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="border-radius: 12px;">
                                            <li><a class="dropdown-item py-2 rounded-2" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirmProbationModal"><i class="bx bx-check-circle me-2 text-success"></i>Confirm Success</a></li>
                                            <li><a class="dropdown-item py-2 rounded-2" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#extendProbationModal"><i class="bx bx-calendar-plus me-2 text-warning"></i>Extend Period</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 rounded-2 text-danger" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#failProbationModal"><i class="bx bx-x-circle me-2"></i>Mark Failure</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="smallest text-muted">Status</span>
                                <span class="badge {{ $user->isUnderProbation() ? 'bg-label-warning' : 'bg-label-success' }} rounded-pill" style="font-size: 0.6rem;">{{ $user->probation_status_display }}</span>
                            </div>
                            @if($user->probation_end_date)
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="smallest text-muted">Ends On</span>
                                <span class="fw-bold text-dark smallest">{{ Carbon::parse($user->probation_end_date)->format('d M Y') }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Separation Action -->
                        <button type="button" class="btn btn-outline-danger w-100 rounded-pill fw-bold small p-2" data-bs-toggle="modal" data-bs-target="#terminateEmployeeModal" style="border-style: dashed; border-width: 2px;">
                            <i class="bx bx-user-x me-1"></i> Initiate Termination
                        </button>
                    @endif
                </div>
            </div>

            <div class="text-center mb-4">
                <p class="text-muted" style="font-size: 0.75rem;"> Account created on <strong>{{ Carbon::parse($user->created_at)->format('d M Y') }}</strong> by <strong>{{ $user->createdBy != null ? $user->createdBy->getFullName() : 'Admin' }}.</strong></p>
            </div>
            <!-- /Work Card -->


        </div>
        <!-- User Content -->
        <div class="col-xl-9 col-lg-9 col-md-8 col-12">
            <style>
                /* =================== TAB NAVIGATION =================== */
                .rosemary-nav-tabs-wrapper {
                    background-color: #F8FAFC;
                    border-radius: 50px;
                    border: 1px solid #E2E8F0;
                    padding: 8px;
                    width: 100%;
                    max-width: 100%;
                    margin-bottom: 2rem;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
                    display: flex;
                    align-items: center;
                    position: relative;
                    z-index: 10;
                }
                .rosemary-nav-tabs {
                    display: flex;
                    justify-content: center;
                    flex-wrap: nowrap !important;
                    overflow-x: auto !important;
                    gap: 1.5rem;
                    border: none !important;
                    width: 100%;
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
                .rosemary-nav-tabs::-webkit-scrollbar { display: none; }
                .rosemary-nav-tabs .nav-link {
                    color: #718096 !important;
                    font-weight: 700;
                    font-size: 0.75rem;
                    border: none;
                    padding: 0.75rem 1.5rem !important;
                    border-radius: 50px !important;
                    transition: all 0.3s ease;
                    text-transform: capitalize;
                    letter-spacing: 0.3px;
                    background-color: transparent !important;
                    display: flex;
                    align-items: center;
                    white-space: nowrap !important;
                    flex-shrink: 0;
                }
                .rosemary-nav-tabs .nav-link.active {
                    background-color: #127464 !important;
                    color: #fff !important;
                    box-shadow: 0 4px 12px rgba(18, 116, 100, 0.25);
                }
                .rosemary-nav-tabs .nav-link:hover:not(.active) {
                    background-color: rgba(18, 116, 100, 0.05) !important;
                    color: #127464 !important;
                }

                @media (max-width: 991px) {
                    .rosemary-nav-tabs-wrapper {
                        display: none !important; /* Hide full header on mobile */
                    }
                    .mobile-tab-navigation {
                        display: flex !important;
                        justify-content: space-between;
                        padding: 1rem;
                        background: #fff;
                        border-top: 1px solid #eee;
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        z-index: 100;
                        box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
                    }
                }
                .mobile-tab-navigation { display: none; }

                /* =================== CARDS & BOXES =================== */
                .card, .emp-card, .hitech-card {
                    border: 1px solid #E2E8F0 !important;
                    border-radius: 12px !important;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.03) !important;
                    background: #fff;
                    overflow: hidden;
                    width: 100%;
                }
                .emp-card .card-body { padding: 1.75rem !important; }
                .emp-field-box {
                    background-color: #F8FAFC;
                    border: 1px solid #F1F5F9;
                    border-radius: 10px;
                    padding: 0.85rem 1.1rem;
                    transition: all 0.2s ease;
                }
                .emp-field-box:hover { border-color: #127464; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }

                /* =================== HITECH MODAL STYLES =================== */
                .modal-content-hitech {
                    border: none;
                    border-radius: 18px;
                    overflow: hidden;
                    box-shadow: 0 25px 80px rgba(0,0,0,0.25);
                    animation: hitechZoomIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
                }
                @keyframes hitechZoomIn {
                    from { opacity: 0; transform: scale(0.92) translateY(20px); }
                    to { opacity: 1; transform: scale(1) translateY(0); }
                }
                .modal-header-hitech {
                    background: linear-gradient(135deg, #127464 0%, #0E5A4E 100%);
                    padding: 1.5rem 2rem;
                    border: none;
                }
                .modal-title-hitech { color: #fff; font-weight: 800; font-size: 1.15rem; letter-spacing: -0.02em; }
                .modal-icon-header {
                    background-color: rgba(255,255,255,0.15);
                    border: 1px solid rgba(255,255,255,0.1);
                    backdrop-filter: blur(8px);
                    border-radius: 12px;
                    width: 44px; height: 44px;
                    display: flex; align-items: center; justify-content: center;
                }
                .modal-icon-header i { color: #fff; font-size: 1.4rem; }
                .btn-close-hitech {
                    background: rgba(255,255,255,0.2);
                    border: none; border-radius: 10px;
                    width: 36px; height: 36px;
                    display: flex; align-items: center; justify-content: center;
                    cursor: pointer; transition: 0.2s; color: #fff;
                }
                .btn-close-hitech:hover { background: rgba(255,255,255,0.35); transform: rotate(90deg); }
                .modal-body-hitech { padding: 2rem; background-color: #fff; }
                .form-label-hitech {
                    font-size: 0.72rem; font-weight: 700;
                    text-transform: uppercase; letter-spacing: 0.08em;
                    color: #64748B; margin-bottom: 0.6rem;
                    display: block;
                }
                .form-control-hitech, .form-select-hitech {
                    border-radius: 10px !important;
                    border: 1px solid #E2E8F0;
                    padding: 0.75rem 1rem;
                    font-size: 0.92rem;
                    background-color: #F8FAFC;
                    transition: all 0.2s ease;
                }
                .form-control-hitech:focus, .form-select-hitech:focus {
                    background-color: #fff;
                    border-color: #127464;
                    box-shadow: 0 0 0 4px rgba(18,116,100,0.1);
                    outline: none;
                }
                .btn-hitech-modal-cancel {
                    background-color: #FEE2E2; color: #EF4444;
                    border: 1px solid #FECACA; border-radius: 50px;
                    padding: 0.7rem 2rem; font-weight: 700; font-size: 0.85rem;
                    transition: all 0.2s;
                }
                .btn-hitech-modal-cancel:hover { background-color: #FECACA; }
                .btn-hitech-modal-submit {
                    background: linear-gradient(135deg, #127464 0%, #0E5A4E 100%);
                    color: #fff; border: none; border-radius: 50px;
                    padding: 0.7rem 2.5rem; font-weight: 700; font-size: 0.85rem;
                    box-shadow: 0 4px 15px rgba(18, 116, 100, 0.3);
                    transition: all 0.3s;
                }
                .btn-hitech-modal-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(18, 116, 100, 0.4); }

                /* =================== STATUS BOX IN SIDEBAR =================== */
                .emp-status-box {
                    border: 1px solid #E2E8F0 !important;
                    border-radius: 12px;
                    padding: 1.25rem;
                    background: #fff;
                    margin-bottom: 1.5rem;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
                }
            </style>


            <!-- Tabs Navigation -->
            <div class="rosemary-nav-tabs-wrapper mb-4">
                <ul class="nav nav-pills border-0 flex-column flex-md-row rosemary-nav-tabs" id="employeeTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#basic-info"><i class="bx bx-user me-1"></i> Basic Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#employment"><i class="bx bx-briefcase me-1"></i> Employment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#contact"><i class="bx bx-phone me-1"></i> Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#banking"><i class="bx bx-credit-card me-1"></i> Banking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#documents"><i class="bx bx-file me-1"></i> Documents</a>
                    </li>
                    @if ($addonService->isAddonEnabled(ModuleConstants::PAYROLL))
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#payroll"><i class="bx bx-wallet me-1"></i> Payroll</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kpi"><i class="bx bx-trending-up me-1"></i> KPI</a>
                    </li>
                </ul>
            </div>
            <!-- /Tabs Navigation -->

            <!-- Tab Content -->
            <div class="tab-content p-0 m-0 border-0 shadow-none">

                <!-- Basic Info Tab -->
                <div class="tab-pane fade show active" id="basic-info">
                    <div class="card mb-4 emp-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-info-circle me-2 fs-5" style="color: #127464;"></i>
                                    <h6 class="mb-0 fw-bold" style="color: #1E293B;">Basic Information</h6>
                                </div>
                                <button class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#offcanvasEditBasicInfo">
                                    <i class="bx bx-edit-alt me-1"></i> Edit Basic Info
                                </button>
                            </div>

                            <div class="row g-4">
                                <!-- First & Last Name -->
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-user text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">First Name</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->first_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-user text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Last Name</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->last_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MARITAL STATUS -->
                                <div class="col-md-6">
                                    <div class="emp-field-box border-0 shadow-xs" style="background: #fdf2f2; border-start: 4px solid #ef4444 !important;">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-heart text-danger fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">Marital Status</p>
                                                <p class="mb-0 fw-bold text-dark">{{ ucfirst($user->marital_status ?? 'Single') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- BLOOD GROUP -->
                                <div class="col-md-6">
                                    <div class="emp-field-box border-0 shadow-xs" style="background: #f0fdf4; border-start: 4px solid #22c55e !important;">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-droplet text-success fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">Blood Group</p>
                                                <p class="mb-0 fw-extrabold text-dark">{{ $user->blood_group ?? 'O+' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PARENTS DETAILS -->
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-male text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">Father's Name</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->father_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-female text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">Mother's Name</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->mother_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($user->marital_status == 'married')
                                <div class="col-md-6">
                                    <div class="emp-field-box border-primary" style="background: #eff6ff;">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-user-circle text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-primary smallest fw-bold text-uppercase">Spouse Name</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->spouse_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-group text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">No. of Children</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->no_of_children ?? '0' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- NATIONALITY -->
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-globe text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">Birth Country</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->birth_country ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-flag text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted smallest fw-bold text-uppercase">Citizenship</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->citizenship ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Employment Tab -->
                <div class="tab-pane fade" id="employment">
                    <div class="card mb-4 emp-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-briefcase me-2 fs-5" style="color: #127464;"></i>
                                    <h6 class="mb-0 fw-bold" style="color: #1E293B;">Work Information</h6>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-hitech px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAllotDevice">
                                        <i class="bx bx-mobile-alt me-1"></i> Allot Device
                                    </button>
                                    <button class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#offcanvasEditWorkInfo" onclick="loadSelectList()">
                                        <i class="bx bx-edit-alt me-1"></i> Update Details
                                    </button>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Designation -->
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-award text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Designation</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->designation != null ? $user->designation->name : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Department/Team -->
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bx bx-group text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Department / Team</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->team != null ? $user->team->name : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted smallest fw-bold text-uppercase">Reporting Manager</p>
                                        <p class="mb-0 fw-bold text-dark">{{ $user->reporting_to_id ? $user->getReportingToUserName() : 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted smallest fw-bold text-uppercase">Access Role</p>
                                        <p class="mb-0 fw-bold text-dark">{{ ucfirst($user->getRoleNames()->first() ?? 'Employee') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Device Information Card -->
                    <div class="card mb-4 emp-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-devices me-2 fs-5" style="color: #127464;"></i>
                                    <h6 class="mb-0 fw-bold" style="color: #1E293B;">Device Information</h6>
                                </div>
                                <div class="d-flex gap-2">
                                    @if ($user->userDevice)
                                        <form action="{{ route('employees.removeDevice') }}" method="post" id="deleteDeviceForm">
                                            @csrf
                                            <input type="hidden" name="userId" value="{{ $user->id }}">
                                            <button type="button" onclick="showDeleteDeviceConfirmation()" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bx bx-trash me-1"></i> Unlink Device
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#modalAllotDevice">
                                            <i class="bx bx-plus me-1"></i> Allot Device
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if ($user->userDevice)
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                            <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Device ID</p>
                                            <p class="mb-0 fw-bold text-dark small">{{ $user->userDevice->device_id }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                            <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Brand / Model</p>
                                            <p class="mb-0 fw-bold text-dark small">{{ $user->userDevice->brand ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                            <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">App Version</p>
                                            <p class="mb-0 fw-bold text-dark small">{{ $user->userDevice->app_version ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 rounded-3" style="border: 2px dashed #E2E8F0; background-color: #F8FAFC;">
                                    <i class="bx bx-mobile-alt text-muted mb-2" style="font-size: 2.5rem;"></i>
                                    <p class="text-muted small mb-2">No device linked to this employee yet.</p>
                                    <button class="btn btn-sm text-white px-4 rounded-pill" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#modalAllotDevice">
                                        <i class="bx bx-plus me-1"></i> Allot a Device
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Contact Tab -->
                <div class="tab-pane fade" id="contact">
                    <div class="card mb-4 emp-card border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-phone-call me-2 fs-5" style="color: #127464;"></i>
                                    <h6 class="mb-0 fw-bold" style="color: #1E293B;">Contact & Address Details</h6>
                                </div>
                                <button class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#offcanvasEditContactInfo">
                                    <i class="bx bx-edit-alt me-1"></i> Edit Contact Info
                                </button>
                            </div>

                            <div class="row g-4">
                                <!-- EMAIL & PHONE -->
                                <div class="col-md-4">
                                    <div class="emp-field-box border-dashed">
                                        <p class="mb-1 text-muted smallest fw-bold text-uppercase">Personal Email</p>
                                        <p class="mb-0 fw-bold text-dark">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted smallest fw-bold text-uppercase">Primary Phone</p>
                                        <p class="mb-0 fw-bold text-dark">{{ $user->phone }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted smallest fw-bold text-uppercase">Alternate Phone</p>
                                        <p class="mb-0 fw-bold text-dark">{{ $user->alternate_number ?? 'Not Provided' }}</p>
                                    </div>
                                </div>

                                <!-- DUAL ADDRESS SECTION -->
                                <div class="col-12 mt-5">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="p-4 rounded-4" style="background: #f8fafc; border: 1px solid #eef2f6;">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="bg-white shadow-sm rounded-pill p-2 me-3" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-map-pin" style="color: #127464;"></i></div>
                                                    <h6 class="mb-0 fw-bold text-dark">Current Address</h6>
                                                </div>
                                                <p class="mb-0 text-muted lh-base">
                                                    @if($user->temp_street || $user->temp_building)
                                                        {{ $user->temp_building }}<br>
                                                        {{ $user->temp_street }}<br>
                                                        {{ $user->temp_city }}, {{ $user->temp_state }} {{ $user->temp_zip }}<br>
                                                        {{ $user->temp_country }}
                                                    @else
                                                        <span class="fst-italic text-sm">No current address recorded.</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-4 rounded-4" style="background: #fdf2f2; border: 1px solid #fee2e2;">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="bg-white shadow-sm rounded-pill p-2 me-3" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-home-heart" style="color: #127464;"></i></div>
                                                    <h6 class="mb-0 fw-bold text-dark">Permanent Address</h6>
                                                </div>
                                                <p class="mb-0 text-muted lh-base">
                                                    @if($user->perm_street || $user->perm_building)
                                                        {{ $user->perm_building }}<br>
                                                        {{ $user->perm_street }}<br>
                                                        {{ $user->perm_city }}, {{ $user->perm_state }} {{ $user->perm_zip }}<br>
                                                        {{ $user->perm_country }}
                                                    @else
                                                        <span class="fst-italic text-sm">Same as current or not provided.</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- EMERGENCY CONTACT SECTION -->
                                <div class="col-12 mt-4">
                                    <div class="card bg-label-danger border-0 p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bxs-ambulance me-2 fs-3" style="color: #127464;"></i>
                                            <h6 class="mb-0 fw-bold" style="color: #127464;">Emergency Contact Details</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <span class="d-block smallest text-muted mb-1">CONTACT PERSON</span>
                                                <strong class="text-dark">{{ $user->emergency_contact_name ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="d-block smallest text-muted mb-1">RELATIONSHIP</span>
                                                <strong class="text-dark">{{ $user->emergency_contact_relation ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="d-block smallest text-muted mb-1">CONTACT PHONE</span>
                                                <strong class="text-dark">{{ $user->emergency_contact_phone ?? 'N/A' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banking Tab -->
                <div class="tab-pane fade" id="banking">
                    <div class="card mb-4 emp-card">
                        <div class="card-body p-4">
                 <!-- Banking Tab Card Header -->
                 <div class="d-flex justify-content-between align-items-center mb-4">
                                 <div class="d-flex align-items-center">
                                     <i class="bx bx-credit-card me-2 fs-5" style="color: #127464;"></i>
                                     <h6 class="mb-0 fw-bold" style="color: #1E293B;">Bank Account Details</h6>
                                 </div>
                                 @if (
                                     $user->status != \App\Enums\UserAccountStatus::RELIEVED &&
                                         $user->status != \App\Enums\UserAccountStatus::RETIRED &&
                                         $user->status != \App\Enums\UserAccountStatus::TERMINATED)
                                     <button class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#offcanvasAddAccount" onclick="loadBankDetails()">
                                         <i class="bx bx-edit-alt me-1"></i> Edit Banking
                                     </button>
                                 @endif
                             </div>

                            @if ($user->bank_account)
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="emp-field-box">
                                            <p class="mb-1 text-muted smallest fw-bold text-uppercase">Beneficiary Name</p>
                                            <p class="mb-0 fw-bold text-dark">{{ $user->bank_account->account_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="emp-field-box">
                                            <p class="mb-1 text-muted smallest fw-bold text-uppercase">Bank Name</p>
                                            <p class="mb-0 fw-bold text-dark">{{ $user->bank_account->bank_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="emp-field-box border-dashed">
                                            <p class="mb-1 text-muted smallest fw-bold text-uppercase">Account Number</p>
                                            <p class="mb-0 fw-extrabold text-dark">•••• •••• •••• {{ substr($user->bank_account->account_number, -4) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="emp-field-box">
                                            <p class="mb-1 text-muted smallest fw-bold text-uppercase">IFSC / Branch Code</p>
                                            <p class="mb-0 fw-bold text-dark">{{ $user->bank_account->bank_code }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 rounded-4" style="background: #f8fafc; border: 2px dashed #e2e8f0;">
                                    <i class="bx bx-landmark text-muted mb-2" style="font-size: 3rem;"></i>
                                    <p class="text-muted fw-bold mb-0">No bank details added yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Security Notice (Banking) --}}
                    <div class="d-flex align-items-center p-3 mt-3 emp-card" style="background-color: #F0FAFA; border: 1px solid #CCECEC !important;">
                        <div class="rounded-pill p-2 me-3 d-flex align-items-center justify-content-center" style="background-color: #CCECEC; width: 40px; height: 40px;">
                            <i class="bx bx-shield-quarter" style="color: #127464; font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold" style="color: #127464; font-size: 0.85rem;">Security Notice</h6>
                            <p class="mb-0 text-muted" style="font-size: 0.78rem;">Banking details are encrypted and auditing is enabled for all modifications.</p>
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="documents">
                    <div class="card mb-4 emp-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-file me-2 fs-5" style="color: #127464;"></i>
                                    <h6 class="mb-0 fw-bold" style="color: #1E293B;">Employee Documents</h6>
                                </div>
                                <button class="btn btn-sm text-white rounded-pill px-4 shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#modalAddUserDocument" onclick="setDocModal('Other Document', '')">
                                    <i class="bx bx-plus me-1"></i> Add New Document
                                </button>
                            </div>

                            @php
                                $mandatoryDocs = [
                                    ['name' => 'Aadhar Card', 'key' => 'aadhaar_no', 'icon' => 'bx-id-card'],
                                    ['name' => 'Pan Card', 'key' => 'pan_no', 'icon' => 'bx-credit-card-front'],
                                    ['name' => '10th Marksheet', 'key' => null, 'icon' => 'bx-certification'],
                                    ['name' => 'Intermediate Marksheet', 'key' => null, 'icon' => 'bx-certification'],
                                    ['name' => 'Graduation Marksheet', 'key' => null, 'icon' => 'bx-certification'],
                                ];
                            @endphp

                            <h6 class="fw-bold mb-3 small text-muted text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">Essential Verification Documents</h6>
                            <div class="row g-3">
                                @foreach($mandatoryDocs as $doc)
                                    @php
                                        $isSubmitted = false;
                                        $docFile = null;
                                        $docNumber = 'N/A';

                                        if ($doc['key'] && $user->{$doc['key']}) {
                                            $isSubmitted = true;
                                            $docNumber = $user->{$doc['key']};
                                        }

                                        $request = $user->documentRequests->where('status', 'approved')->filter(function($r) use ($doc) {
                                            return $r->documentType && strtolower($r->documentType->name) == strtolower($doc['name']);
                                        })->first();

                                        if ($request) {
                                            $isSubmitted = true;
                                            $docFile = $request->generated_file;
                                            if ($request->remarks) $docNumber = $request->remarks;
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <div class="rounded-3 p-2 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: {{ $isSubmitted ? '#E6F4F1' : '#F1F5F9' }}; width: 44px; height: 44px; border: 1px solid {{ $isSubmitted ? '#A7D9CF' : '#E2E8F0' }};">
                                                    <i class="bx {{ $doc['icon'] }} {{ $isSubmitted ? '' : 'text-muted' }} fs-4" style="{{ $isSubmitted ? 'color:#127464' : '' }}"></i>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="mb-0 fw-bold text-dark small" style="line-height: 1.2;">{{ $doc['name'] }}</p>
                                                    <span class="badge" style="font-size: 0.55rem; padding: 0.2rem 0.5rem; border-radius: 4px; background-color: {{ $isSubmitted ? '#127464' : '#94A3B8' }}; color:#fff;">{{ $isSubmitted ? 'SUBMITTED' : 'NOT SUBMITTED' }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                                                                @if($isSubmitted)
                                                    @if($docFile)
                                                        <a href="javascript:void(0)" class="btn btn-xs rounded-pill px-3" style="font-size: 0.65rem; background:#127464; color:#fff; border:1px solid #127464;" onclick="viewDocumentPopup('{{ asset('storage/'.$docFile) }}', '{{ $doc['name'] }}')"><i class="bx bx-show me-1"></i>View</a>
                                                    @elseif($docNumber && $docNumber !== 'N/A')
                                                        <a href="javascript:void(0)" class="btn btn-xs rounded-pill px-3" style="font-size: 0.65rem; background:#127464; color:#fff; border:1px solid #127464;" onclick="viewDocumentNumber('{{ $doc['name'] }}', '{{ $docNumber }}')"><i class="bx bx-show me-1"></i>View</a>
                                                    @endif
                                                    <button class="btn btn-xs btn-outline-hitech rounded-pill px-3" style="font-size: 0.65rem;" data-bs-toggle="modal" data-bs-target="#modalAddUserDocument" onclick="setDocModal('{{ $doc['name'] }}', '{{ $docNumber }}')">Update</button>
                                                @else
                                                    <button class="btn btn-xs btn-hitech rounded-pill px-3" style="font-size: 0.65rem; background-color: #127464; color: #fff;" data-bs-toggle="modal" data-bs-target="#modalAddUserDocument" onclick="setDocModal('{{ $doc['name'] }}', '')">Upload</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($user->passport_no || $user->visa_type || $user->frro_registration || $user->documentRequests->where('status', 'approved')->count() > 0)
                            <hr class="my-4" style="border-style: dashed; opacity: 0.1;">
                            <!-- Other Identity Proofs -->
                            <h6 class="fw-bold mb-3 small text-muted text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">Other Identity Proofs</h6>
                            <div class="row g-3">
                                @if($user->passport_no)
                                <div class="col-md-4">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Passport No.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark small text-truncate">{{ $user->passport_no }}</span>
                                            <i class="bx bxs-check-shield text-success"></i>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($user->visa_type)
                                <div class="col-md-4">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Visa Status</p>
                                        <span class="small fw-semibold text-dark">{{ $user->visa_type }}</span>
                                    </div>
                                </div>
                                @endif
                                @if($user->documentRequests->where('status', 'approved')->count() > 0)
                                <div class="col-md-4">
                                    <div class="emp-field-box">
                                        <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Additional Docs</p>
                                        <span class="badge" style="background:#127464;color:#fff;">{{ $user->documentRequests->where('status', 'approved')->count() }} Added</span>
                                    </div>
                                </div>
                                @endif
                                @if($user->visa_type || $user->frro_registration)
                                <div class="col-12 mt-2 pt-3 border-top">
                                    <div class="row g-4">
                                        @if($user->visa_type)
                                        <div class="col-md-6">
                                            <h6 class="fw-bold mb-2 small text-muted text-uppercase" style="font-size: 0.65rem;">Visa Information</h6>
                                            <div class="p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted small">Type: <strong>{{ $user->visa_type }}</strong></span>
                                                    <span class="text-muted small">Expires: <strong>{{ $user->visa_expiry_date ?? 'N/A' }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($user->frro_registration)
                                        <div class="col-md-6">
                                            <h6 class="fw-bold mb-2 small text-muted text-uppercase" style="font-size: 0.65rem;">FRRO Registration</h6>
                                            <div class="p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">Number: <strong>{{ $user->frro_registration }}</strong></span>
                                                    <span class="text-muted small">Expires: <strong>{{ $user->frro_expiry_date ?? 'N/A' }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>




                <!-- Payroll Tab -->
                <div class="tab-pane fade" id="payroll">
                    <div class="row g-4">
                        <!-- Compensation Summary -->
                        <div class="col-md-7">
                            <div class="card mb-4 emp-card">
                                <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-money me-2 fs-5" style="color: #127464;"></i>
                                            <h6 class="mb-0 fw-bold" style="color: #1E293B;">Compensation Details</h6>
                                        </div>
                                        @if ($user->status != \App\Enums\UserAccountStatus::RELIEVED &&
                                                $user->status != \App\Enums\UserAccountStatus::RETIRED &&
                                                $user->status != \App\Enums\UserAccountStatus::TERMINATED)
                                            <button class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="background-color: #127464;" data-bs-toggle="modal" data-bs-target="#offcanvasEditCompInfo">
                                                <i class="bx bx-edit-alt me-1"></i> Update
                                            </button>
                                        @endif
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <div class="emp-field-box">
                                                <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Base Monthly Salary</p>
                                                <p class="mb-0 fw-bold fs-5 text-dark">
                                                    {{ $user->base_salary != null ? $settings->currency_symbol . number_format($user->base_salary, 2) : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="emp-field-box d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Payroll Status</p>
                                                    <p class="mb-0 fw-bold text-success">Active</p>
                                                </div>
                                                <span class="badge rounded-pill px-3" style="background-color: #E0F2F1; color: #127464;">ON CYCLE</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="emp-field-box">
                                                <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">CTC Offered</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->ctc_offered != null ? $settings->currency_symbol . number_format($user->ctc_offered, 2) : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="emp-field-box">
                                                <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Pay Frequency</p>
                                                <p class="mb-0 fw-bold text-dark">Monthly</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="emp-field-box">
                                                <p class="mb-1 text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Effective Date</p>
                                                <p class="mb-0 fw-bold text-dark">{{ $user->joining_date ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Adjustments -->
                            <div class="card mb-4 emp-card">
                                <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-list-check me-2 fs-5" style="color: #127464;"></i>
                                            <h6 class="mb-0 fw-bold" style="color: #1E293B;">Allowances & Deductions</h6>
                                        </div>
                                        @if($user->status != \App\Enums\UserAccountStatus::RELIEVED && $user->status != \App\Enums\UserAccountStatus::RETIRED)
                                            <button class="btn btn-sm btn-outline-hitech rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#offcanvasPayrollAdjustment">
                                                <i class="bx bx-plus me-1"></i> Add Item
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if ($user->payrollAdjustments->count() > 0)
                                        <div class="row g-3">
                                        @foreach ($user->payrollAdjustments as $adjustment)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-white p-2 rounded-pill me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                            <i class="bx {{ $adjustment->type === 'benefit' ? 'bx-trending-up text-success' : 'bx-trending-down text-danger' }} fs-5"></i>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bold small text-dark">{{ $adjustment->name }}</p>
                                                            <span class="text-muted" style="font-size: 0.65rem;">{{ strtoupper($adjustment->type) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <p class="mb-0 fw-bold {{ $adjustment->type === 'benefit' ? 'text-success' : 'text-danger' }}">
                                                            {{ $adjustment->type === 'benefit' ? '+' : '-' }}{{ $settings->currency_symbol }}{{ number_format($adjustment->amount, 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 rounded-3" style="border: 2px dashed #E2E8F0; background-color: #F8FAFC;">
                                            <p class="text-muted mb-0 small">No active adjustments or allowances</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- KPI Tab -->
                <div class="tab-pane fade" id="kpi">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-line-chart me-2 fs-5" style="color: #127464;"></i>
                                    <h6 class="mb-0 fw-bold" style="color: #1E293B;">Performance Metrics (KPIs)</h6>
                                </div>
                                <select class="form-select form-select-sm w-auto rounded-pill border-0 bg-light px-3 font-inter" style="font-size: 0.8rem;">
                                    <option>Last 6 Months</option>
                                    <option>Year 2025</option>
                                </select>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="p-4 rounded-3 text-center" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                        <p class="text-muted small mb-2 uppercase fw-bold tracking-wider font-inter" style="font-size: 0.65rem;">ATTENDANCE</p>
                                        <h2 class="fw-bold mb-1" style="color: #1E293B; letter-spacing: -1px;">98.5%</h2>
                                        <span class="badge bg-label-success rounded-pill px-2" style="font-size: 0.65rem;">Above Target</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 rounded-3 text-center" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                        <p class="text-muted small mb-2 uppercase fw-bold tracking-wider font-inter" style="font-size: 0.65rem;">PRODUCTIVITY</p>
                                        <h2 class="fw-bold mb-1" style="color: #1E293B; letter-spacing: -1px;">4.8<span class="fs-6 text-muted fw-normal">/5</span></h2>
                                        <span class="badge bg-label-primary rounded-pill px-2" style="font-size: 0.65rem;">Exceeds Exp.</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 rounded-3 text-center" style="background-color: #F8FAFC; border: 1px solid #F1F5F9;">
                                        <p class="text-muted small mb-2 uppercase fw-bold tracking-wider font-inter" style="font-size: 0.65rem;">TASKS COMPLETED</p>
                                        <h2 class="fw-bold mb-1" style="color: #1E293B; letter-spacing: -1px;">124</h2>
                                        <span class="text-muted small font-inter" style="font-size: 0.7rem;"><i class="bx bx-up-arrow-alt text-success"></i> 12% vs last month</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if ($addonService->isAddonEnabled(ModuleConstants::SALES_TARGET))
                                <div class="mt-5 pt-4 border-top">
                                    <h6 class="fw-bold mb-4" style="color: #1E293B;">Sales Performance</h6>
                                    @include('salestarget::partials.employee_view_content')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Tab Content -->

            <!-- Mobile Tab Navigation (Previous/Next) -->
            <div class="mobile-tab-navigation d-md-none mt-4 pb-4">
                <div class="card border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 20px;">
                    <div class="card-body p-3 d-flex justify-content-between gap-3">
                        <button class="btn btn-outline-secondary rounded-pill flex-fill py-2 fw-bold" onclick="navigateTabs('prev')">
                            <i class="bx bx-chevron-left me-1"></i> Previous
                        </button>
                        <button class="btn btn-primary rounded-pill flex-fill py-2 fw-bold" style="background: #127464;" onclick="navigateTabs('next')">
                            Next Stage <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--/ User Content -->
    </div>




    {{-- Document View Popup Modal --}}
    <div class="modal fade" id="modalViewDocument" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="modal-icon-header me-3"><i class="bx bx-file-find"></i></div>
                    <h5 class="modal-title modal-title-hitech mb-0" id="docViewModalTitle">View Document</h5>
                    <a id="docViewDownloadBtn" href="#" download class="btn btn-sm ms-auto me-2" style="background: rgba(255,255,255,0.2); color: #fff; border-radius: 10px; font-size: 0.8rem; padding: 0.4rem 1rem;">
                        <i class="bx bx-download me-1"></i> Download
                    </a>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal" style="position:relative;top:auto;right:auto;transform:none;"><i class="bx bx-x"></i></button>
                </div>
                <div class="modal-body p-0" style="background: #1a1a2e; min-height: 75vh;">
                    <div id="docViewContainer" class="w-100 h-100 d-flex align-items-center justify-content-center" style="min-height: 75vh;">
                        {{-- Content injected by JS --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW: Add Document Modal --}}
    <div class="modal fade" id="modalAddUserDocument" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-header me-3">
                            <i class="bx bx-file-plus"></i>
                        </div>
                        <h5 class="modal-title modal-title-hitech mb-0">Manage Document</h5>
                    </div>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ route('employees.addOrUpdateDocument') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="userId" value="{{ $user->id }}">
                    <div class="modal-body modal-body-hitech">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label-hitech">Document Name <span class="text-danger">*</span></label>
                                <input type="text" id="docModalName" name="documentName" class="form-control form-control-hitech" placeholder="e.g. Aadhar Card" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-hitech">Document Number</label>
                                <input type="text" id="docModalNumber" name="remarks" class="form-control form-control-hitech" placeholder="Enter ID number">
                            </div>
                            <div class="col-12">
                                <label class="form-label-hitech">Attachment Upload <span class="text-danger">*</span></label>
                                <div class="p-4 border-2 rounded-3 text-center" style="border: 2px dashed #E2E8F0; background-color: #F8FAFC;">
                                    <i class="bx bx-cloud-upload text-muted mb-2" style="font-size: 2.5rem;"></i>
                                    <p class="small text-muted mb-3">Click to select or drag and drop file here</p>
                                    <input type="file" name="file" class="form-control form-control-hitech" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-hitech-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-hitech-modal-submit">Upload & Save <i class="bx bx-cloud-upload ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- NEW: Terminate Employee Modal --}}
    <div class="modal fade" id="terminateEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-header me-3">
                            <i class="bx bx-user-x"></i>
                        </div>
                        <h5 class="modal-title modal-title-hitech mb-0">Terminate Employee</h5>
                    </div>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form id="terminateEmployeeForm" action="{{ route('employees.terminate', $user->id) }}" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="modal-body modal-body-hitech">
                        <div class="alert alert-warning border-0 d-flex align-items-center mb-4" style="background-color: #FFFBEB; border-radius: 12px;">
                            <i class="bx bx-error-alt fs-4 me-2 text-warning"></i>
                            <div class="small text-dark fw-bold">Warning: Initiating termination for {{ $user->getFullName() }}. This action cannot be undone easily.</div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label-hitech">Termination Type <span class="text-danger">*</span></label>
                                <select id="terminationType" name="terminationType" class="select2 form-select form-select-hitech" required>
                                    <option value="">Select Type</option>
                                    @foreach (\App\Enums\TerminationType::cases() as $type)
                                        <option value="{{ $type->value }}">
                                            {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $type->value)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-hitech">Exit Date <span class="text-danger">*</span></label>
                                <input type="text" id="exitDate" name="exitDate" class="form-control form-control-hitech flatpickr-input" placeholder="Select Date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-hitech">Last Working Day <span class="text-danger">*</span></label>
                                <input type="text" id="lastWorkingDay" name="lastWorkingDay" class="form-control form-control-hitech flatpickr-input" placeholder="Select Date" required>
                            </div>
                            <div class="col-md-6 d-flex align-items-center mt-auto pb-2">
                                <div class="form-check form-switch custom-switch-hitech">
                                    <input class="form-check-input" type="checkbox" id="isEligibleForRehire" name="isEligibleForRehire" value="1" checked>
                                    <label class="form-check-label ms-2 fw-bold text-muted small text-uppercase" for="isEligibleForRehire">Eligible for Re-hire</label>
                                    <input type="hidden" name="isEligibleForRehire" value="0">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label-hitech">Reason for Termination <span class="text-danger">*</span></label>
                                <textarea id="exitReason" name="exitReason" class="form-control form-control-hitech" rows="3" placeholder="Provide detailed reason..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-hitech-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-hitech-modal-submit bg-danger border-0" id="terminateSubmitBtn">Confirm Termination <i class="bx bx-check-circle ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modals Inclusion --}}
    @include('_partials._modals.employees.edit_basic_info')
    @include('_partials._modals.employees.edit_contact_info')
    @include('_partials._modals.employees.edit_work_info')
    @include('_partials._modals.employees.edit_compensation_info')

    @if ($addonService->isAddonEnabled(ModuleConstants::PAYROLL))
        @include('_partials._modals.employees.add_orUpdate_bankAccount')
        @include('payroll::partials.add_orUpdate_payroll_adjustment')
    @endif

    {{-- Allot Device Modal --}}
    <div class="modal fade" id="modalAllotDevice" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="modal-icon-header me-3"><i class="bx bx-mobile-alt"></i></div>
                    <h5 class="modal-title modal-title-hitech mb-0">Allot Device</h5>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal" style="position:relative;top:auto;right:auto;transform:none;"><i class="bx bx-x"></i></button>
                </div>
                <div class="modal-body modal-body-hitech">
                    <form action="{{ route('employees.allotDevice') }}" method="POST" id="allotDeviceForm">
                        @csrf
                        <input type="hidden" name="userId" value="{{ $user->id }}">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-hitech" for="allotDeviceId">Device ID <span class="text-danger">*</span></label>
                                <input type="text" name="deviceId" id="allotDeviceId" class="form-control form-control-hitech" placeholder="e.g. DEVICE-001 or IMEI number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-hitech" for="allotDeviceBrand">Brand / Model</label>
                                <input type="text" name="brand" id="allotDeviceBrand" class="form-control form-control-hitech" placeholder="e.g. Samsung Galaxy A52">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-hitech" for="allotDeviceType">Device Type</label>
                                <select name="deviceType" id="allotDeviceType" class="form-select form-select-hitech">
                                    <option value="mobile">Mobile Phone</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="biometric">Biometric Device</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer border-0 px-0 pb-0 pt-4 d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-hitech-modal-cancel px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-hitech-modal-submit px-5">
                                Allot Device <i class="bx bx-check-circle ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($addonService->isAddonEnabled(ModuleConstants::SALES_TARGET))
        @include('salestarget::partials.add_or_update_sales_target_model')
    @endif

    {{-- 1. Confirm Probation Modal --}}
    <div class="modal fade" id="confirmProbationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-header me-3">
                            <i class="bx bx-check-circle"></i>
                        </div>
                        <h5 class="modal-title modal-title-hitech mb-0">Confirm Probation</h5>
                    </div>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form id="confirmProbationForm" action="{{ route('employees.confirmProbation', $user->id) }}" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="modal-body modal-body-hitech text-center py-4">
                        <i class="bx bx-confetti text-success mb-3" style="font-size: 3.5rem; opacity: 0.8;"></i>
                        <h6 class="fw-bold mb-2">Confirm Completion?</h6>
                        <p class="text-muted small px-4">Are you sure you want to confirm the successful completion of probation for <strong>{{ $user->getFullName() }}</strong>?</p>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-hitech-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-hitech-modal-submit" id="confirmProbationSubmitBtn">Confirm Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. Extend Probation Modal --}}
    <div class="modal fade" id="extendProbationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-header me-3">
                            <i class="bx bx-calendar-plus"></i>
                        </div>
                        <h5 class="modal-title modal-title-hitech mb-0">Extend Probation</h5>
                    </div>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form id="extendProbationForm" action="{{ route('employees.extendProbation', $user->id) }}" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="modal-body modal-body-hitech">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label-hitech" for="newProbationEndDate">New Probation End Date <span class="text-danger">*</span></label>
                                <input type="text" id="newProbationEndDate" name="newProbationEndDate" class="form-control form-control-hitech flatpickr-input" placeholder="Select New Date" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label-hitech" for="extendRemarks">Extension Reason / Remarks</label>
                                <textarea class="form-control form-control-hitech" id="extendRemarks" name="probationRemarks" rows="3" placeholder="Explain why the probation is being extended..."></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-hitech-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-hitech-modal-submit" id="extendProbationSubmitBtn">Extend Period</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. Fail Probation Modal --}}
    <div class="modal fade" id="failProbationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-hitech">
                <div class="modal-header modal-header-hitech">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-header me-3 bg-danger" style="background-color: #FEE2E2 !important;">
                            <i class="bx bx-user-minus text-danger"></i>
                        </div>
                        <h5 class="modal-title modal-title-hitech mb-0">Probation Non-Completion</h5>
                    </div>
                    <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form id="failProbationForm" action="{{ route('employees.failProbation', $user->id) }}" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="modal-body modal-body-hitech">
                        <div class="alert alert-danger border-0 d-flex align-items-center mb-4" style="background-color: #FEF2F2; border-radius: 12px;">
                            <i class="bx bx-error fs-4 me-2 text-danger"></i>
                            <div class="small fw-bold text-danger">Warning: This will terminate the employee due to probation failure.</div>
                        </div>
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label-hitech" for="failRemarks">Reason for Failure <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-hitech" id="failRemarks" name="probationRemarks" rows="3" placeholder="Provide detailed feedback on non-completion..." required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-hitech-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-hitech-modal-submit bg-danger border-0" id="failProbationSubmitBtn">Confirm Failure</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        // Global variables for employee context
        var user = @json($user);
        var role = @json($role);
        var attendanceType = @json($user->attendance_type);
        var terminateUrl = "{{ route('employees.terminate', $user->id) }}";
        // Document Viewer Popup (for file-based documents)
        function viewDocumentPopup(url, docName) {
            console.log("Opening document viewer for:", url);
            var safeUrl = encodeURI(url);
            document.getElementById('docViewModalTitle').textContent = docName || 'View Document';
            var dlBtn = document.getElementById('docViewDownloadBtn');
            dlBtn.href = safeUrl; 
            dlBtn.style.display = '';
            var container = document.getElementById('docViewContainer');
            var ext = safeUrl.split('.').pop().toLowerCase().split('?')[0];
            
            // Add a loading indicator
            container.innerHTML = '<div class="text-white pt-5"><div class="spinner-border text-light"></div><p class="mt-2 small opacity-50">Loading Document...</p></div>';
            
            if (ext === 'pdf') {
                container.innerHTML = '<iframe src="' + safeUrl + '" style="width:100%;height:80vh;border:none;" onerror="console.error(\'PDF load failed\')" allowfullscreen></iframe>';
            } else {
                var img = new Image();
                img.onload = function() {
                    container.innerHTML = '<div style="padding:1rem;text-align:center;"><img src="' + safeUrl + '" style="max-width:100%;max-height:80vh;object-fit:contain;border-radius:12px;" alt="' + (docName||'Document') + '" /></div>';
                };
                img.onerror = function() {
                    console.error("Image load failed for URL:", safeUrl);
                    container.innerHTML = '<div class="text-center p-5"><i class="bx bx-error-circle text-danger mb-3" style="font-size:3rem;"></i><h5 class="text-white">Image Failed to Load</h5><p class="text-muted small">Please ensure the document was uploaded correctly. <br>URL: ' + safeUrl + '</p></div>';
                };
                img.src = safeUrl;
            }
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalViewDocument')).show();
        }

        // Document Number Viewer (for Aadhaar, PAN — number only, no file)
        function viewDocumentNumber(docName, docNumber) {
            document.getElementById('docViewModalTitle').textContent = docName;
            document.getElementById('docViewDownloadBtn').style.display = 'none';
            document.getElementById('docViewContainer').innerHTML = `
                <div style="text-align:center;padding:3rem 2rem;">
                    <div style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:2.5rem 3rem;display:inline-block;min-width:300px;">
                        <div style="width:64px;height:64px;background:linear-gradient(135deg,#127464,#0e5a4e);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;">
                            <i class="bx bx-id-card" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <p style="color:rgba(255,255,255,0.55);font-size:0.72rem;text-transform:uppercase;letter-spacing:2px;margin-bottom:0.5rem;">${docName}</p>
                        <h2 style="color:#fff;font-weight:800;font-size:2rem;letter-spacing:5px;margin:0;font-family:monospace;">${docNumber}</h2>
                        <p style="color:rgba(255,255,255,0.35);font-size:0.7rem;margin-top:0.8rem;">Reference number on file</p>
                    </div>
                </div>`;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalViewDocument')).show();
        }


        // Mobile Tab Navigation Logic
        function navigateTabs(direction) {
            const tabs = $('.rosemary-nav-tabs .nav-link');
            const activeTab = $('.rosemary-nav-tabs .nav-link.active');
            let activeIndex = tabs.index(activeTab);
            
            let nextIndex;
            if (direction === 'next') {
                nextIndex = (activeIndex + 1) % tabs.length;
            } else {
                nextIndex = (activeIndex - 1 + tabs.length) % tabs.length;
            }
            
            const nextTab = $(tabs[nextIndex]);
            nextTab.tab('show');
            
            // Smooth scroll to top of content
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Update horizontal scroll of tab header if needed
            const tabWrapper = document.querySelector('.rosemary-nav-tabs');
            const targetTab = tabs[nextIndex];
            tabWrapper.scrollTo({
                left: targetTab.offsetLeft - (tabWrapper.offsetWidth / 2) + (targetTab.offsetWidth / 2),
                behavior: 'smooth'
            });
        }

        // Document Management Modal Handler
        function setDocModal(name, number) {
            document.getElementById('docModalName').value = name;
            document.getElementById('docModalNumber').value = (number === 'N/A' || number === 'undefined' ? '' : number);
        }

        // Device Unlink Confirmation
        function showDeleteDeviceConfirmation() {
            Swal.fire({
                title: 'Unlink Device?',
                text: "This will remove the biometric lock for this employee's mobile app.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Yes, Unlink it!',
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteDeviceForm').submit();
                }
            });
        }

        // AJAX Loaders for Dynamic Dropdowns
        function getDynamicQrDevices() {
            var dynamicQrId = '{{ $user->dynamic_qr_device_id }}';
            $.ajax({
                url: '{{ route('employee.getDynamicQrDevices') }}',
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Please select a dynamic qr device</option>';
                    response.forEach(function(item) {
                        options += '<option value="' + item.id + '" ' + (dynamicQrId == item.id ? 'selected' : '') + '>' + item.name + '</option>';
                    });
                    $('#dynamicQrId').html(options);
                }
            });
        }
        function getGeofenceGroups() {
            var geofenceId = '{{ $user->geofence_group_id }}';
            $.ajax({
                url: '{{ route('employee.getGeofenceGroups') }}',
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Please select a geofence group</option>';
                    response.forEach(function(item) {
                        options += '<option value="' + item.id + '" ' + (geofenceId == item.id ? 'selected' : '') + '>' + item.name + '</option>';
                    });
                    $('#geofenceGroupId').html(options);
                }
            });
        }
        function getIpGroups() {
            var ipGroupId = '{{ $user->ip_address_group_id }}';
            $.ajax({
                url: '{{ route('employee.getIpGroups') }}',
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Please select a ip group</option>';
                    response.forEach(function(item) {
                        options += '<option value="' + item.id + '" ' + (ipGroupId == item.id ? 'selected' : '') + '>' + item.name + '</option>';
                    });
                    $('#ipGroupId').html(options);
                }
            });
        }
        function getQrGroups() {
            var qrGroupId = '{{ $user->qr_group_id }}';
            $.ajax({
                url: '{{ route('employee.getQrGroups') }}',
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Please select a qr group</option>';
                    response.forEach(function(item) {
                        options += '<option value="' + item.id + '" ' + (qrGroupId == item.id ? 'selected' : '') + '>' + item.name + '</option>';
                    });
                    $('#qrGroupId').html(options);
                }
            });
        }
        function getSites() {
            var siteId = '{{ $user->site_id }}';
            $.ajax({
                url: '{{ route('employee.getSites') }}',
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Please select a site</option>';
                    response.forEach(function(item) {
                        options += '<option value="' + item.id + '" ' + (siteId == item.id ? 'selected' : '') + '>' + item.name + '</option>';
                    });
                    $('#siteId').html(options);
                }
            });
        }

        // Employee Action Handlers (Status Toggle, Relieve, Retire)
        function toggleEmployeeStatus(userId, isActive) {
            const status = isActive ? 'activate' : 'deactivate';
            Swal.fire({
                title: `Are you sure you want to ${status} this employee?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${status}`,
                customClass: { confirmButton: 'btn btn-primary me-3', cancelButton: 'btn btn-label-secondary' },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/employees/toggleStatus/${userId}`, { _token: '{{ csrf_token() }}', status: isActive ? 1 : 0 }, function(response) {
                        Swal.fire({ title: 'Updated!', text: response.data, icon: 'success' });
                    }).fail(() => Swal.fire('Error', 'Unable to update status', 'error'));
                }
            });
        }

        function confirmEmployeeAction(action, userId) {
            const text = action === 'relieve' ? 'relieve' : 'retire';
            Swal.fire({
                title: `Are you sure you want to ${text} this employee?`,
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${text}`,
                customClass: { confirmButton: 'btn btn-primary me-3', cancelButton: 'btn btn-label-secondary' },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/employees/${action}/${userId}`, { _token: '{{ csrf_token() }}' }, function(response) {
                        Swal.fire({ title: 'Success!', text: response.data, icon: 'success' });
                        setTimeout(() => location.reload(), 2000);
                    }).fail(() => Swal.fire('Error', `Unable to ${text} employee`, 'error'));
                }
            });
        }

        function loadBankDetails() {
            console.log("loadBankDetails called for user:", user?.id);
            try {
                if(!user || !user.bank_account) {
                    console.log("No bank account found or user object incomplete.");
                    // Reset fields for fresh entry
                    $('#bankName, #bankCode, #accountName, #accountNumber, #confirmAccountNumber, #branchName, #branchCode').val('');
                    return;
                }
                console.log("Found bank account:", user.bank_account);
                $('#bankName').val(user.bank_account.bank_name || '');
                $('#bankCode').val(user.bank_account.bank_code || '');
                $('#accountName').val(user.bank_account.account_name || '');
                $('#accountNumber').val(user.bank_account.account_number || '');
                $('#confirmAccountNumber').val(user.bank_account.account_number || '');
                $('#branchName').val(user.bank_account.branch_name || '');
                $('#branchCode').val(user.bank_account.branch_code || '');
                console.log("Bank fields populated.");
            } catch (e) {
                console.error("Critical error in loadBankDetails:", e);
            }
        }

        // Form Handlers & Modal Initializations
        document.addEventListener('DOMContentLoaded', function() {
            // Termination Modal Setup
            const terminateModal = document.getElementById('terminateEmployeeModal');
            if (terminateModal) {
                $(terminateModal).find('.select2').select2({ dropdownParent: $(terminateModal) });
                flatpickr("#exitDate", { dateFormat: 'Y-m-d', altInput: true, altFormat: 'M j, Y' });
                flatpickr("#lastWorkingDay", { dateFormat: 'Y-m-d', altInput: true, altFormat: 'M j, Y' });

                document.getElementById('terminateEmployeeForm')?.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Confirm Termination?',
                        text: "This action is major and processed immediately. Continue?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Terminate',
                        customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
                        buttonsStyling: false
                    }).then(res => {
                        if (res.isConfirmed) {
                            const btn = document.getElementById('terminateSubmitBtn');
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
                            $.ajax({
                                url: terminateUrl,
                                method: 'POST',
                                data: new FormData(this),
                                processData: false,
                                contentType: false,
                                success: (resp) => {
                                    if(resp.success) {
                                        Swal.fire({ icon: 'success', title: 'Terminated', text: resp.message, timer: 2000, showConfirmButton: false });
                                        setTimeout(() => location.reload(), 2000);
                                    }
                                },
                                error: (xhr) => {
                                    btn.disabled = false;
                                    btn.innerHTML = 'Confirm Termination';
                                    Swal.fire('Error', xhr.responseJSON?.message || 'Update failed', 'error');
                                }
                            });
                        }
                    });
                });
            }

            // Probation Forms Setup
            const probConfigs = [
                { id: 'confirmProbationForm', btnId: 'confirmProbationSubmitBtn', text: 'Confirm Completion' },
                { id: 'extendProbationForm', btnId: 'extendProbationSubmitBtn', text: 'Extend Probation' },
                { id: 'failProbationForm', btnId: 'failProbationSubmitBtn', text: 'Confirm Failure' }
            ];

            flatpickr("#newProbationEndDate", {
                dateFormat: 'Y-m-d', altInput: true, altFormat: 'M j, Y',
                minDate: '{{ $user->probation_end_date?->toDateString() }}' ? new Date('{{ $user->probation_end_date?->toDateString() }}').fp_incr(1) : "today"
            });

            probConfigs.forEach(config => {
                const form = document.getElementById(config.id);
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const btn = document.getElementById(config.btnId);
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
                        
                        fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: new FormData(form)
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({ icon: 'success', title: 'Updated', text: data.message, timer: 2000, showConfirmButton: false });
                                setTimeout(() => location.reload(), 2000);
                            } else {
                                throw new Error(data.message || 'Validation failed');
                            }
                        })
                        .catch(err => {
                            btn.disabled = false;
                            btn.innerHTML = config.text;
                            Swal.fire('Error', err.message, 'error');
                        });
                    });
                }
            });
        });

    </script>
    @vite(['resources/js/main-helper.js', 'resources/assets/js/app/employee-view.js'])
@endsection

