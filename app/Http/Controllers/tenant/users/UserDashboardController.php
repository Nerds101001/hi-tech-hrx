<?php

namespace App\Http\Controllers\tenant\users;

use App\Enums\LeaveRequestStatus;
use App\Enums\UserAccountStatus;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DocumentRequest;
use App\Models\ExpenseRequest;
use App\Models\LeaveRequest;
use App\Models\LoanRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\SOSLog;
use App\Models\Visit;
use App\Models\LeaveType;
use App\Models\ExpenseType;
use App\Models\Holiday;
use App\Models\Notice;
use App\Models\Payslip;
use App\Helpers\NotificationHelper;
use App\Notifications\Leave\NewLeaveRequest;
use App\Notifications\Expense\NewExpenseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use AppConstants;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isHR = $user->hasRole('hr');
        $isFieldEmployee = $user->hasRole('field_employee');
        $isManager = $user->hasRole('manager');

        // Common Personal Stats
        $myLeavesCount = LeaveRequest::where('user_id', $user->id)->count();
        $myExpensesCount = ExpenseRequest::where('user_id', $user->id)->count();
        $myAttendanceCount = Attendance::where('user_id', $user->id)->count();
        $mySOSCount = SOSLog::where('user_id', $user->id)->count();

        // --- Revamp Data Points ---
        $nextHoliday = Holiday::where('date', '>=', now()->toDateString())
            ->where('status', 1)
            ->orderBy('date', 'asc')
            ->first();

        $recentNotices = Notice::where('status', 1)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Team Out Today (Approved leaves for today)
        $teamOutTodayQuery = LeaveRequest::whereDate('from_date', '<=', now())
            ->whereDate('to_date', '>=', now())
            ->where('status', LeaveRequestStatus::APPROVED)
            ->with('user');

        // Scoping for Manager
        if ($isManager) {
            $teamOutTodayQuery->whereHas('user', function ($q) use ($user) {
                $q->where('team_id', $user->team_id);
            });
        }
        $teamOutToday = $teamOutTodayQuery->get();

        // Payroll Trend (Last 2 payslips comparison)
        $latestPayslips = Payslip::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(2)
            ->get();
        $payrollTrend = 0;
        $latestNetSalary = 0;
        if ($latestPayslips->count() >= 1) {
            $latestNetSalary = $latestPayslips[0]->net_salary;
            if ($latestPayslips->count() == 2 && $latestPayslips[1]->net_salary > 0) {
                $payrollTrend = (($latestPayslips[0]->net_salary - $latestPayslips[1]->net_salary) / $latestPayslips[1]->net_salary) * 100;
            }
        }
        // --------------------------

        // Global Stats (Needed for HR and Admin)
        $totalUser = User::count();
        $active = User::where('status', UserAccountStatus::ACTIVE)->count();
        $presentUsersCount = Attendance::whereDate('created_at', now())->count();
        
        // Pending Requests (All for now, could be scoped to team for manager)
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $pendingExpenseRequests = ExpenseRequest::where('status', 'pending')->count();

        // 1. HR Dashboard (First Priority)
        if ($isHR) {
            // HR-specific calculations
            $presentUsersCountLastWeek = Attendance::whereBetween('created_at', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()])
                ->where('check_out_time', '!=', null)
                ->get()
                ->sum(function ($attendance) {
                    return $attendance->check_in_time->diffInMinutes($attendance->check_out_time);
            });

            $thisWeekWorkingHours = Attendance::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('check_out_time', '!=', null)
                ->get()
                ->sum(function ($attendance) {
                    return $attendance->check_in_time->diffInMinutes($attendance->check_out_time);
            });

            $todayHours = Attendance::whereDate('created_at', now())
                ->where('check_out_time', '!=', null)
                ->get()
                ->sum(function ($attendance) {
                    return $attendance->check_in_time->diffInMinutes($attendance->check_out_time);
            });

            $onLeaveUsersCount = LeaveRequest::whereDate('from_date', now())
                ->where('status', LeaveRequestStatus::APPROVED)
                ->count();

            return view('tenant.users.dashboard.hr-index', [
                'totalUser' => $totalUser,
                'activeEmployees' => $active,
                'active' => $active,
                'presentUsersCount' => $presentUsersCount,
                'pendingLeaveRequests' => $pendingLeaveRequests,
                'pendingExpenseRequests' => $pendingExpenseRequests,
                'pendingDocumentRequests' => DocumentRequest::where('status', 'pending')->count(),
                'pendingLoanRequests' => LoanRequest::where('status', 'pending')->count(),
                'thisWeekWorkingHours' => round($thisWeekWorkingHours, 2),
                'todayHours' => round($todayHours, 2),
                'tasks' => Task::where('status', 'new')->count(),
                'onGoingTasks' => Task::where('status', 'in_progress')->count(),
                'todayPresentUsers' => $presentUsersCount,
                'todayAbsentUsers' => $active - $presentUsersCount,
                'presentUsersCountLastWeek' => $presentUsersCountLastWeek,
                'absentUsersCountLastWeek' => $active - $presentUsersCountLastWeek,
                'onLeaveUsersCount' => $onLeaveUsersCount,
                'isSelfService' => false,
                'myLeavesCount' => $myLeavesCount,
                'myExpensesCount' => $myExpensesCount,
                'mySOSCount' => $mySOSCount,
                'nextHoliday' => $nextHoliday,
                'recentNotices' => $recentNotices,
                'teamOutToday' => $teamOutToday,
                'payrollTrend' => $payrollTrend,
                'latestNetSalary' => $latestNetSalary
            ]);
        }

        // 2. Employee Dashboard
        if ($isFieldEmployee) {
            return view('tenant.users.dashboard.employee-index', compact(
                'myLeavesCount', 
                'myExpensesCount', 
                'myAttendanceCount', 
                'mySOSCount',
                'nextHoliday',
                'recentNotices',
                'payrollTrend',
                'latestNetSalary'
            ));
        }

        // Global Stats (Needed for Manager and HR)
        $totalUser = User::count();
        $active = User::where('status', UserAccountStatus::ACTIVE)->count();
        $presentUsersCount = Attendance::whereDate('created_at', now())->count();
        
        // Pending Requests (All for now, could be scoped to team for manager)
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $pendingExpenseRequests = ExpenseRequest::where('status', 'pending')->count();

        // 2. Manager Dashboard
        if ($isManager) {
            return view('tenant.users.dashboard.manager-index', [
                'pendingLeaveRequests' => $pendingLeaveRequests,
                'pendingExpenseRequests' => $pendingExpenseRequests,
                'activeEmployees' => $active, // Total active for now
                'todayPresentUsers' => $presentUsersCount,
                'myLeavesCount' => $myLeavesCount,
                'myExpensesCount' => $myExpensesCount,
                'mySOSCount' => $mySOSCount,
                'nextHoliday' => $nextHoliday,
                'recentNotices' => $recentNotices,
                'teamOutToday' => $teamOutToday,
                'payrollTrend' => $payrollTrend,
                'latestNetSalary' => $latestNetSalary
            ]);
        }

        // 3. Admin / Default Dashboard (Full View)
        $presentUsersCountLastWeek = Attendance::whereBetween('created_at', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()])
            ->where('check_out_time', '!=', null)
            ->get()
            ->sum(function ($attendance) {
                return $attendance->check_in_time->diffInMinutes($attendance->check_out_time);
        });

        $thisWeekWorkingHours = Attendance::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('check_out_time', '!=', null)
            ->get()
            ->sum(function ($attendance) {
                return $attendance->check_in_time->diffInMinutes($attendance->check_out_time);
        });

        $todayHours = Attendance::whereDate('created_at', now())
            ->where('check_out_time', '!=', null)
            ->get()
            ->sum(function ($attendance) {
                return $attendance->check_in_time->diffInMinutes($attendance->check_out_time);
        });

        $onLeaveUsersCount = LeaveRequest::whereDate('from_date', now())
            ->where('status', LeaveRequestStatus::APPROVED)
            ->count();

        return view('tenant.users.dashboard.index', [
            'totalUser' => $totalUser,
            'activeEmployees' => $active,
            'active' => $active,
            'presentUsersCount' => $presentUsersCount,
            'pendingLeaveRequests' => $pendingLeaveRequests,
            'pendingExpenseRequests' => $pendingExpenseRequests,
            'pendingDocumentRequests' => DocumentRequest::where('status', 'pending')->count(),
            'pendingLoanRequests' => LoanRequest::where('status', 'pending')->count(),
            'thisWeekWorkingHours' => round($thisWeekWorkingHours, 2),
            'todayHours' => round($todayHours, 2),
            'tasks' => Task::where('status', 'new')->count(),
            'onGoingTasks' => Task::where('status', 'in_progress')->count(),
            'todayPresentUsers' => $presentUsersCount,
            'todayAbsentUsers' => $active - $presentUsersCount,
            'presentUsersCountLastWeek' => $presentUsersCountLastWeek,
            'absentUsersCountLastWeek' => $active - $presentUsersCountLastWeek,
            'onLeaveUsersCount' => $onLeaveUsersCount,
            'isSelfService' => false,
            'myLeavesCount' => $myLeavesCount,
            'myExpensesCount' => $myExpensesCount,
            'mySOSCount' => $mySOSCount,
            'nextHoliday' => $nextHoliday,
            'recentNotices' => $recentNotices,
            'teamOutToday' => $teamOutToday,
            'payrollTrend' => $payrollTrend,
            'latestNetSalary' => $latestNetSalary
        ]);
    }

    public function leaveIndex()
    {
        $leaves = LeaveRequest::where('user_id', auth()->id())->with('leaveType')->orderBy('id', 'desc')->get();
        $leaveTypes = LeaveType::where('status', 1)->get();
        return view('tenant.users.leaves.index', compact('leaves', 'leaveTypes'));
    }

    public function leaveStore(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'user_notes' => 'required|string|max:1000',
        ]);

        $leaveRequest = new LeaveRequest();
        $leaveRequest->user_id = auth()->id();
        $leaveRequest->leave_type_id = $validated['leave_type_id'];
        $leaveRequest->from_date = $validated['from_date'];
        $leaveRequest->to_date = $validated['to_date'];
        $leaveRequest->user_notes = $validated['user_notes'];
        $leaveRequest->status = LeaveRequestStatus::PENDING;
        $leaveRequest->save();

        NotificationHelper::notifyAdminHR(new NewLeaveRequest($leaveRequest));

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }

    public function expenseIndex()
    {
        $expenses = ExpenseRequest::where('user_id', auth()->id())->with('expenseType')->orderBy('id', 'desc')->get();
        $expenseTypes = ExpenseType::where('status', 1)->get();
        return view('tenant.users.expenses.index', compact('expenses', 'expenseTypes'));
    }

    public function expenseStore(Request $request)
    {
        $validated = $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'for_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'remarks' => 'required|string|max:1000',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $expenseRequest = new ExpenseRequest();
        $expenseRequest->user_id = auth()->id();
        $expenseRequest->expense_type_id = $validated['expense_type_id'];
        $expenseRequest->for_date = $validated['for_date'];
        $expenseRequest->amount = $validated['amount'];
        $expenseRequest->remarks = $validated['remarks'];
        $expenseRequest->status = 'pending';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->putFileAs(AppConstants::BaseFolderExpenseProofs, $file, $fileName);
            $expenseRequest->document_url = $fileName;
        }

        $expenseRequest->save();

        NotificationHelper::notifyAdminHR(new NewExpenseRequest($expenseRequest));

        return redirect()->back()->with('success', 'Expense request submitted successfully.');
    }

    public function attendanceIndex()
    {
        $attendances = Attendance::where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        return view('tenant.users.attendance.index', compact('attendances'));
    }

    public function sosIndex()
    {
        $sosLogs = SOSLog::where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        return view('tenant.users.sos.index', compact('sosLogs'));
    }

    public function visitIndex()
    {
        $visits = Visit::where('created_by_id', auth()->id())->orderBy('id', 'desc')->get();
        return view('tenant.users.visits.index', compact('visits'));
    }
}
