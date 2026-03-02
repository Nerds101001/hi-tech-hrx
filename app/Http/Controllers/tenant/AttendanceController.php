<?php

namespace App\Http\Controllers\tenant;

use App\Enums\UserAccountStatus;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\Shift;
use App\Models\Team;
use AppConstants;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
  public function index()
  {
    $activeUsersCount = User::where('status', UserAccountStatus::ACTIVE)->count();
    
    $todayPresentCount = Attendance::whereDate('created_at', today())
      ->where('status', 'Present')
      ->count();
      
    $todayAbsentCount = Attendance::whereDate('created_at', today())
      ->where('status', 'Absent')
      ->count();
      
    $lateCount = Attendance::whereDate('created_at', today())
      ->where('status', 'Late')
      ->count();

    $onLeaveCount = \App\Models\LeaveRequest::where('status', \App\Enums\LeaveRequestStatus::APPROVED)
        ->whereDate('from_date', '<=', today())
        ->whereDate('to_date', '>=', today())
        ->count();

    $users = User::where('status', UserAccountStatus::ACTIVE)
      ->get();

    $shifts = Shift::get();
    $teams = Team::get();

    return view('tenant.attendance.index', [
      'pageConfigs' => ['contentLayout' => 'wide'],
      'users' => $users,
      'shifts' => $shifts,
      'teams' => $teams,
      'todayPresentCount' => $todayPresentCount,
      'todayAbsentCount' => $todayAbsentCount,
      'onLeaveCount' => $onLeaveCount,
      'lateCount' => $lateCount,
      'activeUsersCount' => $activeUsersCount
    ]);
  }

  public function indexAjax(Request $request)
  {
    $query = Attendance::query()
      ->with(['attendanceLogs', 'user', 'shift']);

    // User filter
    if ($request->has('userId') && $request->input('userId')) {
      $query->where('user_id', $request->input('userId'));
    }

    // Shift filter
    if ($request->has('shiftId') && $request->input('shiftId')) {
        $query->where('shift_id', $request->input('shiftId'));
    }

    // Team Filter
    if ($request->has('teamId') && $request->input('teamId')) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('team_id', $request->input('teamId'));
        });
    }

    // Date filter
    if ($request->has('date') && $request->input('date')) {
      $query->whereDate('created_at', $request->input('date'));
    } else {
      $query->whereDate('created_at', Carbon::today());
    }

    // Search term filter
    if ($request->has('searchTerm') && $request->input('searchTerm')) {
        $searchTerm = $request->input('searchTerm');
        $query->whereHas('user', function($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%{$searchTerm}%")
              ->orWhere('last_name', 'like', "%{$searchTerm}%")
              ->orWhere('code', 'like', "%{$searchTerm}%");
        });
    }

    return DataTables::of($query)
      ->addIndexColumn()
      ->addColumn('date', function ($attendance) {
        return $attendance->created_at->format('d/m/Y');
      })
      ->editColumn('check_in_time', function ($attendance) {
        $checkInAt = $attendance->attendanceLogs->where('type', 'check_in')->first();
        return $checkInAt ? $checkInAt->created_at->format('h:i A') : '<span class="text-muted">N/A</span>';
      })
      ->editColumn('check_out_time', function ($attendance) {
        $checkOutAt = $attendance->attendanceLogs->where('type', 'check_out')->last();
        return $checkOutAt ? $checkOutAt->created_at->format('h:i A') : '<span class="text-muted">--</span>';
      })
      ->addColumn('shift', function ($attendance) {
        return $attendance->shift ? '<span class="badge badge-hitech-success">'.$attendance->shift->name.'</span>' : '<span class="text-muted">N/A</span>';
      })
      ->addColumn('working_hours', function ($attendance) {
          return '<span class="fw-bold text-dark">'.($attendance->total_working_hours ?: '0:00').'h</span>';
      })
      ->addColumn('status', function ($attendance) {
          $status = $attendance->status ?: 'Present';
          $color = 'success';
          $icon = 'bx-check-circle';
          if($status == 'Absent') { $color = 'danger'; $icon = 'bx-x-circle'; }
          if($status == 'Late') { $color = 'warning'; $icon = 'bx-time'; }
          return '<span class="badge bg-label-'.$color.' px-3 py-2 rounded-pill"><i class="bx '.$icon.' me-1"></i>'.$status.'</span>';
      })
      ->addColumn('actions', function ($attendance) {
          return '<div class="d-flex align-items-center gap-2">'.
                 '<button class="btn btn-sm btn-icon hitech-action-icon" onclick="viewLogs('.$attendance->id.')" title="View Logs"><i class="bx bx-list-ul"></i></button>'.
                 '<button class="btn btn-sm btn-icon hitech-action-icon" onclick="editRecord('.$attendance->id.')" title="Edit"><i class="bx bx-edit"></i></button>'.
                 '</div>';
      })
      ->addColumn('user', function ($attendance) {
        $employeeViewUrl = route('employees.show', $attendance->user_id);
        if ($attendance->user->profile_picture) {
          $profileOutput = '<img src="' . asset('storage/' . AppConstants::BaseFolderEmployeeProfileWithSlash . $attendance->user->profile_picture) . '"  alt="Avatar" class="avatar rounded-circle " />';
        } else {
          $profileOutput = '<span class="avatar-initial rounded-circle bg-label-info">' . $attendance->user->getInitials() . '</span>';
        }

        return '<div class="d-flex justify-content-start align-items-center user-name">' .
          '<div class="avatar-wrapper">' .
          '<div class="avatar avatar-sm me-4">' .
          $profileOutput .
          '</div>' .
          '</div>' .
          '<div class="d-flex flex-column">' .
          '<a href="' .
          $employeeViewUrl .
          '" class="text-heading text-truncate"><span class="fw-medium">' .
          $attendance->user->getFullName() .
          '</span></a>' .
          '<small class="text-muted">' .
          $attendance->user->code .
          '</small>' .
          '</div>' .
          '</div>';

      })
      ->rawColumns(['user', 'status', 'actions', 'shift', 'working_hours', 'check_in_time', 'check_out_time'])
      ->make(true);
  }
}
