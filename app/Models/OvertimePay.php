<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use App\Models\User;

class OvertimePay extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'date',
        'overtime_hours',
        'overtime_rate',
        'overtime_pay',
        'approval_status',
        'approved_by',
        'approved_at',
        'is_read',
        'read_at',
        'is_view',
        'view_at',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'datetime',
        'approved_at' => 'datetime',
        'read_at' => 'datetime',
        'view_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who approved this overtime pay
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

/**
 * Calculate the overtime pay based on overtime hours and overtime rate.
 *
 * @return float
 */
public function calculateOvertimePay(): float
{
    // Retrieve the employee's salary based on the employee_id from the current instance
    $employee = Employee::find($this->employee_id);

    if (!$employee) {
        throw new \Exception('Employee not found');
    }

    $dailySalary = $employee->salary / 26; // Daily salary calculation
    $overtimePayRate = $dailySalary / 8; // Hourly rate

    // Calculate the overtime pay for this record
    $overtimePay = $overtimePayRate * $this->overtime_hours * $this->overtime_rate;

    // Update the overtime_pay field
    $this->overtime_pay = $overtimePay;
    $this->save();

    return $overtimePay;
}

/**
 * Approve the overtime pay record
 *
 * @param int $userId The ID of the user who is approving the overtime
 * @return bool
 */
public function approve(int $userId): bool
{
    $this->approval_status = 'approved';
    $this->approved_by = $userId;
    $this->approved_at = now();
    return $this->save();
}

/**
 * Reject the overtime pay record
 *
 * @param int $userId The ID of the user who is rejecting the overtime
 * @return bool
 */
public function reject(int $userId): bool
{
    $this->approval_status = 'rejected';
    $this->approved_by = $userId;
    $this->approved_at = now();
    return $this->save();
}

/**
 * Get total overtime pay for a specific employee within a date range
 *
 * @param int $employeeId
 * @param string $startDate
 * @param string $endDate
 * @return float
 */
public static function getTotalOvertimePay(int $employeeId, string $startDate, string $endDate): float
{
    return self::where('employee_id', $employeeId)
        ->whereBetween('approved_at', [$startDate, $endDate])
        ->where('approval_status', 'approved')
        ->get()
        ->sum(function ($record) {
            return $record->calculateOvertimePay();
        });
}

}