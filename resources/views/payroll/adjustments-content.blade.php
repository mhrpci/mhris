

<!-- Company header with fixed position -->
<div class="payroll-header py-3 bg-light border-bottom sticky-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h5 class="font-weight-bold mb-0">MEDICAL & HOSPITAL RESOURCES HEALTH CARE, INC.</h5>
                <p class="mb-0">PAYROLL <span class="payroll-year">{{ date('Y') }}</span></p>
                <div class="row justify-content-center mb-2">
                    <div class="col-md-3 col-sm-4">
                        <p class="mb-0"><small>Period Cov: <span class="period-cov font-weight-bold"></span></small></p>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <p class="mb-0"><small>Payroll: <span class="payroll-date font-weight-bold"></span></small></p>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <p class="mb-0"><small>Pay-out: <span class="pay-out-date font-weight-bold"></span></small></p>
                    </div>
                </div>
                
                <!-- Search and filter controls -->
                <div class="row mt-2">
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" id="adjustmentSearch" placeholder="Search employee...">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <select class="form-control form-control-sm" id="departmentFilter">
                            <option value="">All Departments</option>
                            @foreach($departments as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="toggleAllDepartments">
                            <i class="fas fa-compress-alt"></i> <span>Collapse All</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table with fixed header -->
<div class="table-container">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped" id="adjustmentTable">
            <thead class="thead-light sticky-header">
                <tr>
                    <th colspan="2" class="text-center position-relative">
                        Employee
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Employee information"></i>
                    </th>
                    <th rowspan="2" class="position-relative">
                        Position
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Employee position"></i>
                    </th>
                    <th rowspan="2" class="position-relative">
                        Department
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Employee department"></i>
                    </th>
                    <th rowspan="2" class="position-relative">
                        Monthly Rate
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Base monthly salary"></i>
                    </th>
                    <th rowspan="2">Daily Rate</th>
                    <th rowspan="2" class="position-relative">
                        L/UT/LWOP
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Leave without pay/late undertime"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Adjustments
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Salary adjustments"></i>
                    </th>
                    <th colspan="4" class="text-center position-relative">
                        In hours
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Overtime and holiday hours"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Allowances
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Employee allowances"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Other Adjustments
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Additional adjustments"></i>
                    </th>
                    <th rowspan="2">SSS EE</th>
                    <th rowspan="2">SSS ER</th>
                    <th rowspan="2">HDMF EE</th>
                    <th rowspan="2">PHIC EE</th>
                    <th rowspan="2">SSS Loan</th>
                    <th rowspan="2">HDMF Loan</th>
                    <th rowspan="2">Cash Advance Salary</th>
                    <th rowspan="2" class="editable-column position-relative">
                        Cash Bond
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Cash bond deductions"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Other Deduction
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Additional deductions"></i>
                    </th>
                    <th rowspan="2">TAX</th>
                    <th rowspan="2">Net Salary</th>
                </tr>
                <tr>
                    <th class="position-relative">
                        Number
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Last 4 digits of employee company ID"></i>
                    </th>
                    <th>Name</th>
                    <th class="position-relative">
                        OT Hours
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Overtime hours worked"></i>
                    </th>
                    <th>LH</th>
                    <th>SH</th>
                    <th>NP</th>
                </tr>
            </thead>
            <tbody>
                @if($payrollsByDepartment->isEmpty())
                    <tr>
                        <td colspan="24" class="text-center">No payroll records found for the selected date range.</td>
                    </tr>
                @else
                    @foreach($payrollsByDepartment as $department => $payrolls)
                        <tr class="bg-light department-header" data-department="{{ $department }}">
                            <td colspan="24" class="font-weight-bold">
                                <i class="fas fa-chevron-down mr-2 toggle-department"></i>
                                {{ $departments[$department] ?? strtoupper($department) }}
                            </td>
                        </tr>
                        @foreach($payrolls as $payroll)
                            <tr class="employee-row" data-department="{{ $department }}" data-search="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }} {{ $payroll->employee->position->name ?? $payroll->employee->position }}" data-payroll-id="{{ $payroll->id }}">
                                <td>
                                    {{ substr($payroll->employee->company_id, -4) }}
                                </td>
                                <td>{{ $payroll->employee->last_name }}, {{ $payroll->employee->first_name }}</td>
                                <td>{{ $payroll->employee->position->name ?? $payroll->employee->position }}</td>
                                <td>{{ $payroll->employee->department->name ?? 'N/A' }}</td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->employee->salary, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->employee->salary / 26, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="text" 
                                        class="form-control form-control-sm"
                                        readonly
                                        value="@php
                                            // Convert both times to minutes
                                            list($lateHours, $lateMinutes) = array_map('intval', explode(':', $payroll->late_time));
                                            list($underHours, $underMinutes) = array_map('intval', explode(':', $payroll->under_time));
                                            
                                            // Get unpaid leave hours (stored as decimal) and convert to minutes
                                            $unpaidLeaveMinutes = ($payroll->unpaid_leave_hours ?? 0) * 60;
                                            
                                            // Calculate total minutes
                                            $totalMinutes = ($lateHours * 60 + $lateMinutes) + ($underHours * 60 + $underMinutes) + $unpaidLeaveMinutes;
                                            
                                            // Convert back to HH:MM format
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                            
                                            echo sprintf('%02d:%02d', $hours, $minutes);
                                        @endphp">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm adjustment-field numeric-input {{ isset($payroll->adjustments) || $payroll->adjustments === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->adjustments) || $payroll->adjustments === 0.0 ? number_format($payroll->adjustments, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->adjustments) || $payroll->adjustments === 0.0 ? number_format($payroll->adjustments, 2, '.', '') : '' }}">
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="text" 
                                            class="form-control form-control-sm text-right" 
                                            readonly 
                                            value="@php
                                                if(isset($payroll->overtime_hours)) {
                                                    $hours = floor($payroll->overtime_hours);
                                                    $minutes = round(($payroll->overtime_hours - $hours) * 60);
                                                    // Handle case where minutes round up to 60
                                                    if($minutes == 60) {
                                                        $hours++;
                                                        $minutes = 0;
                                                    }
                                                    echo sprintf('%d:%02d', $hours, $minutes);
                                                } else {
                                                    echo '0:00';
                                                }
                                            @endphp"
                                            style="min-width: 100px;"
                                            data-toggle="tooltip" 
                                            title="Hours (decimal): {{ isset($payroll->overtime_hours) ? number_format($payroll->overtime_hours, 2, '.', ',') : '0.00' }} | Overtime Pay: ₱{{ isset($payroll->overtime_pay) ? number_format($payroll->overtime_pay, 2, '.', ',') : '0.00' }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control form-control-sm numeric-input text-right" 
                                            readonly 
                                            min="0"
                                            step="0.01"
                                            data-type="currency"
                                            value="{{isset($payroll->regular_holiday_hours) ? number_format($payroll->regular_holiday_hours, 2, '.', '') : '0.00' }}"
                                            style="min-width: 100px;">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control form-control-sm numeric-input text-right" 
                                            readonly 
                                            min="0"
                                            step="0.01"
                                            data-type="currency"
                                            value="{{isset($payroll->special_holiday_hours) ? number_format($payroll->special_holiday_hours, 2, '.', '') : '0.00' }}"
                                            style="min-width: 100px;">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control form-control-sm numeric-input text-right" 
                                            readonly 
                                            min="0"
                                            step="0.01"
                                            data-type="currency"
                                            value="{{isset($payroll->night_premium_hours) ? number_format($payroll->night_premium_hours, 2, '.', '') : '0.00' }}"
                                            style="min-width: 100px;">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm allowance-field numeric-input {{ isset($payroll->allowances) || $payroll->allowances === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->allowances) || $payroll->allowances === 0.0 ? number_format($payroll->allowances, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->allowances) || $payroll->allowances === 0.0 ? number_format($payroll->allowances, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm other-adj-field numeric-input {{ isset($payroll->other_adjustments) || $payroll->other_adjustments === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->other_adjustments) || $payroll->other_adjustments === 0.0 ? number_format($payroll->other_adjustments, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->other_adjustments) || $payroll->other_adjustments === 0.0 ? number_format($payroll->other_adjustments, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->sss_contribution, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ $payroll->employer_sss_contribution, 2 }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->pagibig_contribution, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->philhealth_contribution, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->sss_loan, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->pagibig_loan, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->cash_advance, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm cash-bond-field numeric-input {{ isset($payroll->cash_bond) || $payroll->cash_bond === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->cash_bond) || $payroll->cash_bond === 0.0 ? number_format($payroll->cash_bond, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->cash_bond) || $payroll->cash_bond === 0.0 ? number_format($payroll->cash_bond, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm other-deduct-field numeric-input {{ isset($payroll->other_deduction) || $payroll->other_deduction === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->other_deduction) || $payroll->other_deduction === 0.0 ? number_format($payroll->other_deduction, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->other_deduction) || $payroll->other_deduction === 0.0 ? number_format($payroll->other_deduction, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="0.00">
                                </td>
                                <td class="text-right font-weight-bold net-salary-value">{{ number_format($payroll->net_salary, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div> 

<style>
/* Add styling for existing and new values */
.existing-value {
    background-color: #e8f4f8;
    border-color: #17a2b8;
}

.new-value {
    background-color: #fff8e6;
    border-color: #ffc107;
}

.field-info {
    font-size: 0.7rem;
    display: block;
    margin-top: -3px;
    text-align: right;
    font-style: italic;
}

/* Field focus effects */
.existing-value:focus {
    background-color: #d1ebf1;
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.new-value:focus {
    background-color: #fff3cd;
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

/* Numeric input styling */
.numeric-input {
    text-align: right;
    font-variant-numeric: tabular-nums;
    font-family: 'Courier New', monospace;
    padding-right: 5px;
}

.numeric-input::-webkit-inner-spin-button,
.numeric-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.numeric-input[readonly] {
    background-color: #f8f9fa;
    cursor: default;
}

/* Highlight changes in net salary */
.text-success {
    font-weight: bold !important;
}

.text-danger {
    font-weight: bold !important;
}

.change-indicator {
    font-size: 0.8rem;
    font-style: italic;
}

/* Instructions panel */
.adjustment-instructions {
    background-color: #f8f9fa;
    border-left: 4px solid #17a2b8;
    padding: 10px 15px;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.adjustment-instructions ul {
    padding-left: 20px;
    margin-bottom: 0;
}

.adjustment-instructions li {
    margin-bottom: 5px;
}
</style>

<script>
// Format numbers to always display with 2 decimal places
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Handle input changes for numeric fields
    document.querySelectorAll('.numeric-input').forEach(function(input) {
        input.addEventListener('focus', function() {
            // Remove any non-numeric formatting when focused
            let value = this.value.replace(/[^\d.-]/g, '');
            if (value) {
                this.value = parseFloat(value).toFixed(2);
            }
        });
        
        input.addEventListener('blur', function() {
            // Format to 2 decimal places when losing focus
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
        
        // Handle invalid input
        input.addEventListener('input', function() {
            if (this.value !== '' && isNaN(parseFloat(this.value))) {
                this.value = parseFloat(this.dataset.original || 0).toFixed(2);
            }
        });
    });
});
</script> 