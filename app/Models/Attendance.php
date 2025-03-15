<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory, Loggable;

    protected const REMARKS = [
        'SUNDAY' => 'Sunday',
        'SATURDAY' => 'Saturday',
        'HOLIDAY' => 'Holiday',
        'ON_LEAVE' => 'On Leave',
        'ABSENT' => 'Absent',
        'LATE' => 'Late',
        'UNDERTIME' => 'UnderTime',
        'OVERTIME' => 'Overtime',
        'PRESENT' => 'Present',
        'NO_CLOCK_OUT' => 'No Clock Out',
        'HALF_DAY' => 'Half Day'
    ];

    public static function getRemarks()
    {
        return self::REMARKS;
    }

    protected $fillable = [
        'employee_id',
        'date_attended',
        'time_in',
        'time_out',
        'time_stamp1',
        'time_stamp2',
        'time_in_address',
        'time_out_address',
        'remarks',
        'hours_worked',
        'leave_payment_status',
        'overtime_hours',
    ];

    protected $casts = [
        'date_attended' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'hours_worked' => 'float',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function calculateRemarksAndHoursWorked()
    {
        $dateAttended = Carbon::parse($this->date_attended);
        $dayOfWeek = $dateAttended->dayOfWeek;
        $isHoliday = Holiday::whereDate('date', $dateAttended)->exists();
        $leave = Leave::where('employee_id', $this->employee_id)
                      ->whereDate('date_from', '<=', $dateAttended)
                      ->whereDate('date_to', '>', $dateAttended)
                      ->where('status', 'approved')
                      ->first();

        // Check for Sunday
        if ($dayOfWeek == Carbon::SUNDAY) {
            $this->setNoWorkDay('Sunday');
            return;
        }

        if ($dayOfWeek == Carbon::SATURDAY) {
            // Get employee's employment status and calculate days since hiring
            $employmentStatus = $this->employee->employment_status ?? null;
            $daysEmployed = Carbon::parse($this->employee->date_hired)->diffInDays(Carbon::now());

            // For non-regular employees with 30 or more days of employment
            if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed >= 30) {
                $this->time_in = '08:00:00';
                $this->time_out = '17:00:00';
                $this->remarks = 'Saturday';
                $this->hours_worked = '08:00:00';
                $this->leave_payment_status = 'With Pay';
                return;
            }
            // For non-regular employees with less than 30 days of employment
            else if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed < 30) {
                $this->time_in = null;
                $this->time_out = null;
                $this->remarks = 'Saturday';
                $this->hours_worked = '00:00:00';
                $this->leave_payment_status = 'Without Pay';
                return;
            }

            // For regular employees
            $this->time_in = '08:00:00';
            $this->time_out = '17:00:00';
            $this->remarks = 'Saturday';
            $this->hours_worked = '08:00:00';
            $this->leave_payment_status = 'With Pay';
            return;
        }

        if ($isHoliday) {
            $holiday = Holiday::whereDate('date', $dateAttended)->first();
            if ($holiday->type !== 'Regular Holiday' && $holiday->type !== 'Special Non-Working Holiday') {
                $this->setAbsentForNonRegularHoliday();
                return;
            }
            $this->setHolidayAttendance();
            return;
        }

        if ($leave) {
            $this->setLeaveAttendance($leave);
            return;
        }

        // Regular work day
        $this->setRegularWorkDay();
    }

    private function setNoWorkDay($reason)
    {
        $this->time_in = $this->time_in ?? null;
        $this->time_out = $this->time_out ?? null;
        $this->remarks = ($this->time_in !== null && $this->time_out !== null) ? 'Overtime' : $reason;
        $this->hours_worked = $this->hours_worked ?? '00:00:00';
        $this->leave_payment_status = $this->leave_payment_status ?? null;
        $this->overtime_hours = $this->hours_worked ?? '00:00:00';
    }

    private function setHolidayAttendance()
    {
        // Get employee's employment status and calculate days since hiring
        $employmentStatus = $this->employee->employment_status ?? null;
        $daysEmployed = Carbon::parse($this->employee->date_hired)->diffInDays(Carbon::now());

        // For non-regular employees with 30 or more days of employment
        if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed >= 30) {
            $this->time_in = '08:00:00';
            $this->time_out = '17:00:00';
            $this->remarks = 'Holiday';
            $this->hours_worked = '08:00:00';
            $this->leave_payment_status = 'With Pay';
            return;
        }
        // For non-regular employees with less than 30 days of employment
        else if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed < 30) {
            $this->time_in = null;
            $this->time_out = null;
            $this->remarks = 'Holiday';
            $this->hours_worked = '00:00:00';
            $this->leave_payment_status = 'Without Pay';
            return;
        }

        // For regular employees (detailed setup)
        $this->time_in = '08:00:00';      // Standard start time
        $this->time_out = '17:00:00';     // Standard end time
        $this->remarks = 'Holiday';        // Mark as holiday
        $this->hours_worked = '08:00:00';  // Full day of work credited
        $this->leave_payment_status = 'With Pay';  // Regular employees always get paid holidays
    }

    private function setLeaveAttendance($leave)
    {
        $leavePaymentStatus = $leave->getLeavePaymentStatus();

        if ($leavePaymentStatus === 'With Pay') {
            $this->time_in = '08:00:00';
            $this->time_out = '17:00:00';
            $this->hours_worked = '08:00:00';
        } else {
            $this->time_in = null;
            $this->time_out = null;
            $this->hours_worked = '00:00:00';
        }

        $this->remarks = 'On Leave';
        $this->leave_payment_status = $leavePaymentStatus;
    }

    private function setRegularWorkDay()
    {
        $shiftStart = Carbon::parse('08:00:00');
        $shiftEnd = Carbon::parse('17:00:00');
        $halfDayTime = Carbon::parse('12:00:00');

        $timeIn = $this->time_in ? Carbon::parse($this->time_in) : null;
        $timeOut = $this->time_out ? Carbon::parse($this->time_out) : null;

        if (!$timeIn && !$timeOut) {
            $this->setNoWorkDay('Absent');
            return;
        }

        // Check for Half Day condition
        if ($timeIn && $timeOut && 
            $timeIn->format('H:i:s') === '08:00:00' && 
            $timeOut->format('H:i:s') === '12:00:00') {
            $this->remarks = 'Half Day';
            $this->hours_worked = '04:00:00';
            $this->leave_payment_status = 'With Pay';
            return;
        }

        if ($timeIn && $timeIn->gt($shiftStart)) {
            $this->remarks = 'Late';
        } elseif ($timeOut && $timeOut->lt($shiftEnd)) {
            $this->remarks = 'UnderTime';
        } elseif ($this->isOvertime()) {
            $this->remarks = 'Overtime';
            $this->overtime_hours = $this->calculateOvertimeHours();
        } else {
            $this->remarks = 'Present';
        }

        if (!$timeOut) {
            $this->remarks = 'No Clock Out';
        }

        $this->hours_worked = $this->getHoursWorkedAttribute();
        $this->leave_payment_status = 'With Pay'; // Assuming regular work days are always with pay
    }

    public function isOvertime(): bool
    {
        $shiftEnd = Carbon::parse('18:00:00'); // 06:00 PM
        $timeOut = $this->time_out ? Carbon::parse($this->time_out) : null;

        return $timeOut && $timeOut->gte($shiftEnd);
    }

    public function getHoursWorkedAttribute(): string
    {
        if (is_null($this->time_in) || is_null($this->time_out)) {
            return '00:00:00';
        }

        $shiftStart = Carbon::parse('08:00:00');
        $timeIn = Carbon::parse($this->time_in);
        $timeOut = Carbon::parse($this->time_out);

        if ($timeIn->lt($shiftStart)) {
            $timeIn = $shiftStart;
        }

        if ($timeOut->greaterThanOrEqualTo(Carbon::createFromTime(13, 0))) {
            $timeOut = $timeOut->subHours(1);
        }

        $hoursWorked = $timeIn->diff($timeOut);

        return $hoursWorked->format('%H:%I:%S');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            // Create overtime pay record if remarks is Overtime
            if ($model->remarks === 'Overtime' && $model->overtime_hours) {
                // Convert overtime hours from HH:MM:SS to decimal hours
                $overtimeHoursArray = explode(':', $model->overtime_hours);
                $decimalHours = $overtimeHoursArray[0] + ($overtimeHoursArray[1] / 60) + ($overtimeHoursArray[2] / 3600);

                OvertimePay::updateOrCreate(
                    [
                        'employee_id' => $model->employee_id,
                        'date' => $model->date_attended,
                    ],
                    [
                        'overtime_hours' => $decimalHours,
                        'overtime_rate' => 1.25, // Default overtime rate, adjust as needed
                    ]
                );
            }
        });

        static::saving(function ($model) {
            $model->calculateRemarksAndHoursWorked();
        });
    }

    private function calculateHoursWorked($timeIn, $timeOut)
    {
        if ($timeIn && $timeOut) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $timeIn);
            $end = \Carbon\Carbon::createFromFormat('H:i', $timeOut);
            return $end->diffInHours($start);
        }
        return null;
    }

    public function getAttendancePoints(): float
    {
        // Assuming 'PRESENT' status gets 0.5 points
        if ($this->remarks === 'Present' && $this->leave_payment_status === 'With Pay') {
            return 0.5;
        }
        return 0;
    }

    public function getOvertimeDifferenceInDecimal(): float
    {
        if ($this->remarks === 'Overtime' && $this->time_out) {
            $standardTimeOut = Carbon::parse('17:00:00');
            $actualTimeOut = Carbon::parse($this->time_out);

            // Calculate the difference
            $overtimeDuration = $actualTimeOut->diff($standardTimeOut);

            // Convert the difference to decimal format
            $hours = $overtimeDuration->h;
            $minutes = $overtimeDuration->i;

            return $hours + ($minutes / 60);
        }

        return 0.0;
    }

    private function setAbsentForNonRegularHoliday()
    {
        $this->time_in = null;
        $this->time_out = null;
        $this->remarks = 'Absent';
        $this->hours_worked = '00:00:00';
        $this->leave_payment_status = null;
    }

    public function calculateOvertimeHours(): string
    {
        // Only calculate if remarks is Overtime and time_out exists
        if ($this->remarks !== 'Overtime' || !$this->time_out) {
            return '00:00:00';
        }

        // Set overtime start time to 17:00:00 (5:00 PM)
        $overtimeStart = Carbon::parse('17:00:00');
        $timeOut = Carbon::parse($this->time_out);

        // If time_out is before overtime start, return 0
        if ($timeOut->lte($overtimeStart)) {
            return '00:00:00';
        }

        // Calculate the difference between overtime start and time_out
        $overtimeDuration = $overtimeStart->diff($timeOut);

        // Format the duration as HH:MM:SS
        return $overtimeDuration->format('%H:%I:%S');
    }

    public function calculateLateTime(): string
    {
        if ($this->remarks === 'Late' && $this->time_in) {
            $shiftStart = Carbon::parse('08:00:00');
            $timeIn = Carbon::parse($this->time_in);

            // Calculate the difference in minutes
            $lateDuration = $timeIn->diffInMinutes($shiftStart);

            // Format the late duration as HH:MM
            return sprintf('%02d:%02d', floor($lateDuration / 60), $lateDuration % 60);
        }

        return '00:00'; // No late time if not late
    }
}
