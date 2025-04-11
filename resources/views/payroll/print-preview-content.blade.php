<div class="print-content">
    <!-- Print-specific styles -->
    <style type="text/css" media="print">
        @page {
            size: legal landscape;
            margin: 0.5in;
        }
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .print-content {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }
        .payroll-print-table {
            width: 100%;
            font-size: 8pt;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            page-break-inside: auto;
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
            white-space: nowrap;
            line-height: 1.2;
        }
        .payroll-print-table th {
            background-color: #f2f2f2 !important;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 7pt;
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
        
        /* Ensure background colors print */
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Improve numeric column alignment */
        .text-right {
            text-align: right !important;
        }
        
        /* Ensure page breaks don't occur in the middle of departments */
        .print-department-section {
            page-break-inside: avoid;
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
        .print-department-header td {
            background-color: #eaeaea;
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
                            <td class="text-right">{{ isset($payroll->total_basic_pay) ? number_format($payroll->total_basic_pay, 2) : '-' }}</td>
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
                                        echo '-';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">{{ isset($payroll->night_premium_hours) ? number_format($payroll->night_premium_hours, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->allowances) ? number_format($payroll->allowances, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->other_adjustments) ? number_format($payroll->other_adjustments, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->total_gross_pay) ? number_format($payroll->total_gross_pay, 2) : '-' }}</td>
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
                            <td class="text-right">{{ isset($payroll->tax) ? number_format($payroll->tax, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->total_deduction) ? number_format($payroll->total_deduction, 2) : '-' }}</td>
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
                            {{ number_format($payrolls->sum('total_basic_pay'), 2) }}
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
                            {{ number_format($payrolls->sum('total_gross_pay'), 2) }}
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
                            {{ number_format($payrolls->sum('total_deduction'), 2) }}
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
                            {{ number_format($payrolls->sum('total_basic_pay'), 2) }}
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
                            {{ number_format($payrolls->sum('total_gross_pay'), 2) }}
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
                            {{ number_format($payrolls->sum('total_deduction'), 2) }}
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
                                $grandBasicTotal += $payrolls->sum('total_basic_pay');
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
                                $grandGrossTotal += $payrolls->sum('total_gross_pay');
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
                                $grandDeductionTotal += $payrolls->sum('total_deduction');
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
            <small class="text-muted">Page <span class="pageNumber"></span> of <span class="totalPages"></span></small>
        </div>
    </div>
    
    <!-- Print trigger script -->
    <script>
        // Auto-trigger print dialog when the page loads in print preview
        window.onload = function() {
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

        // Function to adjust table layout similar to the image
        function adjustTableForPrinting() {
            // Make all empty cells consistent
            var cells = document.querySelectorAll('.payroll-print-table td');
            for (var i = 0; i < cells.length; i++) {
                if (cells[i].textContent.trim() === '' || cells[i].textContent.trim() === '-') {
                    cells[i].innerHTML = '';
                    cells[i].classList.add('empty-cell');
                }
                
                // Ensure all cells have consistent height
                cells[i].style.height = '22px';
            }
            
            // Fix column widths to match the image exactly
            var columnWidths = [
                '32px',  // Employee Number
                '150px', // Employee Name
                '130px', // Position
                '50px',  // Semi-Monthly
                '50px',  // L/UT/LWOP
                '50px',  // Total BasicPay
                '50px',  // Overtime
                '50px',  // Holiday with Pay
                '50px',  // Night Premiums
                '50px',  // Allowance
                '50px',  // Other Adjustments
                '50px',  // Total Gross Pay
                '50px',  // SSS Prem EE Share
                '50px',  // SSS ER
                '50px',  // Pag-ibig Prem EE Share
                '50px',  // Pag-ibig ER
                '50px',  // PHIC Prem EE Share
                '50px',  // PHIC ER
                '50px',  // SSS Loan
                '50px',  // Pag-ibig Loan
                '50px',  // Cash Advance Salary
                '50px',  // Cash Bond
                '50px',  // Other Deduction
                '50px',  // W/Tax
                '50px',  // Total Ded.
                '55px'   // Net Pay
            ];
            
            // Apply column widths to match image layout - both to headers and cells for consistency
            var headerCells = document.querySelectorAll('.payroll-print-table th');
            for (var i = 0; i < headerCells.length; i++) {
                if (i < columnWidths.length) {
                    headerCells[i].style.width = columnWidths[i];
                    headerCells[i].style.maxWidth = columnWidths[i];
                    headerCells[i].style.minWidth = columnWidths[i];
                }
            }
            
            // Apply same widths to first row of cells for better column alignment
            var firstRowCells = document.querySelectorAll('.payroll-print-table tbody tr:first-child td');
            for (var i = 0; i < firstRowCells.length; i++) {
                if (i < columnWidths.length) {
                    firstRowCells[i].style.width = columnWidths[i];
                    firstRowCells[i].style.maxWidth = columnWidths[i];
                    firstRowCells[i].style.minWidth = columnWidths[i];
                }
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
            
            // Pre-compute table widths for faster rendering
            var table = document.querySelector('.payroll-print-table');
            if (table) {
                // Fix overall table width to match image
                table.style.width = '100%';
                table.style.maxWidth = '100%';
                table.style.tableLayout = 'fixed';
            }
        }

        // Fix for improper rendering in some browsers
        function setupPrintMode() {
            // Force a repaint of the page to fix rendering issues
            document.body.classList.add('ready-to-print');
            
            // Ensure all table cells are properly measured before printing
            var cells = document.querySelectorAll('.payroll-print-table td, .payroll-print-table th');
            for (var i = 0; i < cells.length; i++) {
                cells[i].style.height = '22px';
            }
            
            // Apply legal page size with browser-specific prefixes for better compatibility
            var style = document.createElement('style');
            style.innerHTML = `
                @page {
                    size: legal landscape;
                    margin: 0.5in;
                }
                @-moz-document url-prefix() {
                    @page {
                        size: legal landscape;
                        margin: 0.5in;
                    }
                }
                @media print and (-webkit-min-device-pixel-ratio:0) {
                    @page {
                        size: legal landscape;
                        margin: 0.5in;
                    }
                }
            `;
            style.id = 'force-legal-size';
            document.head.appendChild(style);
            
            // Apply print-specific styles to improve reliability
            document.querySelector('.print-content').classList.add('printing-active');
        }
        
        // Setup print mode when document is ready
        if (document.readyState === 'complete') {
            setupPrintMode();
        } else {
            document.addEventListener('DOMContentLoaded', setupPrintMode);
        }
        
        // Ensure proper page breaks between departments
        function setupPageBreaks() {
            // Add page break hints before department headers (except the first one)
            var departmentHeaders = document.querySelectorAll('.print-department-header');
            for (var i = 1; i < departmentHeaders.length; i++) { // Skip first header
                var wrapper = document.createElement('div');
                wrapper.className = 'suggested-page-break';
                wrapper.style.pageBreakBefore = 'always';
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
            pageNumScript.innerHTML = `
                (function() {
                    // Initialize page counter
                    var pageNum = 1;
                    var totalPages = 0;
                    
                    // Calculate total pages (improved method)
                    function calculateTotalPages() {
                        // Get the table height
                        var table = document.querySelector('.payroll-print-table');
                        if (!table) return 1;
                        
                        var tableHeight = table.offsetHeight;
                        
                        // Get the effective page height (accounting for headers and footers)
                        var headerHeight = document.querySelector('.print-company-header') ? 
                            document.querySelector('.print-company-header').offsetHeight : 0;
                        var footerHeight = document.querySelector('.footer-section') ?
                            document.querySelector('.footer-section').offsetHeight : 0;
                        
                        // Calculate effective page height (96px = 1 inch in most browsers)
                        var pageHeight = window.innerHeight - 96 - headerHeight - footerHeight;
                        
                        // Calculate pages, with minimum of 1
                        return Math.max(1, Math.ceil(tableHeight / pageHeight));
                    }
                    
                    // Update page number on each printed page
                    window.onbeforeprint = function() {
                        totalPages = calculateTotalPages();
                        var pageNumElements = document.querySelectorAll('.pageNumber');
                        var totalPagesElements = document.querySelectorAll('.totalPages');
                        
                        for (var i = 0; i < pageNumElements.length; i++) {
                            pageNumElements[i].textContent = '1';
                        }
                        
                        for (var i = 0; i < totalPagesElements.length; i++) {
                            totalPagesElements[i].textContent = totalPages.toString();
                        }
                    };
                    
                    // Use print media change detection for page numbering where supported
                    if (window.matchMedia) {
                        var mediaQueryList = window.matchMedia('print');
                        mediaQueryList.addListener(function(mql) {
                            if (!mql.matches) {
                                // Printing finished
                                pageNum = 1;
                            }
                        });
                    }
                })();
            `;
            document.head.appendChild(pageNumScript);
        }
        
        // Apply final layout adjustments to precisely match the image
        function applyFinalPrintLayout() {
            // Set exact font sizes
            document.querySelectorAll('.payroll-print-table th').forEach(function(cell) {
                cell.style.fontSize = '7pt';
                cell.style.fontWeight = 'bold';
            });
            
            document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                cell.style.fontSize = '8pt';
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
        }
        
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