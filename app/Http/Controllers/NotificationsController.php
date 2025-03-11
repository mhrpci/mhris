<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\CashAdvance;
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationsController extends Controller
{
    private $notifications = [
        'leave_requests' => [],
        'cash_advances' => [],
        'leave_approved' => [],
        'leave_validated' => [],
        'leave_rejected' => [],
        'cash_advance_active' => [],
        'cash_advance_declined' => [],
    ];

    // Method to fetch notifications data
    public function getNotificationsData(Request $request)
    {
        try {
            $this->generateNotifications();

            $response = [
                'count' => $this->countTotalNotifications(),
                'notifications' => $this->generateDropdownHtml(),
                'timestamp' => now()->timestamp,
                'toast' => [
                    'title' => 'New Notification',
                    'message' => $this->getLatestNotificationMessage(),
                    'icon' => $this->getLatestNotificationIcon()
                ]
            ];

            // Broadcast to other users
            broadcast(new NewNotification($response))->toOthers();

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error in getNotificationsData: ' . $e->getMessage());
            return response()->json([
                'count' => 0,
                'notifications' => '',
                'timestamp' => now()->timestamp
            ]);
        }
    }

    // Generate all notifications
    private function generateNotifications()
    {
        $this->generateLeaveRequestNotifications();
        $this->generateCashAdvanceRequestNotifications();
        $this->generateLeaveApprovedNotification();
        $this->generateLeaveValidatedNotification();
        $this->generateLeaveRejectedNotification();
        $this->generateCashAdvanceActiveNotification();
        $this->generateCashAdvanceDeclinedNotification();
    }

    private function generateLeaveRequestNotifications()
    {
        $user = Auth::user();
        if ($user && $user->hasAnyRole(['Super Admin', 'Admin'])) {
            $leaves = Leave::with('employee')
                ->where('status', 'pending')
                ->where('is_read', false)
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_requests'][] = [
                    'icon' => 'fas fa-calendar-times',
                    'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                    'time' => $leave->created_at->diffForHumans(),
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason
                    ]
                ];
            }
        } elseif ($user && $user->hasRole('Supervisor')) {
            $supervisorDepartmentId = $user->department_id;
            
            $leaves = Leave::with('employee')
                ->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId);
                })
                ->where('status', 'pending')
                ->where('is_read', false)
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_requests'][] = [
                    'icon' => 'fas fa-calendar-times',
                    'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                    'time' => $leave->created_at->diffForHumans(),
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason
                    ]
                ];
            }
        }
    }

    private function generateCashAdvanceRequestNotifications()
    {
        $user = Auth::user();
        if ($user && $user->hasAnyRole(['Super Admin', 'Admin'])) {
            $advances = CashAdvance::with('employee')
                ->where('status', 'pending')
                ->where('is_read', false)
                ->get();

            foreach ($advances as $advance) {
                $this->notifications['cash_advances'][] = [
                    'icon' => 'fas fa-money-bill-wave',
                    'text' => "{$advance->employee->first_name} {$advance->employee->last_name} requested cash advance",
                    'time' => $advance->created_at->diffForHumans(),
                    'data' => [
                        'id' => $advance->id,
                        'type' => 'cash_advance',
                        'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                        'amount' => $advance->amount,
                        'reason' => $advance->reason
                    ]
                ];
            }
        }
    }
    

    private function generateLeaveApprovedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasAnyRole(['HR ComBen', 'Admin', 'Super Admin'])) {
            $leaves = Leave::with(['employee', 'approvedByUser'])
                ->where('status', 'approved')
                ->where('validated_by_signature', null)
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_approved'][] = [
                    'icon' => 'fas fa-check-circle',
                    'text' => "Leave request for {$leave->employee->first_name} {$leave->employee->last_name} has been approved",
                    'time' => $leave->updated_at->diffForHumans(),
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_approved',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'approved_by' => $leave->approvedByUser->name,
                        'approved_at' => $leave->updated_at->format('M d, Y h:i A'),
                        'leave_type' => $leave->type->name,
                        'duration' => $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s)',
                        'payment_status' => $leave->payment_status,
                        'department' => $leave->employee->department->name,
                        'reason' => $leave->reason
                    ]
                ];
            }
        }
    }

    private function generateLeaveValidatedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $leaves = Leave::with(['employee', 'approvedByUser'])
                ->where('status', 'approved')
                ->where('is_view', false)
                ->where('validated_by_signature', '!=', null)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_validated'][] = [
                    'icon' => 'fas fa-signature',
                    'text' => "Your leave request has been validated",
                    'time' => $leave->updated_at->diffForHumans(),
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_validated',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'approved_by' => $leave->approvedByUser->name,
                        'approved_at' => $leave->updated_at->format('M d, Y h:i A'),
                        'leave_type' => $leave->type->name,
                        'duration' => $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s)',
                        'payment_status' => $leave->payment_status,
                        'department' => $leave->employee->department->name,
                        'reason' => $leave->reason
                    ]
                ];
            }
        }
    }

    private function generateLeaveRejectedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $leaves = Leave::with(['employee', 'rejectedByUser'])
                ->where('status', 'rejected')
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_rejected'][] = [
                    'icon' => 'fas fa-signature',
                    'text' => "Your leave request has been rejected",
                    'time' => $leave->updated_at->diffForHumans(),
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_rejected',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'rejected_by' => $leave->rejectedByUser->name,
                        'rejected_at' => $leave->updated_at->format('M d, Y h:i A'),
                        'leave_type' => $leave->type->name,
                        'duration' => $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s)',
                        'payment_status' => $leave->payment_status,
                        'department' => $leave->employee->department->name,
                        'reason' => $leave->reason
                    ]
                ];
            }
        }
    }

    private function generateCashAdvanceActiveNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $advances = CashAdvance::with(['employee', 'approvedByUser'])
                ->where('status', 'active')
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->get();

            foreach ($advances as $advance) {
                if (isset($advance->approvedByUser)) {
                    $this->notifications['cash_advance_approved'][] = [
                        'icon' => 'fas fa-check-circle',
                        'text' => "Your cash advance request has been approved",
                        'time' => $advance->updated_at->diffForHumans(),
                        'data' => [
                            'id' => $advance->id,
                            'type' => 'cash_advance_approved',
                            'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                            'amount' => $advance->amount,
                            'approved_by' => $advance->approvedByUser->name,
                            'approved_at' => $advance->updated_at->format('M d, Y h:i A'),
                            'reason' => $advance->reason
                        ]
                    ];
                }
            }
        }
    }

    private function generateCashAdvanceDeclinedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $advances = CashAdvance::with(['employee', 'rejectedByUser'])
                ->where('status', 'declined')
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->get();

            foreach ($advances as $advance) {
                if (isset($advance->rejectedByUser)) {
                    $this->notifications['cash_advance_rejected'][] = [
                        'icon' => 'fas fa-times-circle',
                        'text' => "Your cash advance request has been rejected",
                        'time' => $advance->updated_at->diffForHumans(),
                        'data' => [
                            'id' => $advance->id,
                            'type' => 'cash_advance_rejected',
                            'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                            'amount' => $advance->amount,
                            'rejected_by' => $advance->rejectedByUser->name,
                            'rejected_at' => $advance->updated_at->format('M d, Y h:i A'),
                            'reason' => $advance->reason
                        ]
                    ];
                }
            }
        }
    }

    private function countTotalNotifications()
    {
        return array_sum(array_map('count', $this->notifications));
    }

    private function generateDropdownHtml()
    {
        $allNotifications = $this->flattenNotifications();
        $html = '';

        if (empty($allNotifications)) {
            return '<div class="empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>No new notifications</p>
                    </div>';
        }

        foreach ($allNotifications as $notification) {
            $url = $this->getNotificationUrl($notification);
            $statusClass = $this->getStatusClass($notification);
            $timeAgo = $notification['time'];
            
            $html .= "
            <a href='{$url}' class='notification-item {$statusClass}'>
                <div class='notification-icon {$this->getIconClass($notification)}'>
                    <i class='{$notification['icon']}'></i>
                </div>
                <div class='notification-content'>
                    <div class='notification-title'>{$this->getNotificationTitle($notification)}</div>
                    <div class='notification-text'>{$notification['text']}</div>
                    <div class='notification-time'>
                        <i class='far fa-clock mr-1'></i>{$timeAgo}
                    </div>
                </div>
            </a>";
        }

        return $html;
    }

    private function getNotificationUrl($notification)
    {
        if (isset($notification['data'])) {
            if (in_array($notification['data']['type'], ['leave', 'leave_approved', 'leave_validated', 'leave_rejected'])) {
                return url("/leaves/{$notification['data']['id']}");
            }
            
            if (in_array($notification['data']['type'], ['cash_advance', 'cash_advance_approved', 'cash_advance_declined'])) {
                return url("/cash_advances/{$notification['data']['id']}");
            }
        }
        
        return '#';
    }

    private function getIconClass($notification)
    {
        if (strpos($notification['text'], 'requested leave') !== false) {
            return 'leave';
        }
        if (strpos($notification['text'], 'requested cash advance') !== false) {
            return 'cash-advance';
        }
        return 'default';
    }

    private function getNotificationTitle($notification)
    {
        if (strpos($notification['text'], 'requested leave') !== false) {
            return 'Leave Request';
        }
        if (strpos($notification['text'], 'requested cash advance') !== false) {
            return 'Cash Advance Request';
        }
        return 'Notification';
    }

    private function getStatusClass($notification)
    {
        // You can add logic here to determine if a notification is read/unread
        return 'unread';
    }

    private function flattenNotifications()
    {
        $flattened = [];
        foreach ($this->notifications as $notifications) {
            $flattened = array_merge($flattened, $notifications);
        }
        
        usort($flattened, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($flattened, 0, 10);
    }

    private function getLatestNotificationMessage()
    {
        $allNotifications = $this->flattenNotifications();
        return !empty($allNotifications) ? $allNotifications[0]['text'] : '';
    }

    private function getLatestNotificationIcon()
    {
        $allNotifications = $this->flattenNotifications();
        return !empty($allNotifications) ? $allNotifications[0]['icon'] : 'fas fa-bell';
    }

    public function showAllNotifications()
    {
        try {
            $sevenDaysAgo = now()->subDays(7);
            $user = Auth::user();
            
            // Initialize query builders
            $leavesQuery = Leave::with(['employee', 'employee.department', 'type', 'approvedByUser'])
                ->where('created_at', '>=', $sevenDaysAgo);
                
            $advancesQuery = CashAdvance::with(['employee', 'employee.department', 'payments'])
                ->where('created_at', '>=', $sevenDaysAgo);

            // Apply role-based restrictions
            if ($user && $user->hasAnyRole(['Super Admin', 'Admin'])) {
                // Super Admin and Admin can see all leave notifications and cash advances
                // No additional restrictions needed
            } elseif ($user && $user->hasRole('Supervisor')) {
                // Supervisors can only see leave notifications from their department
                $supervisorDepartmentId = $user->department_id;
                
                $leavesQuery->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId);
                });
                
                // Set advances query to return no results for supervisors
                $advancesQuery->where('id', 0);
            } elseif ($user && $user->hasRole('HR ComBen')) {
                // HR ComBen can only see approved leaves that need validation
                $leavesQuery->where('status', 'approved')
                           ->whereNull('validated_by_signature');
                
                // HR ComBen cannot see cash advances
                $advancesQuery->where('id', 0);
            } elseif ($user && $user->hasRole('Employee')) {
                // Regular employees can only see their own validated leave notifications
                $leavesQuery->where('status', 'approved')
                           ->whereNotNull('validated_by_signature')
                           ->whereHas('employee', function($query) use ($user) {
                               $query->where('email_address', $user->email);
                           });
                
                // Employees cannot see cash advances
                $advancesQuery->where('id', 0);
            } else {
                // Any other role sees nothing
                $leavesQuery->where('id', 0);
                $advancesQuery->where('id', 0);
            }

            // Execute queries with ordering
            $leaves = $leavesQuery->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($leave) {
                    $dateFrom = $leave->date_from ? Carbon::parse($leave->date_from) : null;
                    $dateTo = $leave->date_to ? Carbon::parse($leave->date_to) : null;
                    $createdAt = $leave->created_at ? Carbon::parse($leave->created_at) : now();

                    return [
                        'type' => 'leave',
                        'icon' => 'fas fa-calendar-times',
                        'title' => 'Leave Request',
                        'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                        'time' => $createdAt,
                        'time_human' => $createdAt->diffForHumans(),
                        'status' => $leave->status ?? 'pending',
                        'is_read' => $leave->is_read ?? false,
                        'details' => [
                            'Employee' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                            'Department' => $leave->employee->department->name ?? 'N/A',
                            'Leave Type' => $leave->type->name ?? 'N/A',
                            'Date From' => $dateFrom ? $dateFrom->format('M d, Y h:i A') : 'N/A',
                            'Date To' => $dateTo ? $dateTo->format('M d, Y h:i A') : 'N/A',
                            'Duration' => ($dateFrom && $dateTo) ? 
                                $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s) ' . 
                                $leave->diffhours['minutes'] . ' minute(s)' : 'N/A',
                            'Reason' => $leave->reason_to_leave ?? 'No reason provided',
                            'Payment Status' => $leave->payment_status ?? 'N/A',
                            'Status' => ucfirst($leave->status ?? 'pending'),
                            'Approved By' => $leave->approvedByUser ? 
                                $leave->approvedByUser->name : 'Not yet approved',
                            'Reference Number' => $leave->id ?? 'N/A',
                            'Applied On' => $createdAt->format('M d, Y h:i A')
                        ]
                    ];
                });

            $advances = $advancesQuery->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($advance) {
                    $createdAt = $advance->created_at ? Carbon::parse($advance->created_at) : now();
                    $remainingBalance = $advance->remainingBalance();

                    return [
                        'type' => 'cash_advance',
                        'icon' => 'fas fa-money-bill-wave',
                        'title' => 'Cash Advance Request',
                        'text' => "{$advance->employee->first_name} {$advance->employee->last_name} requested cash advance",
                        'time' => $createdAt,
                        'time_human' => $createdAt->diffForHumans(),
                        'status' => $advance->status ?? 'pending',
                        'is_read' => $advance->is_read ?? false,
                        'details' => [
                            'Employee' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                            'Department' => $advance->employee->department->name ?? 'N/A',
                            'Reference Number' => $advance->reference_number ?? 'N/A',
                            'Cash Advance Amount' => '₱ ' . number_format($advance->cash_advance_amount ?? 0, 2),
                            'Repayment Term' => $advance->repayment_term . ' month(s)',
                            'Monthly Amortization' => '₱ ' . number_format($advance->monthly_amortization ?? 0, 2),
                            'Total Repayment' => '₱ ' . number_format($advance->total_repayment ?? 0, 2),
                            'Remaining Balance' => '₱ ' . number_format($remainingBalance, 2),
                            'Status' => ucfirst($advance->status ?? 'pending'),
                            'Applied On' => $createdAt->format('M d, Y h:i A'),
                            'Total Payments Made' => $advance->payments->count(),
                            'Last Payment Date' => $advance->payments->last() ? 
                                Carbon::parse($advance->payments->last()->payment_date)->format('M d, Y') : 'No payments yet'
                        ]
                    ];
                });

            // Merge and sort notifications
            $allNotifications = $leaves->concat($advances)
                ->filter(function ($notification) {
                    return !is_null($notification['time']);
                })
                ->sortByDesc('time')
                ->groupBy(function($notification) {
                    return $notification['time']->format('Y-m-d');
                });

            return view('notifications.all', compact('allNotifications'));
        } catch (\Exception $e) {
            Log::error('Error in showAllNotifications: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load notifications. Please try again later.');
        }
    }
}