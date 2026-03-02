@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutMaster')

@section('title', 'My Leaves')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/animate-css/animate.scss'])
@vite(['resources/assets/vendor/scss/pages/hitech-portal.scss'])
@endsection

@section('content')



<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- HERO SECTION --}}
    <div class="leaves-hero animate__animated animate__fadeIn">
        <div class="leaves-hero-text">
            <div class="greeting">Leave Management</div>
            <div class="sub-text">Plan your time off and track request statuses.</div>
        </div>
        <div>
            <button type="button" class="btn btn-hitech" data-bs-toggle="modal" data-bs-target="#hitechApplyLeaveModal">
                <i class="bx bx-plus-circle me-2"></i> Apply for Leave
            </button>
        </div>
    </div>

    {{-- STATS SECTION --}}
    <div class="row g-4 mb-6">
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.05s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-teal"><i class="bx bx-calendar"></i></div>
                <div class="stat-label">Available Balance</div>
                <div class="stat-value">{{ auth()->user()->available_leave_count ?? 0 }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-blue"><i class="bx bx-list-ul"></i></div>
                <div class="stat-label">Total Requests</div>
                <div class="stat-value">{{ $leaves->count() }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-amber"><i class="bx bx-time"></i></div>
                <div class="stat-label">Pending Approval</div>
                <div class="stat-value">{{ $leaves->where('status', 'pending')->count() }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-red"><i class="bx bx-x-circle"></i></div>
                <div class="stat-label">Rejected</div>
                <div class="stat-value">{{ $leaves->where('status', 'rejected')->count() }}</div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
        <div class="hitech-card-header">
            <h5 class="title">My Leave History</h5>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All Requests</a></li>
                    <li><a class="dropdown-item" href="#">Approved</a></li>
                    <li><a class="dropdown-item" href="#">Pending</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Duration (From - To)</th>
                            <th>Total Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-teal me-3 mb-0" style="width:32px; height:32px; font-size:0.9rem;">
                                        <i class="bx bx-file"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $leave->leaveType->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-semibold">
                                    {{ \Carbon\Carbon::parse($leave->from_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->to_date)->format('d M, Y') }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $from = \Carbon\Carbon::parse($leave->from_date);
                                    $to = \Carbon\Carbon::parse($leave->to_date);
                                    $days = $from->diffInDays($to) + 1;
                                @endphp
                                <span class="badge bg-label-info">{{ $days }} {{ \Illuminate\Support\Str::plural('Day', $days) }}</span>
                            </td>
                            <td>
                                <span class="text-muted" title="{{ $leave->user_notes }}">{{ \Illuminate\Support\Str::limit($leave->user_notes, 25) }}</span>
                            </td>
                        <td>
                                @php
                                    $statusColor = 'secondary';
                                    $statusValue = $leave->status->value ?? $leave->status;
                                    if($statusValue == 'approved') $statusColor = 'success';
                                    elseif($statusValue == 'rejected') $statusColor = 'danger';
                                    elseif($statusValue == 'pending') $statusColor = 'warning';
                                @endphp
                                <span class="badge badge-hitech bg-label-{{ $statusColor }}">
                                    <i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>
                                    {{ ucfirst($statusValue) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $leave->created_at->format('d M, H:i') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bx bx-info-circle fs-2 d-block mb-2 opacity-50"></i>
                                No leave records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Apply Leave Modal -->
<div class="modal fade" id="hitechApplyLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-hitech">
            <div class="modal-header modal-header-hitech">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-header me-3">
                        <i class="bx bx-calendar-plus fs-3"></i>
                    </div>
                    <h5 class="modal-title modal-title-hitech" id="hitechApplyLeaveModalTitle">New Leave Request</h5>
                </div>
                <button type="button" class="btn-close-hitech" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form action="{{ route('user.leaves.store') }}" method="POST">
                @csrf
                <div class="modal-body modal-body-hitech">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="leave_type_id" class="form-label-hitech">Leave Type</label>
                            <select id="leave_type_id" name="leave_type_id" class="form-select form-select-hitech" required>
                                <option value="">Choose leave type...</option>
                                @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="from_date" class="form-label-hitech">Start Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control form-control-hitech" required>
                        </div>
                        <div class="col-md-6">
                            <label for="to_date" class="form-label-hitech">End Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control form-control-hitech" required>
                        </div>
                        <div class="col-12">
                            <label for="user_notes" class="form-label-hitech">Reason for Leave</label>
                            <textarea id="user_notes" name="user_notes" class="form-control form-control-hitech" rows="4" placeholder="Explain your reason briefly..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-hitech px-4">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
