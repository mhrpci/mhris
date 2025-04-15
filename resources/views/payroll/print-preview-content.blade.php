<div class="print-content">
    <!-- Print-specific styles -->
    <style type="text/css" media="print">
        @page {
            size: legal landscape;
            margin: 0.5in;
            scale: auto;
            counter-increment: page;
        }
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            counter-reset: page 1;
        }
        /* Hide all images when printing */
        img {
            display: none !important;
        }
        /* Don't hide department headers in print - allow them to show */
        .print-department-header {
            display: table-row !important;
            background-color: #f0f0f0 !important;
            font-weight: bold !important;
            page-break-before: always;
        }
        /* Hide only subtotal and grand total rows */
        .print-department-subtotal,
        .print-grand-total,
        tr[class*="total"],
        .all-departments-total {
            display: none !important;
        }
        .print-content {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }
        .payroll-print-table {
            width: 100%;
            font-size: 6pt;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
            page-break-inside: auto;
            transform-origin: top left;
            /* Ensure table scales to fit paper */
            max-width: 100%;
            transform: scale(1);
        }
        .payroll-print-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
            height: 22px; /* Shorter row height to match image */
        }
        .payroll-print-table th, .payroll-print-table td {
            border: 1px solid #000;
            padding: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .payroll-print-table th {
            background-color: #f2f2f2 !important;
            text-align: center !important;
            vertical-align: middle;
            font-weight: bold;
            font-size: 4pt;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
        }
        .print-department-header td {
            background-color: #eaeaea !important;
            font-weight: bold;
            text-transform: uppercase;
        }
        .print-department-subtotal {
            background-color: #f9f9f9 !important;
        }
        .print-grand-total {
            background-color: #efefef !important;
        }
        .print-company-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .print-company-header h4 {
            margin-bottom: 5px;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .print-company-header p {
            margin: 3px 0;
            font-size: 10pt;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        
        /* Ensure footer stays at bottom of page */
        .footer-section {
            position: fixed;
            bottom: 0.3in;
            left: 0.5in;
            right: 0.5in;
            font-size: 7pt;
        }
        
        /* Repeating table headers on each page */
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
        
        /* Hide non-printable elements */
        .d-print-none, 
        button, 
        .modal-header, 
        .print-controls {
            display: none !important;
        }
        
        /* Improve numeric column alignment */
        .text-right {
            text-align: right !important;
        }
        
        /* Ensure page breaks begin at each department */
        .print-department-section {
            page-break-inside: avoid;
        }
        
        /* Add department header styling to make each department clearly identifiable */
        .print-department-header td {
            font-weight: bold !important;
            text-transform: uppercase !important;
            background-color: #f0f0f0 !important;
            border-top: 2px solid #000 !important;
            border-bottom: 2px solid #000 !important;
            padding: 5px !important;
            font-size: 7pt !important;
            page-break-before: always !important;
            break-before: always !important;
            counter-reset: department-page 1;
        }

        /* Set department-specific colors for easier identification */
        .print-department-header[data-department="Support Personnel"] td {
            background-color: #e6f2ff !important;
        }
        
        .print-department-header[data-department="BGPDI"] td {
            background-color: #e6ffe6 !important;
        }
        
        .print-department-header[data-department="VHI"] td {
            background-color: #fff2e6 !important;
        }
        
        .print-department-header[data-department="Marketing Department"] td,
        .print-department-header[data-department="Technical Department"] td {
            background-color: #ffe6e6 !important;
        }
        
        /* High contrast for better printouts */
        .print-grand-total td,
        .print-department-subtotal td {
            font-weight: bold !important;
            border-top: 2px solid #000 !important;
        }
        
        /* Set specific columns to fit content - match the image exactly */
        .payroll-print-table th:nth-child(1), 
        .payroll-print-table td:nth-child(1) {
            width: 32px !important; /* employee number */
        }
        .payroll-print-table th:nth-child(2), 
        .payroll-print-table td:nth-child(2) {
            width: 150px !important; /* employee name */
            text-align: left;
        }
        .payroll-print-table th:nth-child(3), 
        .payroll-print-table td:nth-child(3) {
            width: 130px !important; /* position */
            text-align: left;
        }
        
        /* For all other columns, make them evenly spaced */
        .payroll-print-table th:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)), 
        .payroll-print-table td:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)) {
            width: 50px !important;
        }
        
        /* Control where page breaks occur */
        .page-break-after {
            page-break-after: always;
        }
        
        /* Print header that repeats on each page */
        .print-company-header {
            position: fixed;
            top: 0.3in;
            left: 0;
            right: 0;
            background-color: white !important;
            border-bottom: 1px solid #000;
            z-index: 100;
        }
        
        /* Add space for the company header */
        .payroll-print-table {
            margin-top: 1in;
        }
        
        /* Empty cell styling */
        .payroll-print-table td:empty {
            background-color: #f8f8f8 !important;
        }
        
        /* Ensure compact layout even with small data */
        .payroll-print-table td {
            max-height: 22px;
            height: 22px;
        }
        
        /* Set specific font size for table cells */
        .payroll-print-table td {
            font-size: 6pt !important; /* Force 6pt font size */
            min-font-size: 6pt !important; /* Ensure font doesn't go below 6pt */
            max-font-size: 6pt !important; /* Ensure font doesn't go above 6pt */
            line-height: 1.1 !important; /* Tighter line height for smaller font */
        }
    </style>
    
    <!-- Regular screen styles -->
    <style type="text/css" media="screen">
        .payroll-print-table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }
        .payroll-print-table th, .payroll-print-table td {
            border: 1px solid #ddd;
            padding: 4px;
        }
        .payroll-print-table td {
            font-size: 10px; /* Regular screen size */
        }
        .print-department-header td {
            background-color: #eaeaea;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Department-specific colors for screen view */
        .print-department-header[data-department="Support Personnel"] td {
            background-color: #e6f2ff;
        }
        
        .print-department-header[data-department="BGPDI"] td {
            background-color: #e6ffe6;
        }
        
        .print-department-header[data-department="VHI"] td {
            background-color: #fff2e6;
        }
        
        .print-department-header[data-department="Marketing Department"] td,
        .print-department-header[data-department="Technical Department"] td {
            background-color: #ffe6e6;
        }

        .print-department-subtotal {
            background-color: #f9f9fa;
        }
        .print-grand-total {
            background-color: #efefef;
        }
    </style>
    
    <!-- Company header -->
    <div class="print-company-header">
        <h4>MEDICAL & HOSPITAL RESOURCES HEALTH CARE, INC.</h4>
        <p>PAYROLL {{ $payrollYear }}</p>
        <p><small>Period Covered: <strong>{{ $periodStart }} - {{ $periodEnd }}</strong></small></p>
    </div>
    
    <!-- Payroll Table -->
    <table class="payroll-print-table table-bordered">
        <thead>
            <tr>
                <th>Employee Number</th>
                <th>Employee</th>
                <th>Position</th>
                <th>Semi-Monthly</th>
                <th>L/UT/LWOP</th>
                <th>Total BasicPay</th>
                <th>Overtime</th>
                <th>Holiday with Pay</th>
                <th>Night Premiums</th>
                <th>Allowance</th>
                <th>Other Adjustments</th>
                <th>Total Gross Pay</th>
                <th>SSS Prem EE Share</th>
                <th>SSS ER</th>
                <th>Pag-ibig Prem EE Share</th>
                <th>Pag-ibig ER</th>
                <th>PHIC Prem EE Share</th>
                <th>PHIC ER</th>
                <th>SSS Loan</th>
                <th>Pag-ibig Loan</th>
                <th>Cash Advance Salary</th>
                <th>Cash Bond</th>
                <th>Other Deduction</th>
                <th>W/Tax</th>
                <th>Total Ded.</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>
            @if($payrollsByDepartment->isEmpty())
                <tr>
                    <td colspan="26" class="text-center">No payroll records found for the selected date range.</td>
                </tr>
            @else
                @foreach($payrollsByDepartment as $department => $payrolls)
                    <tr class="print-department-header print-department-section" data-department="{{ $department }}">
                        <td colspan="26" class="text-left font-weight-bold">
                            @if($department == 'BGPDI')
                                BGPDI
                            @elseif($department == 'VHI')
                                VHI
                            @elseif($department == 'Marketing Department' || $department == 'Technical Department')
                                Marketing Department
                            @else
                                Support Personnel
                            @endif
                        </td>
                    </tr>
                    
                    @foreach($payrolls as $payroll)
                        <tr class="print-employee-row print-department-section" data-department="{{ $department }}">
                            <td class="text-center">{{ substr($payroll->employee->company_id, -4) }}</td>
                            <td>{{ $payroll->employee->last_name }}, {{ $payroll->employee->first_name }}</td>
                            <td>{{ $payroll->employee->position->name ?? $payroll->employee->position }}</td>
                            <td class="text-right">{{ number_format($payroll->employee->salary/2, 2) }}</td>
                            <td class="text-center">
                                @php
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
                                @endphp
                            </td>
                            <td class="text-right">
                                @php
                                    // Calculate total_basic_pay using the formula:
                                    // employee->salary/2 - (late,undertime,leave without pay deductions) + adjustments
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        // Calculate daily rate and then deduct based on hours
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Add adjustments if present
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    
                                    // Calculate total basic pay
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Store this value if needed for later calculations
                                    $payroll->total_basic_pay = $totalBasicPay;
                                    
                                    echo number_format($totalBasicPay, 2);
                                @endphp
                            </td>
                            <td class="text-center">
                                @php
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
                                        echo '-';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">
                                @php
                                    $regularHolidayHours = isset($payroll->regular_holiday_hours) ? $payroll->regular_holiday_hours : 0;
                                    $specialHolidayHours = isset($payroll->special_holiday_hours) ? $payroll->special_holiday_hours : 0;
                                    $totalHolidayHours = $regularHolidayHours + $specialHolidayHours;
                                    
                                    if ($totalHolidayHours > 0) {
                                        echo number_format($totalHolidayHours, 2);
                                    } else {
                                        echo '00:00';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">{{ isset($payroll->night_premium_hours) ? number_format($payroll->night_premium_hours, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->allowances) ? number_format($payroll->allowances, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->other_adjustments) ? number_format($payroll->other_adjustments, 2) : '-' }}</td>
                            <td class="text-right">
                                @php
                                    // Calculate total_gross_pay
                                    // Formula: total_basic_pay + overtime_pay + holiday_pay + night_premium_pay + allowances + other_adjustments
                                    
                                    // Get existing values or default to 0
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    $otherAdjustments = $payroll->other_adjustments ?? 0;
                                    
                                    // Get total_basic_pay from previous calculation or from model
                                    $totalBasicPay = $payroll->total_basic_pay ?? 0;
                                    
                                    // Calculate total gross pay
                                    $totalGrossPay = $totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances + $otherAdjustments;
                                    
                                    // Store this value if needed for later calculations
                                    $payroll->total_gross_pay = $totalGrossPay;
                                    
                                    echo number_format($totalGrossPay, 2);
                                @endphp
                            </td>
                            <td class="text-right">{{ isset($payroll->sss_contribution) ? number_format($payroll->sss_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->employer_sss_contribution) ? number_format($payroll->employer_sss_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->pagibig_contribution) ? number_format($payroll->pagibig_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->employer_pagibig_contribution) ? number_format($payroll->employer_pagibig_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->philhealth_contribution) ? number_format($payroll->philhealth_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->employer_philhealth_contribution) ? number_format($payroll->employer_philhealth_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->sss_loan) ? number_format($payroll->sss_loan, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->pagibig_loan) ? number_format($payroll->pagibig_loan, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->cash_advance) ? number_format($payroll->cash_advance, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->cash_bond) ? number_format($payroll->cash_bond, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->other_deduction) ? number_format($payroll->other_deduction, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->tax) ? number_format($payroll->tax, 2) : '00:00' }}</td>
                            <td class="text-right">
                                @php
                                    // Calculate total_deduction
                                    // Formula: sss_contribution + pagibig_contribution + philhealth_contribution + sss_loan + pagibig_loan + cash_advance + cash_bond + other_deduction + tax
                                    
                                    // Get existing values or default to 0
                                    $sssContribution = $payroll->sss_contribution ?? 0;
                                    $pagibigContribution = $payroll->pagibig_contribution ?? 0;
                                    $philhealthContribution = $payroll->philhealth_contribution ?? 0;
                                    $sssLoan = $payroll->sss_loan ?? 0;
                                    $pagibigLoan = $payroll->pagibig_loan ?? 0;
                                    $cashAdvance = $payroll->cash_advance ?? 0;
                                    $cashBond = $payroll->cash_bond ?? 0;
                                    $otherDeduction = $payroll->other_deduction ?? 0;
                                    $tax = $payroll->tax ?? 0;
                                    
                                    // Calculate total deduction
                                    $totalDeduction = $sssContribution + $pagibigContribution + $philhealthContribution + 
                                                    $sssLoan + $pagibigLoan + $cashAdvance + $cashBond + 
                                                    $otherDeduction + $tax;
                                    
                                    // Store this value if needed for later calculations
                                    $payroll->total_deduction = $totalDeduction;
                                    
                                    echo number_format($totalDeduction, 2);
                                @endphp
                            </td>
                            <td class="text-right font-weight-bold">{{ number_format($payroll->net_salary, 2) }}</td>
                        </tr>
                    @endforeach
                    
                    <!-- Department subtotal row -->
                    <tr class="print-department-subtotal print-department-section" data-department="{{ $department }}">
                        <td colspan="3" class="text-right font-weight-bold">Department Total:</td>
                        <td class="text-right font-weight-bold">
                            @php
                                $deptSalaryTotal = $payrolls->sum(function($p) {
                                    return $p->employee->salary/2;
                                });
                                echo number_format($deptSalaryTotal, 2);
                            @endphp
                        </td>
                        <td></td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalBasicPaySum = 0;
                                foreach ($payrolls as $payroll) {
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPaySum += ($semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments);
                                }
                                echo number_format($totalBasicPaySum, 2);
                            @endphp
                        </td>
                        <td></td>
                        <td class="text-right font-weight-bold">
                            @php
                                $holidayTotal = $payrolls->sum('regular_holiday_hours') + $payrolls->sum('special_holiday_hours');
                                echo number_format($holidayTotal, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('night_premium_hours'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('allowances'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('other_adjustments'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalGrossPaySum = 0;
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate unpaid leave deduction
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Calculate total basic pay
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Other pay components
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    
                                    // Sum up total gross pay
                                    $totalGrossPaySum += ($totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances);
                                }
                                echo number_format($totalGrossPaySum, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('sss_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('employer_sss_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('pagibig_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('employer_pagibig_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('philhealth_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('employer_philhealth_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('sss_loan'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('pagibig_loan'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('cash_advance'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('cash_bond'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('other_deduction'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('tax'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalDeductionSum = 0;
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $sssContribution = $payroll->sss_contribution ?? 0;
                                    $pagibigContribution = $payroll->pagibig_contribution ?? 0;
                                    $philhealthContribution = $payroll->philhealth_contribution ?? 0;
                                    $sssLoan = $payroll->sss_loan ?? 0;
                                    $pagibigLoan = $payroll->pagibig_loan ?? 0;
                                    $cashAdvance = $payroll->cash_advance ?? 0;
                                    $cashBond = $payroll->cash_bond ?? 0;
                                    $otherDeduction = $payroll->other_deduction ?? 0;
                                    $tax = $payroll->tax ?? 0;
                                    
                                    // Sum up total deductions
                                    $totalDeductionSum += ($sssContribution + $pagibigContribution + $philhealthContribution + 
                                                      $sssLoan + $pagibigLoan + $cashAdvance + $cashBond + 
                                                      $otherDeduction + $tax);
                                }
                                echo number_format($totalDeductionSum, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('net_salary'), 2) }}
                        </td>
                    </tr>
                    
                    <!-- Department-specific grand totals that will show when filtering -->
                    <tr class="print-grand-total print-department-section" data-department="{{ $department }}">
                        <td colspan="3" class="text-right font-weight-bold">
                            @if($department == 'BGPDI')
                                BGPDI TOTAL:
                            @elseif($department == 'VHI')
                                VHI TOTAL:
                            @elseif($department == 'Marketing Department' || $department == 'Technical Department')
                                MARKETING DEPARTMENT TOTAL:
                            @else
                                SUPPORT PERSONNEL TOTAL:
                            @endif
                        </td>
                        <td class="text-right font-weight-bold">
                            @php
                                $deptSalaryTotal = $payrolls->sum(function($p) {
                                    return $p->employee->salary/2;
                                });
                                echo number_format($deptSalaryTotal, 2);
                            @endphp
                        </td>
                        <td></td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalBasicPaySum = 0;
                                foreach ($payrolls as $payroll) {
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPaySum += ($semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments);
                                }
                                echo number_format($totalBasicPaySum, 2);
                            @endphp
                        </td>
                        <td></td>
                        <td class="text-right font-weight-bold">
                            @php
                                $holidayTotal = $payrolls->sum('regular_holiday_hours') + $payrolls->sum('special_holiday_hours');
                                echo number_format($holidayTotal, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('night_premium_hours'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('allowances'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('other_adjustments'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalGrossPaySum = 0;
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate unpaid leave deduction
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Calculate total basic pay
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Other pay components
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    
                                    // Sum up total gross pay
                                    $totalGrossPaySum += ($totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances);
                                }
                                echo number_format($totalGrossPaySum, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('sss_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('employer_sss_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('pagibig_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('employer_pagibig_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('philhealth_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('employer_philhealth_contribution'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('sss_loan'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('pagibig_loan'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('cash_advance'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('cash_bond'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('other_deduction'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('tax'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalDeductionSum = 0;
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $sssContribution = $payroll->sss_contribution ?? 0;
                                    $pagibigContribution = $payroll->pagibig_contribution ?? 0;
                                    $philhealthContribution = $payroll->philhealth_contribution ?? 0;
                                    $sssLoan = $payroll->sss_loan ?? 0;
                                    $pagibigLoan = $payroll->pagibig_loan ?? 0;
                                    $cashAdvance = $payroll->cash_advance ?? 0;
                                    $cashBond = $payroll->cash_bond ?? 0;
                                    $otherDeduction = $payroll->other_deduction ?? 0;
                                    $tax = $payroll->tax ?? 0;
                                    
                                    // Sum up total deductions
                                    $totalDeductionSum += ($sssContribution + $pagibigContribution + $philhealthContribution + 
                                                      $sssLoan + $pagibigLoan + $cashAdvance + $cashBond + 
                                                      $otherDeduction + $tax);
                                }
                                echo number_format($totalDeductionSum, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('net_salary'), 2) }}
                        </td>
                    </tr>
                @endforeach
                
                <!-- Grand total row for all departments -->
                <tr class="print-grand-total all-departments-total">
                    <td colspan="3" class="text-right font-weight-bold">GRAND TOTAL (ALL DEPARTMENTS):</td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSalaryTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandSalaryTotal += $payrolls->sum(function($p) {
                                    return $p->employee->salary/2;
                                });
                            }
                            echo number_format($grandSalaryTotal, 2);
                        @endphp
                    </td>
                    <td></td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandBasicTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                foreach ($payrolls as $payroll) {
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $grandBasicTotal += ($semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments);
                                }
                            }
                            echo number_format($grandBasicTotal, 2);
                        @endphp
                    </td>
                    <td></td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandHolidayTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandHolidayTotal += $payrolls->sum('regular_holiday_hours') + $payrolls->sum('special_holiday_hours');
                            }
                            echo number_format($grandHolidayTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandNightTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandNightTotal += $payrolls->sum('night_premium_hours');
                            }
                            echo number_format($grandNightTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandAllowanceTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandAllowanceTotal += $payrolls->sum('allowances');
                            }
                            echo number_format($grandAllowanceTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandAdjustmentsTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandAdjustmentsTotal += $payrolls->sum('other_adjustments');
                            }
                            echo number_format($grandAdjustmentsTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandGrossTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate unpaid leave deduction
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Calculate total basic pay
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Other pay components
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    
                                    // Sum up total gross pay
                                    $grandGrossTotal += ($totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances);
                                }
                            }
                            echo number_format($grandGrossTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSSSTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandSSSTotal += $payrolls->sum('sss_contribution');
                            }
                            echo number_format($grandSSSTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSSSERTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandSSSERTotal += $payrolls->sum('employer_sss_contribution');
                            }
                            echo number_format($grandSSSERTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPagibigTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandPagibigTotal += $payrolls->sum('pagibig_contribution');
                            }
                            echo number_format($grandPagibigTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPagibigERTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandPagibigERTotal += $payrolls->sum('employer_pagibig_contribution');
                            }
                            echo number_format($grandPagibigERTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPhilhealthTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandPhilhealthTotal += $payrolls->sum('philhealth_contribution');
                            }
                            echo number_format($grandPhilhealthTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPhilhealthERTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandPhilhealthERTotal += $payrolls->sum('employer_philhealth_contribution');
                            }
                            echo number_format($grandPhilhealthERTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSSSLoanTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandSSSLoanTotal += $payrolls->sum('sss_loan');
                            }
                            echo number_format($grandSSSLoanTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPagibigLoanTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandPagibigLoanTotal += $payrolls->sum('pagibig_loan');
                            }
                            echo number_format($grandPagibigLoanTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandCashAdvanceTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandCashAdvanceTotal += $payrolls->sum('cash_advance');
                            }
                            echo number_format($grandCashAdvanceTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandCashBondTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandCashBondTotal += $payrolls->sum('cash_bond');
                            }
                            echo number_format($grandCashBondTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandOtherDeductionTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandOtherDeductionTotal += $payrolls->sum('other_deduction');
                            }
                            echo number_format($grandOtherDeductionTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandTaxTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandTaxTotal += $payrolls->sum('tax');
                            }
                            echo number_format($grandTaxTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandDeductionTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $sssContribution = $payroll->sss_contribution ?? 0;
                                    $pagibigContribution = $payroll->pagibig_contribution ?? 0;
                                    $philhealthContribution = $payroll->philhealth_contribution ?? 0;
                                    $sssLoan = $payroll->sss_loan ?? 0;
                                    $pagibigLoan = $payroll->pagibig_loan ?? 0;
                                    $cashAdvance = $payroll->cash_advance ?? 0;
                                    $cashBond = $payroll->cash_bond ?? 0;
                                    $otherDeduction = $payroll->other_deduction ?? 0;
                                    $tax = $payroll->tax ?? 0;
                                    
                                    // Sum up total deductions
                                    $grandDeductionTotal += ($sssContribution + $pagibigContribution + $philhealthContribution + 
                                                      $sssLoan + $pagibigLoan + $cashAdvance + $cashBond + 
                                                      $otherDeduction + $tax);
                                }
                            }
                            echo number_format($grandDeductionTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandNetTotal = 0;
                            foreach($payrollsByDepartment as $payrolls) {
                                $grandNetTotal += $payrolls->sum('net_salary');
                            }
                            echo number_format($grandNetTotal, 2);
                        @endphp
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer section with pagination -->
    <div class="footer-section row">
        <div class="col-6">
            <small class="text-muted">Generated on: {{ date('F j, Y g:i A') }}</small>
        </div>
        <div class="col-6 text-right">
            <small class="text-muted">Page <span class="pageNumber">1</span> of <span class="totalPages">1</span></small>
        </div>
    </div>
    
    <!-- Print trigger script -->
    <script>
        // Auto-trigger print dialog when the page loads in print preview
        window.onload = function() {
            // Remove all images before printing
            removeImagesForPrinting();
            
            // Hide total rows for printing
            hideTotalRowsForPrinting();
            
            // Set font sizes for printing
            setFontSizesForPrinting();
            
            // Set up page numbering
            setupPageNumbering();
            
            // Adjust table layout to match the image
            adjustTableForPrinting();
            
            // Ensure proper page breaks
            setupPageBreaks();
            
            // Small delay to ensure everything is rendered
            setTimeout(function() {
                try {
                    // Apply final layout adjustments to match image
                    applyFinalPrintLayout();
                    
                    // One final check of font sizes before printing
                    enforceFontSizes();
                    
                    // Modern browsers print function
                    window.print();
                    
                    // Listen for print dialog completion
                    window.onafterprint = function() {
                        console.log("Printing completed or dialog closed");
                    };
                } catch (e) {
                    console.error("Printing failed: ", e);
                    handlePrintError(e);
                }
            }, 1000); // Increased delay for better compatibility
        };

        // New function to hide all total rows before printing
        function hideTotalRowsForPrinting() {
            // Hide department subtotals
            document.querySelectorAll('.print-department-subtotal').forEach(function(row) {
                row.style.display = 'none';
            });
            
            // Hide grand totals
            document.querySelectorAll('.print-grand-total').forEach(function(row) {
                row.style.display = 'none';
            });
            
            // Don't hide department headers - we need them for identification
            // document.querySelectorAll('.print-department-header').forEach(function(row) {
            //     row.style.display = 'none';
            // });
            
            // Hide any row that has "TOTAL" in its text except department headers
            document.querySelectorAll('tr').forEach(function(row) {
                if ((row.textContent.includes('TOTAL') || 
                    row.textContent.includes('Total:') || 
                    row.classList.contains('all-departments-total')) &&
                    !row.classList.contains('print-department-header')) {
                    row.style.display = 'none';
                }
            });
        }

        // New function to remove all images before printing
        function removeImagesForPrinting() {
            var images = document.querySelectorAll('img');
            images.forEach(function(img) {
                img.style.display = 'none';
                // Also add a class for easier targeting with CSS
                img.classList.add('print-hidden');
            });
            
            // Also remove background images
            var elementsWithBgImage = document.querySelectorAll('[style*="background-image"]');
            elementsWithBgImage.forEach(function(el) {
                el.style.backgroundImage = 'none';
            });
        }

        // New function to set font sizes for printing
        function setFontSizesForPrinting() {
            // Set all td elements to exactly 6pt
            document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                cell.style.fontSize = '6pt';
                // Add !important flag via cssText
                cell.style.cssText += '; font-size: 6pt !important;';
                // Adjust line height for better readability with small font
                cell.style.lineHeight = '1.1';
                // Set font weight for better clarity at small size
                cell.style.fontWeight = 'normal';
                // Enhance contrast for better readability
                cell.style.color = '#000000';
            });
            
            // Set all th elements to 4pt as previously configured
            document.querySelectorAll('.payroll-print-table th').forEach(function(cell) {
                cell.style.fontSize = '4pt';
                // Force center alignment for all headers
                cell.style.textAlign = 'center';
                cell.style.cssText += '; text-align: center !important;';
            });
        }

        // New function to enforce font sizes right before printing
        function enforceFontSizes() {
            // Apply important styles directly to elements
            document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                cell.style.setProperty('font-size', '6pt', 'important');
                cell.style.setProperty('line-height', '1.1', 'important');
            });
            
            // Add a final style block to force font sizes
            var style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    .payroll-print-table td {
                        font-size: 6pt !important;
                        line-height: 1.1 !important;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Function to adjust table layout similar to the image
        function adjustTableForPrinting() {
            // Calculate and apply scaling to fit the table within page width
            var table = document.querySelector('.payroll-print-table');
            if (table) {
                // Fix overall table width to match image
                table.style.width = '100%';
                table.style.maxWidth = '100%';
                table.style.tableLayout = 'auto';
                
                // Enable auto-fit for table
                applyTableScaling(table);
            }
            
            // Make all empty cells consistent
            var cells = document.querySelectorAll('.payroll-print-table td');
            for (var i = 0; i < cells.length; i++) {
                if (cells[i].textContent.trim() === '' || cells[i].textContent.trim() === '-') {
                    cells[i].innerHTML = '';
                    cells[i].classList.add('empty-cell');
                }
                
                // Ensure all cells have consistent height
                cells[i].style.height = '18px'; // Slightly reduced for smaller font
                
                // Set font size directly on each cell
                cells[i].style.fontSize = '6pt';
            }
            
            // Adjust header cells to fit text
            var headerCells = document.querySelectorAll('.payroll-print-table th');
            for (var i = 0; i < headerCells.length; i++) {
                // Remove any fixed width settings
                headerCells[i].style.width = '';
                headerCells[i].style.maxWidth = '';
                headerCells[i].style.minWidth = '';
                
                // Ensure text wrapping for header cells
                headerCells[i].style.whiteSpace = 'normal';
                headerCells[i].style.wordWrap = 'break-word';
            }
            
            // Ensure proper text alignment in all cells
            document.querySelectorAll('.payroll-print-table td.text-right').forEach(function(cell) {
                cell.style.textAlign = 'right';
            });
            
            document.querySelectorAll('.payroll-print-table td.text-center').forEach(function(cell) {
                cell.style.textAlign = 'center';
            });
            
            // Ensure all header cells are vertically centered
            document.querySelectorAll('.payroll-print-table th').forEach(function(cell) {
                cell.style.verticalAlign = 'middle';
            });
        }

        // New function to handle table scaling to fit the page
        function applyTableScaling(table) {
            // Get table and page dimensions
            var tableWidth = table.offsetWidth;
            var pageWidth = window.innerWidth - 96; // 96px = approximately 1 inch margins
            
            // Calculate scale factor if table is wider than page
            if (tableWidth > pageWidth) {
                var scaleFactor = pageWidth / tableWidth;
                
                // Ensure minimum scale for readability (80% minimum)
                scaleFactor = Math.max(scaleFactor, 0.8);
                
                // Apply scale transform 
                table.style.transform = 'scale(' + scaleFactor + ')';
                
                // Adjust parent container to account for scaling
                table.style.transformOrigin = 'top left';
                table.parentNode.style.maxWidth = (tableWidth * scaleFactor) + 'px';
                
                // No need to increase font size when scaling down since we want it fixed at 6pt
                // Just ensure it doesn't go below 6pt
                document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                    cell.style.setProperty('font-size', '6pt', 'important');
                });
            }
        }

        // Fix for improper rendering in some browsers
        function setupPrintMode() {
            // Force a repaint of the page to fix rendering issues
            document.body.classList.add('ready-to-print');
            
            // Apply legal page size with browser-specific prefixes for better compatibility
            var style = document.createElement('style');
            style.innerHTML = `
                @page {
                    size: legal landscape;
                    margin: 0.5in;
                    scale: auto;
                }
                @-moz-document url-prefix() {
                    @page {
                        size: legal landscape;
                        margin: 0.5in;
                        scale: auto;
                    }
                }
                @media print and (-webkit-min-device-pixel-ratio:0) {
                    @page {
                        size: legal landscape;
                        margin: 0.5in;
                        scale: auto;
                    }
                }
                @media print {
                    /* Hide all images in print */
                    img, .print-hidden {
                        display: none !important;
                    }
                    
                    /* Remove all background images */
                    * {
                        background-image: none !important;
                    }
                    
                    /* Don't hide department headers but hide other totals */
                    .print-department-subtotal,
                    .print-grand-total,
                    tr[class*="total"],
                    .all-departments-total {
                        display: none !important;
                    }
                    
                    /* Ensure department headers are visible with their specific colors */
                    .print-department-header {
                        display: table-row !important;
                        page-break-before: always !important;
                    }
                    
                    .print-department-header[data-department="Support Personnel"] td {
                        background-color: #e6f2ff !important;
                    }
                    
                    .print-department-header[data-department="BGPDI"] td {
                        background-color: #e6ffe6 !important;
                    }
                    
                    .print-department-header[data-department="VHI"] td {
                        background-color: #fff2e6 !important;
                    }
                    
                    .print-department-header[data-department="Marketing Department"] td,
                    .print-department-header[data-department="Technical Department"] td {
                        background-color: #ffe6e6 !important;
                    }
                    
                    /* Force exact 6pt font size on all table cells */
                    .payroll-print-table td {
                        font-size: 6pt !important;
                        line-height: 1.1 !important;
                        min-height: 8pt;
                    }
                    
                    /* Force center alignment on all th elements */
                    .payroll-print-table th {
                        text-align: center !important;
                        vertical-align: middle !important;
                    }
                    
                    /* For WebKit browsers */
                    @media (-webkit-min-device-pixel-ratio: 0) {
                        .payroll-print-table td {
                            font-size: 6pt !important;
                        }
                        .payroll-print-table th {
                            text-align: center !important;
                        }
                    }
                    
                    /* For Firefox */
                    @-moz-document url-prefix() {
                        .payroll-print-table td {
                            font-size: 6pt !important;
                        }
                        .payroll-print-table th {
                            text-align: center !important;
                        }
                    }
                    
                    /* For IE */
                    @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
                        .payroll-print-table td {
                            font-size: 6pt !important;
                        }
                        .payroll-print-table th {
                            text-align: center !important;
                        }
                    }
                    
                    .payroll-print-table {
                        width: 100% !important;
                        max-width: 100% !important;
                        transform: scale(1);
                        transform-origin: top left;
                        table-layout: auto !important;
                    }
                    
                    /* CSS for Internet Explorer */
                    @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
                        .payroll-print-table {
                            width: 100% !important;
                            zoom: 0.9;
                        }
                    }
                }
            `;
            style.id = 'force-legal-size';
            document.head.appendChild(style);
            
            // Apply print-specific styles to improve reliability
            document.querySelector('.print-content').classList.add('printing-active');
            
            // Add listener for media query to handle print layouts
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    // When printing, reapply table scaling
                    var table = document.querySelector('.payroll-print-table');
                    if (table) {
                        applyTableScaling(table);
                    }
                }
            });
        }
        
        // Ensure proper page breaks between departments
        function setupPageBreaks() {
            // Force page breaks before each department header
            document.querySelectorAll('.print-department-header').forEach(function(header, index) {
                if (index > 0) { // Skip the first one
                    header.style.pageBreakBefore = 'always';
                    header.style.breakBefore = 'always'; // Modern browsers
                }
                
                // Add a visible separator line
                var hr = document.createElement('hr');
                hr.className = 'department-separator';
                hr.style.borderTop = '2px solid #000';
                hr.style.margin = '0';
                hr.style.pageBreakAfter = 'avoid';
                header.parentNode.insertBefore(hr, header);
            });
            
            // Add page break hints before department headers (except the first one)
            var departmentHeaders = document.querySelectorAll('.print-department-header');
            for (var i = 1; i < departmentHeaders.length; i++) { // Skip first header
                var wrapper = document.createElement('div');
                wrapper.className = 'suggested-page-break';
                wrapper.style.pageBreakBefore = 'always';
                wrapper.style.breakBefore = 'always'; // Modern browsers
                departmentHeaders[i].parentNode.insertBefore(wrapper, departmentHeaders[i]);
            }
            
            // Make sure grand total is on its own page
            var grandTotal = document.querySelector('.all-departments-total');
            if (grandTotal) {
                var wrapper = document.createElement('div');
                wrapper.className = 'suggested-page-break';
                wrapper.style.pageBreakBefore = 'always';
                grandTotal.parentNode.insertBefore(wrapper, grandTotal);
            }
            
            // Apply "avoid page break inside" to department subtotals and their preceding row
            var subtotals = document.querySelectorAll('.print-department-subtotal');
            subtotals.forEach(function(subtotal) {
                var previousRow = subtotal.previousElementSibling;
                if (previousRow) {
                    previousRow.style.pageBreakAfter = 'avoid';
                }
                subtotal.style.pageBreakInside = 'avoid';
            });
        }
        
        // Set up page numbering for multiple pages
        function setupPageNumbering() {
            // This script will be executed during printing to add page numbers
            var pageNumScript = document.createElement('script');
            pageNumScript.innerHTML = 
                "(function() {" +
                    "// Initialize page counter" +
                    "var pageNum = 1;" +
                    "var totalPages = 0;" +
                    
                    "// Calculate total pages (improved method)" +
                    "function calculateTotalPages() {" +
                        "// Get the table height" +
                        "var table = document.querySelector('.payroll-print-table');" +
                        "if (!table) return 1;" +
                        
                        "var tableHeight = table.offsetHeight;" +
                        
                        "// Get the effective page height (accounting for headers and footers)" +
                        "var headerHeight = document.querySelector('.print-company-header') ? " +
                            "document.querySelector('.print-company-header').offsetHeight : 0;" +
                        "var footerHeight = document.querySelector('.footer-section') ?" +
                            "document.querySelector('.footer-section').offsetHeight : 0;" +
                        
                        "// Calculate effective page height (96px = 1 inch in most browsers)" +
                        "var pageHeight = window.innerHeight - 96 - headerHeight - footerHeight;" +
                        
                        "// Count department sections as they will each start on a new page" +
                        "var departmentCount = document.querySelectorAll('.print-department-header').length;" +
                        
                        "// Get average rows per department" +
                        "var totalRows = document.querySelectorAll('.print-employee-row').length;" +
                        "var avgRowsPerDept = departmentCount > 0 ? Math.ceil(totalRows / departmentCount) : 0;" +
                        
                        "// Calculate rows that fit per page" +
                        "var rowHeight = 22;" + // Height of a typical row in pixels
                        "var rowsPerPage = Math.floor(pageHeight / rowHeight);" +
                        
                        "// Calculate pages needed per department" +
                        "var pagesPerDept = Math.ceil(avgRowsPerDept / rowsPerPage);" +
                        
                        "// Total pages is at least the number of departments" +
                        "var calculatedPages = Math.max(departmentCount, Math.ceil(tableHeight / pageHeight));" +
                        
                        "// Add extra pages for each department that exceeds one page" +
                        "if (pagesPerDept > 1) {" +
                            "calculatedPages = departmentCount * pagesPerDept;" +
                        "}" +
                        
                        "// Ensure minimum of 1 page" +
                        "return Math.max(1, calculatedPages);" +
                    "}" +
                    
                    "// Update page numbers on each page" +
                    "function updatePageNumbers() {" +
                        "totalPages = calculateTotalPages();" +
                        "var pageNumElements = document.querySelectorAll('.pageNumber');" +
                        "var totalPagesElements = document.querySelectorAll('.totalPages');" +
                        
                        "for (var i = 0; i < totalPagesElements.length; i++) {" +
                            "totalPagesElements[i].textContent = totalPages.toString();" +
                        "}" +
                    "}" +
                    
                    "// Add CSS counter for page numbering" +
                    "function addPageCounterCSS() {" +
                        "var style = document.createElement('style');" +
                        "style.innerHTML = " +
                            "'@page {" +
                                "counter-increment: page;" +
                            "}' +" +
                            
                            "'.pageNumber::after {" +
                                "content: counter(page);" +
                            "}';" +
                        "document.head.appendChild(style);" +
                    "}" +
                    
                    "// Update page numbers before printing" +
                    "window.onbeforeprint = function() {" +
                        "updatePageNumbers();" +
                        "addPageCounterCSS();" +
                    "};" +
                    
                    "// Initialize page numbers immediately" +
                    "updatePageNumbers();" +
                    
                    "// Use print media change detection for page numbering where supported" +
                    "if (window.matchMedia) {" +
                        "var mediaQueryList = window.matchMedia('print');" +
                        "mediaQueryList.addListener(function(mql) {" +
                            "if (!mql.matches) {" +
                                "// Printing finished" +
                                "pageNum = 1;" +
                            "}" +
                        "});" +
                    "}" +
                "})();";
            document.head.appendChild(pageNumScript);
            
            // Add CSS for page number styling
            var pageNumStyle = document.createElement('style');
            pageNumStyle.innerHTML = 
                "@media print {" +
                    "/* Page number display */" +
                    ".footer-section {" +
                        "position: fixed;" +
                        "bottom: 0.3in;" +
                        "left: 0.5in;" +
                        "right: 0.5in;" +
                        "font-size: 8pt;" +
                        "border-top: 1px solid #ddd;" +
                        "padding-top: 0.1in;" +
                        "z-index: 9999;" +
                    "}" +
                    
                    ".pageNumber, .totalPages {" +
                        "font-weight: bold;" +
                    "}" +
                    
                    "/* Reset page counter for each new department */" +
                    ".print-department-header {" +
                        "counter-reset: department-page 0;" +
                    "}" +
                    
                    "/* Department-specific page counter */" +
                    ".print-department-section {" +
                        "counter-increment: department-page;" +
                    "}" +
                "}";
            document.head.appendChild(pageNumStyle);
        }
        
        // Apply final layout adjustments to precisely match the image
        function applyFinalPrintLayout() {
            // Set exact font sizes
            document.querySelectorAll('.payroll-print-table th').forEach(function(cell) {
                cell.style.fontSize = '4pt';
                cell.style.fontWeight = 'bold';
                // Ensure center alignment for headers
                cell.style.setProperty('text-align', 'center', 'important');
                // Allow text to wrap in headers
                cell.style.whiteSpace = 'normal';
                cell.style.wordWrap = 'break-word';
                cell.style.wordBreak = 'break-word';
                // Remove any fixed width settings
                cell.style.width = '';
                cell.style.maxWidth = '';
                cell.style.minWidth = '';
            });
            
            document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                // Apply 6pt font size with !important to override any other styles
                cell.style.setProperty('font-size', '6pt', 'important');
                // Adjust line height for better readability with small font
                cell.style.setProperty('line-height', '1.1', 'important');
                // Ensure text doesn't overflow and is properly visible
                cell.style.overflow = 'hidden';
                cell.style.textOverflow = 'ellipsis';
                // Maintain numerical precision
                if (cell.classList.contains('text-right')) {
                    cell.style.fontVariantNumeric = 'tabular-nums';
                }
            });
            
            // Ensure department headers stand out
            document.querySelectorAll('.print-department-header td').forEach(function(cell) {
                cell.style.backgroundColor = '#eaeaea';
                cell.style.fontWeight = 'bold';
                cell.style.textTransform = 'uppercase';
            });
            
            // Ensure employee numbers are centered
            document.querySelectorAll('.payroll-print-table td:first-child').forEach(function(cell) {
                cell.style.textAlign = 'center';
            });
            
            // Make numeric columns right aligned
            var numericColumns = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];
            numericColumns.forEach(function(colIndex) {
                document.querySelectorAll(`.payroll-print-table td:nth-child(${colIndex})`).forEach(function(cell) {
                    cell.style.textAlign = 'right';
                });
            });
            
            // Fix headers
            document.querySelector('.print-company-header').style.position = 'fixed';
            document.querySelector('.print-company-header').style.top = '0.3in';
            document.querySelector('.print-company-header').style.width = '100%';
            document.querySelector('.print-company-header').style.zIndex = '100';
            document.querySelector('.print-company-header').style.backgroundColor = 'white';
            
            // Set table spacing to align with image
            document.querySelector('.payroll-print-table').style.marginTop = '1in';
            
            // Fix grid line colors
            document.querySelectorAll('.payroll-print-table th, .payroll-print-table td').forEach(function(cell) {
                cell.style.border = '1px solid #000';
            });
            
            // Final check to ensure table fits page width and apply scaling if needed
            var table = document.querySelector('.payroll-print-table');
            if (table) {
                table.style.tableLayout = 'auto';
                applyTableScaling(table);
            }
        }
        
        // Reapply scaling when window is resized (for preview)
        window.addEventListener('resize', function() {
            var table = document.querySelector('.payroll-print-table');
            if (table) {
                applyTableScaling(table);
            }
        });
        
        // Handle print errors with helpful feedback
        function handlePrintError(error) {
            console.error("Print error:", error);
            
            // Create a fallback print button
            var printButton = document.createElement('button');
            printButton.style.position = 'fixed';
            printButton.style.top = '10px';
            printButton.style.right = '10px';
            printButton.style.zIndex = '9999';
            printButton.style.padding = '10px 15px';
            printButton.style.backgroundColor = '#4CAF50';
            printButton.style.color = 'white';
            printButton.style.border = 'none';
            printButton.style.borderRadius = '4px';
            printButton.style.cursor = 'pointer';
            printButton.style.fontWeight = 'bold';
            printButton.innerHTML = 'Print Now';
            
            // Add click handler
            printButton.onclick = function() {
                this.style.display = 'none';
                
                // Try printing again
                setTimeout(function() {
                    window.print();
                    setTimeout(function() {
                        printButton.style.display = 'block';
                    }, 1000);
                }, 200);
            };
            
            // Add error message
            var errorMsg = document.createElement('div');
            errorMsg.style.position = 'fixed';
            errorMsg.style.top = '50px';
            errorMsg.style.right = '10px';
            errorMsg.style.zIndex = '9999';
            errorMsg.style.padding = '10px';
            errorMsg.style.backgroundColor = '#ffeeee';
            errorMsg.style.border = '1px solid #ffcccc';
            errorMsg.style.borderRadius = '4px';
            errorMsg.style.maxWidth = '300px';
            errorMsg.innerHTML = 'Automatic printing failed. Please click the "Print Now" button above.';
            
            // Add to document
            document.body.appendChild(printButton);
            document.body.appendChild(errorMsg);
        }
    </script>
</div>
</div>