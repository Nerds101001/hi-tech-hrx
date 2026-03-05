<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AttendanceImport, $request->file('file'));
            return redirect()->back()->with('success', 'Attendance imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing attendance: ' . $e->getMessage());
        }
    }
}
