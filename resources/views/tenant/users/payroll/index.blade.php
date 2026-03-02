@extends('layouts/layoutMaster')

@section('title', 'My Payroll')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/animate-css/animate.scss'])
@vite(['resources/assets/vendor/scss/pages/hitech-portal.scss'])
@endsection

@section('content')



<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- HERO SECTION --}}
    <div class="payroll-hero animate__animated animate__fadeIn">
        <div class="payroll-hero-text">
            <div class="greeting">Payroll & Earnings</div>
            <div class="sub-text">View your salary history and download your official payslips.</div>
        </div>
        <div>
            <div class="text-white text-end">
                <i class="bx bxs-badge-dollar" style="font-size:3rem; opacity:0.15; position:absolute; top:10px; right:10px;"></i>
                <div style="font-size:0.75rem; font-weight:700; opacity:0.7; text-transform:uppercase; letter-spacing:0.05em;">Currency</div>
                <div style="font-size:1.5rem; font-weight:800;">{{ $settings->currency_symbol ?? '$' }} ({{ $settings->currency ?? 'USD' }})</div>
            </div>
        </div>
    </div>

    @php
        $latestPayslip = $payslips->first();
        $totalNetPaid = $payslips->where('status', 'paid')->sum('net_salary');
        $currency = $settings->currency_symbol ?? '$';
    @endphp

    {{-- STATS SECTION --}}
    <div class="row g-4 mb-6">
        <div class="col-sm-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.05s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-teal"><i class="bx bx-star"></i></div>
                <div class="stat-label">Last Net Salary</div>
                <div class="stat-value">{{ $currency }}{{ $latestPayslip ? number_format($latestPayslip->net_salary, 2) : '0.00' }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-blue"><i class="bx bx-layer"></i></div>
                <div class="stat-label">Total Earnings (YTD)</div>
                <div class="stat-value">{{ $currency }}{{ number_format($totalNetPaid, 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
            <div class="hitech-stat-card">
                <div class="stat-icon-wrap icon-amber"><i class="bx bx-file-blank"></i></div>
                <div class="stat-label">Available Slips</div>
                <div class="stat-value">{{ $payslips->count() }}</div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
        <div class="hitech-card-header">
            <h5 class="title">Payslip History</h5>
            <button class="btn btn-sm btn-label-secondary">
                <i class="bx bx-help-circle me-1"></i> Payroll Help
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Payslip ID</th>
                            <th>Period</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $payslip)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-teal me-3 mb-0" style="width:32px; height:32px; font-size:0.9rem;">
                                        <i class="bx bx-spreadsheet"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $payslip->code }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-semibold">
                                    {{ $payslip->created_at->format('F, Y') }}
                                </div>
                                <small class="text-muted">Generated on {{ $payslip->created_at->format('d M') }}</small>
                            </td>
                            <td>
                                <div class="text-success fw-bold fs-5">
                                    {{ $currency }}{{ number_format($payslip->net_salary, 2) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-hitech bg-label-{{ $payslip->status === 'paid' ? 'success' : 'warning' }}">
                                    <i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>
                                    {{ ucfirst($payslip->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('user.payroll.download', $payslip->id) }}" class="btn-hitech-sm" target="_blank">
                                    <i class="bx bx-download"></i> PDF
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bx bx-info-circle fs-2 d-block mb-2 opacity-50"></i>
                                No salary slips found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
