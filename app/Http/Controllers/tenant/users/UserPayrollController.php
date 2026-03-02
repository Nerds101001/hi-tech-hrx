<?php

namespace App\Http\Controllers\tenant\users;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use Illuminate\Http\Request;
use PDF; // Assuming a PDF wrapper exists, otherwise we'll need to check packages

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
        $payslip = Payslip::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Placeholder for PDF generation
        // If dompdf or similar is installed, we would stream/download here
        // For now, we might just return a view or a placeholder response
        
        return view('tenant.users.payroll.show', compact('payslip'));
    }
}
