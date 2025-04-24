<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Leave;
use App\Models\CashAdvance;
use App\Models\OvertimePay;
use App\Models\NightPremium;
use App\Models\Notification;
use Carbon\Carbon;

class WebNotificationsController extends Controller
{
    /**
     * Get VAPID public key for web push
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVapidPublicKey()
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key')
        ]);
    }

    /**
     * Check if push notifications are enabled for the current user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNotificationStatus()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['enabled' => false]);
        }
        
        // Check if the user has any push subscriptions
        $enabled = $user->pushSubscriptions()->count() > 0;
        
        return response()->json([
            'enabled' => $enabled
        ]);
    }

    /**
     * Store a new push subscription for the authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePushSubscription(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        $this->validate($request, [
            'endpoint' => 'required|string|max:500',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string'
        ]);
        
        // Delete any existing subscriptions with this endpoint
        $user->pushSubscriptions()
            ->where('endpoint', $request->endpoint)
            ->delete();
        
        // Create a new subscription
        $subscription = $user->pushSubscriptions()->create([
            'endpoint' => $request->endpoint,
            'public_key' => $request->keys['p256dh'],
            'auth_token' => $request->keys['auth'],
            'content_encoding' => $request->contentEncoding ?? 'aes128gcm'
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Send a test push notification to the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testPushNotification(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        try {
            // Create a test notification payload
            $payload = [
                'toast' => [
                    'title' => 'Notifications Enabled!',
                    'message' => 'You will now receive real-time notifications.',
                    'icon' => 'fas fa-bell'
                ],
                'url' => route('notifications.all'),
                'timestamp' => now()->timestamp
            ];
            
            // Send notifications to all subscriptions of this user
            foreach ($user->pushSubscriptions as $subscription) {
                $this->sendWebPushNotification($subscription, $payload);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error sending test push notification: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check for notification updates in background
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkBackgroundUpdates(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            
            $timestamp = $request->input('timestamp', 0);
            
            // Get new notifications based on user role
            $newNotifications = $this->getNewNotificationsByUserRole($user, $timestamp);
            
            $hasUpdates = count($newNotifications) > 0;
            $newCount = count($newNotifications);
            
            // Format notifications for push delivery
            $formattedNotifications = [];
            foreach ($newNotifications as $notification) {
                $formattedNotifications[] = [
                    'id' => $notification['id'] ?? '',
                    'title' => $notification['title'] ?? 'New Notification',
                    'body' => $notification['text'] ?? '',
                    'icon' => $notification['icon'] ?? 'fas fa-bell',
                    'url' => $notification['url'] ?? '/notifications/all',
                    'type' => $notification['type'] ?? '',
                    'timestamp' => $notification['timestamp'] ?? time(),
                    'data' => $notification['data'] ?? []
                ];
            }
            
            return response()->json([
                'has_updates' => $hasUpdates,
                'new_count' => $newCount,
                'notifications' => $formattedNotifications,
                'timestamp' => time()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in checkBackgroundUpdates: ' . $e->getMessage());
            return response()->json([
                'has_updates' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get new notifications based on user role
     * 
     * @param User $user
     * @param int $timestamp
     * @return array
     */
    private function getNewNotificationsByUserRole($user, $timestamp)
    {
        $notifications = [];
        $since = Carbon::createFromTimestamp($timestamp);
        
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            // Get leave requests
            $leaves = Leave::with('employee')
                ->where('status', 'pending')
                ->where('created_at', '>', $since)
                ->orderBy('created_at', 'desc')
                ->get();
                
            foreach ($leaves as $leave) {
                $notifications[] = [
                    'id' => 'leave_' . $leave->id,
                    'title' => 'New Leave Request',
                    'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                    'icon' => 'fas fa-calendar-times',
                    'timestamp' => $leave->created_at->timestamp,
                    'url' => route('leave.index'),
                    'type' => 'leave_request',
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
            
            // Get cash advance requests
            $advances = CashAdvance::with('employee')
                ->where('status', 'pending')
                ->where('created_at', '>', $since)
                ->orderBy('created_at', 'desc')
                ->get();
                
            foreach ($advances as $advance) {
                $notifications[] = [
                    'id' => 'cash_advance_' . $advance->id,
                    'title' => 'New Cash Advance Request',
                    'text' => "{$advance->employee->first_name} {$advance->employee->last_name} requested cash advance",
                    'icon' => 'fas fa-money-bill-wave',
                    'timestamp' => $advance->created_at->timestamp,
                    'url' => route('cashadvance.index'),
                    'type' => 'cash_advance_request',
                    'data' => [
                        'id' => $advance->id,
                        'type' => 'cash_advance',
                        'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                        'amount' => $advance->amount,
                        'reason' => $advance->reason
                    ]
                ];
            }
        } elseif ($user->hasRole('Supervisor')) {
            // Get leave requests from their department
            $supervisorDepartmentId = $user->department_id;
            
            $leaves = Leave::with('employee')
                ->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId);
                })
                ->where('status', 'pending')
                ->where('created_at', '>', $since)
                ->orderBy('created_at', 'desc')
                ->get();
                
            foreach ($leaves as $leave) {
                $notifications[] = [
                    'id' => 'leave_' . $leave->id,
                    'title' => 'New Leave Request',
                    'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                    'icon' => 'fas fa-calendar-times',
                    'timestamp' => $leave->created_at->timestamp,
                    'url' => route('leave.index'),
                    'type' => 'leave_request',
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
        } elseif ($user->hasRole('Employee')) {
            // Employee notifications - get leave status updates
            $leaves = Leave::with(['employee', 'approvedByUser'])
                ->whereIn('status', ['approved', 'rejected'])
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->where('updated_at', '>', $since)
                ->orderBy('updated_at', 'desc')
                ->get();
                
            foreach ($leaves as $leave) {
                $isApproved = $leave->status === 'approved';
                $notifications[] = [
                    'id' => 'leave_' . ($isApproved ? 'approved_' : 'rejected_') . $leave->id,
                    'title' => 'Leave Request ' . ($isApproved ? 'Approved' : 'Rejected'),
                    'text' => "Your leave request has been " . ($isApproved ? 'approved' : 'rejected'),
                    'icon' => $isApproved ? 'fas fa-check-circle' : 'fas fa-times-circle',
                    'timestamp' => $leave->updated_at->timestamp,
                    'url' => route('leave.index'),
                    'type' => 'leave_' . ($isApproved ? 'approved' : 'rejected'),
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_' . ($isApproved ? 'approved' : 'rejected'),
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason,
                        'status' => $leave->status
                    ]
                ];
            }
            
            // Employee notifications - get cash advance status updates
            $advances = CashAdvance::with(['employee', 'approvedByUser', 'rejectedByUser'])
                ->whereIn('status', ['active', 'declined'])
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->where('updated_at', '>', $since)
                ->orderBy('updated_at', 'desc')
                ->get();
                
            foreach ($advances as $advance) {
                $isApproved = $advance->status === 'active';
                $notifications[] = [
                    'id' => 'cash_advance_' . ($isApproved ? 'active_' : 'declined_') . $advance->id,
                    'title' => 'Cash Advance ' . ($isApproved ? 'Approved' : 'Rejected'),
                    'text' => "Your cash advance request has been " . ($isApproved ? 'approved' : 'rejected'),
                    'icon' => $isApproved ? 'fas fa-check-circle' : 'fas fa-times-circle',
                    'timestamp' => $advance->updated_at->timestamp,
                    'url' => route('cashadvance.index'),
                    'type' => 'cash_advance_' . ($isApproved ? 'approved' : 'rejected'),
                    'data' => [
                        'id' => $advance->id,
                        'type' => 'cash_advance_' . ($isApproved ? 'approved' : 'rejected'),
                        'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                        'amount' => $advance->amount,
                        'reason' => $advance->reason,
                        'status' => $advance->status
                    ]
                ];
            }
        }
        
        return $notifications;
    }

    /**
     * Mark a notification as read from the background
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsReadFromBackground(Request $request)
    {
        try {
            $notificationId = $request->input('id');
            $notificationType = $request->input('type');
            
            // Extract the actual ID
            $parts = explode('_', $notificationId);
            $id = end($parts);
            
            if (strpos($notificationType, 'leave') !== false) {
                $leave = Leave::find($id);
                if ($leave) {
                    $leave->is_read = true;
                    if (strpos($notificationType, 'approved') !== false || 
                        strpos($notificationType, 'rejected') !== false) {
                        $leave->is_view = true;
                    }
                    $leave->save();
                }
            } elseif (strpos($notificationType, 'cash_advance') !== false) {
                $advance = CashAdvance::find($id);
                if ($advance) {
                    $advance->is_read = true;
                    if (strpos($notificationType, 'active') !== false || 
                        strpos($notificationType, 'declined') !== false) {
                        $advance->is_view = true;
                    }
                    $advance->save();
                }
            } elseif (strpos($notificationType, 'overtime') !== false) {
                $overtime = OvertimePay::find($id);
                if ($overtime) {
                    $overtime->is_read = true;
                    $overtime->read_at = now();
                    if (strpos($notificationType, 'approved') !== false || 
                        strpos($notificationType, 'rejected') !== false) {
                        $overtime->is_view = true;
                        $overtime->view_at = now();
                    }
                    $overtime->save();
                }
            } elseif (strpos($notificationType, 'night_premium') !== false) {
                $nightPremium = NightPremium::find($id);
                if ($nightPremium) {
                    // Determine which read flag to set based on user role
                    $user = Auth::user();
                    
                    if ($user->hasRole('Supervisor')) {
                        $nightPremium->is_read_by_supervisor = true;
                        $nightPremium->is_read_at_supervisor = now();
                    } elseif ($user->hasRole('Finance')) {
                        $nightPremium->is_read_by_finance = true;
                        $nightPremium->is_read_at_finance = now();
                    } elseif ($user->hasRole('Employee')) {
                        $nightPremium->is_read_by_employee = true;
                        $nightPremium->is_read_at_employee = now();
                    }
                    
                    // Handle approved/rejected status
                    if (strpos($notificationType, 'approved') !== false || 
                        strpos($notificationType, 'rejected') !== false) {
                        $nightPremium->is_read_by_employee = true;
                        $nightPremium->is_read_at_employee = now();
                    }
                    
                    $nightPremium->save();
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read from background: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Send notifications to users when app is in background
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendBackgroundNotification(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $notification = $request->input('notification');
            
            if (!$userId || !$notification) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }
            
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            // Broadcast the notification using Pusher
            event(new \App\Events\NotificationEvent($userId, $notification));
            
            // Send push notification to user for background notifications
            foreach ($user->pushSubscriptions as $subscription) {
                $this->sendWebPushNotification($subscription, $notification);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error sending background notification: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Send a web push notification to a specific subscription
     *
     * @param  object  $subscription
     * @param  array  $payload
     * @return void
     */
    private function sendWebPushNotification($subscription, $payload)
    {
        try {
            $webPush = new \Minishlink\WebPush\WebPush([
                'VAPID' => [
                    'subject' => config('app.url'),
                    'publicKey' => config('webpush.vapid.public_key'),
                    'privateKey' => config('webpush.vapid.private_key')
                ]
            ]);

            $webPush->sendNotification(
                $subscription->endpoint,
                json_encode($payload),
                $subscription->public_key,
                $subscription->auth_token,
                true
            );
        } catch (\Exception $e) {
            Log::error('Error sending web push notification: ' . $e->getMessage());
            
            // Check if the subscription is invalid
            if (strpos($e->getMessage(), '410 Gone') !== false) {
                // Delete invalid subscription
                $subscription->delete();
            }
        }
    }
}
