<?php

namespace App\Http\Controllers\tenant\users;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UserPayrollController extends Controller
{
    public function index()
    {
        $payslips = Payslip::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenant.users.payroll.index', compact('payslips'));
    }

    public function download($id)
    {
        $payslip = Payslip::with(['user.designation', 'user.team', 'user.bankAccount'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Placeholder for PDF generation
        // If dompdf or similar is installed, we would stream/download here
        // For now, we might just return a view or a placeholder response
        
        $user = $payslip->user;
    $ctcAnnum = $user?->ctc_offered ?? ($payslip->net_salary * 12);
    $ctcMonth = $ctcAnnum / 12;
    $symbol = '₹'; // Default or from settings

    // Synchronized Breakdown Logic
    $basicMonth = $ctcMonth * 0.5;
    $hraMonth = $ctcMonth * 0.25;
    $medicalMonth = 2500;
    $eduMonth = 200;
    $ltaMonth = 2500;
    $sumA = $basicMonth + $hraMonth + $medicalMonth + $eduMonth + $ltaMonth;
    $specialAllowance = max(0, $ctcMonth - $sumA);
    $profTax = 200;
    $pfAmount = 1800;
    $deductions = $profTax + $pfAmount;
    $netSalary = $ctcMonth - $deductions;

    $data = [
        'payslip' => $payslip,
        'user' => $user,
        'basicMonth' => $basicMonth,
        'hraMonth' => $hraMonth,
        'medicalMonth' => $medicalMonth,
        'eduMonth' => $eduMonth,
        'ltaMonth' => $ltaMonth,
        'specialAllowance' => $specialAllowance,
        'profTax' => $profTax,
        'pfAmount' => $pfAmount,
        'netSalary' => $netSalary,
        'currencySymbol' => $symbol,
        'company' => [
            'name' => 'HI TECH HRX',
            'address' => 'Global Business Park, Gurgaon',
            'phone' => '+91-1234567890',
            'email' => 'hr@hitechhrx.com',
            'logoBase64' => '' // Add logo if available
        ]
    ];

    $pdf = Pdf::loadView('payslip.pdf', $data);
    return $pdf->download('payslip-' . $payslip->id . '.pdf');
    }
}
