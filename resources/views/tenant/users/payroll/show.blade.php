@extends('layouts/layoutMaster')

@section('title', 'Payslip Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card invoice-preview-card">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                <div class="mb-xl-0 mb-4">
                    <div class="d-flex svg-illustration mb-3 gap-2">
                        <span class="app-brand-text demo text-body fw-bolder">Payslip #{{ $payslip->code }}</span>
                    </div>
                    <p class="mb-1">{{ $payslip->created_at->format('F Y') }}</p>
                </div>
                <div>
                    <h4>Net Salary: {{ $settings->currency_symbol }}{{ number_format($payslip->net_salary, 2) }}</h4>
                    <div class="mb-1">
                        <span class="me-1">Date:</span>
                        <span class="fw-semibold">{{ $payslip->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-0" />
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-md-0 mb-3">
                    <h5>Earnings</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="ps-0">Basic Salary:</td>
                                <td class="fw-semibold text-end">{{ $settings->currency_symbol }}{{ number_format($payslip->basic_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-0">Benefits:</td>
                                <td class="fw-semibold text-end">{{ $settings->currency_symbol }}{{ number_format($payslip->total_benefits, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Deductions</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="ps-0">Total Deductions:</td>
                                <td class="fw-semibold text-end text-danger">-{{ $settings->currency_symbol }}{{ number_format($payslip->total_deductions, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button onclick="window.print()" class="btn btn-primary me-2"><i class='bx bx-printer me-1'></i> Print / Save as PDF</button>
                    <a href="{{ route('user.payroll.index') }}" class="btn btn-label-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
