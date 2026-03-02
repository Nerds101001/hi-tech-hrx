<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    public function index()
    {
        return view('tenant.payroll.index', [
            'pageConfigs' => ['contentLayout' => 'wide']
        ]);
    }

    public function indexAjax(Request $request)
    {
        $query = Payslip::query()->with('user');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('employee', function ($item) {
                return $item->user->getFullName();
            })
            ->addColumn('actions', function ($item) {
                return '<div class="d-flex align-items-center gap-2">' .
                    '<button class="btn btn-sm btn-icon hitech-action-icon" onclick="viewPayslip(' . $item->id . ')" title="View"><i class="bx bx-show"></i></button>' .
                    '<button class="btn btn-sm btn-icon hitech-action-icon" onclick="downloadPayslip(' . $item->id . ')" title="Download"><i class="bx bx-download"></i></button>' .
                    '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
