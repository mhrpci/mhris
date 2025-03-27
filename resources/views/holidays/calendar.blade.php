@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Holiday Calendar</h5>
                        <div class="ms-auto d-flex justify-content-end align-items-center">
                            <div class="me-3">
                                <input type="month" id="monthYearFilter" class="form-control form-control-sm" 
                                    value="{{ date('Y-m') }}" style="width: 150px;">
                            </div>
                            @can('holiday-create')
                            <div>
                                <a href="{{ route('holidays.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Holiday
                                </a>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Calendar Legend</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2" style="width: 30px;">&nbsp;</span>&nbsp;&nbsp;
                            <div>
                                <strong>Regular Holiday</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2" style="width: 30px;">&nbsp;</span>&nbsp;&nbsp;
                            <div>
                                <strong>Special Non-Working Holiday</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2" style="width: 30px;">&nbsp;</span>&nbsp;&nbsp;
                            <div>
                                <strong>Special Working Holiday</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2" style="width: 30px;">&nbsp;</span>&nbsp;&nbsp;
                            <div>
                                <strong>Pay Day</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2" style="width: 30px;">&nbsp;</span>&nbsp;&nbsp;
                            <div>
                                <strong>Quarterly Sales Review</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Holiday Modal -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHolidayModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addHolidayForm" action="{{ route('holidays.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Holiday Name</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="Regular Holiday">Regular Holiday</option>
                            <option value="Special Non-Working Holiday">Special Non-Working Holiday</option>
                            <option value="Special Working Holiday">Special Working Holiday</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<style>
    #calendar {
        margin: 20px 0;
        background: white;
    }
    .fc-day-grid-event {
        border-radius: 3px;
        padding: 4px 8px !important;
        margin: 2px 5px !important;
    }
    .fc-day-grid-event .fc-content {
        white-space: normal;
        overflow: hidden;
    }
    .fc-event-title {
        font-weight: 500;
    }
    .holiday-regular {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #fff !important;
    }
    .holiday-special {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important;
    }
    .holiday-special-working {
        background-color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #000 !important;
    }
    .pay-day {
        background-color: #198754 !important; /* Bootstrap success green */
        border-color: #198754 !important;
        color: #fff !important;
    }
    .quarterly-sales {
        background-color: #0d6efd !important; /* Bootstrap primary blue */
        border-color: #0d6efd !important;
        color: #fff !important;
    }
    
    /* Tooltip Styles */
    .holiday-tooltip-inner {
        max-width: 300px;
        padding: 10px;
        text-align: left;
    }
    
    .holiday-tooltip .holiday-title {
        font-size: 1.1em;
        margin-bottom: 5px;
    }
    
    .holiday-tooltip .holiday-type {
        color: #6c757d;
        font-size: 0.9em;
        margin-bottom: 3px;
    }
    
    .holiday-tooltip .holiday-date {
        font-size: 0.9em;
        color: #495057;
    }
    
    .tooltip-inner.holiday-tooltip-inner {
        background-color: white;
        color: #212529;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const holidays = @json($holidays);
        const payDays = @json($payDays ?? []);
        const quarterlySales = @json($quarterlySales ?? []);
        
        // Combine all events
        const allEvents = [
            ...holidays.map(holiday => ({
                title: holiday.title,
                start: holiday.date,
                className: getHolidayClass(holiday.type),
                allDay: true,
                description: holiday.type
            }))
        ];
        
        // Add pay days if they exist
        if (payDays.length > 0) {
            allEvents.push(...payDays.map(payDay => ({
                title: payDay.title,
                start: payDay.date,
                className: 'pay-day',
                allDay: true,
                description: 'Pay Day'
            })));
        }
        
        // Add quarterly sales if they exist
        if (quarterlySales.length > 0) {
            allEvents.push(...quarterlySales.map(quarter => ({
                title: quarter.title,
                start: quarter.date,
                className: 'quarterly-sales',
                allDay: true,
                description: 'Quarterly Sales Review'
            })));
        }
        
        const calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,listMonth'
            },
            events: allEvents,
            eventRender: function(event, element) {
                // Create a more detailed tooltip content
                const tooltipContent = `
                    <div class="holiday-tooltip">
                        <div class="holiday-title"><strong>${event.title}</strong></div>
                        <div class="holiday-type">${event.description}</div>
                        <div class="holiday-date">${moment(event.start).format('MMMM D, YYYY')}</div>
                    </div>
                `;

                // Add small text under the event title
                element.find('.fc-title').append('<br/><small>' + event.description + '</small>');
                
                // Initialize Bootstrap tooltip with enhanced options
                element.tooltip({
                    html: true,
                    title: tooltipContent,
                    placement: 'top',
                    container: 'body',
                    trigger: 'hover',
                    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner holiday-tooltip-inner"></div></div>'
                });
            },
            displayEventTime: false,
            firstDay: 0,
            height: 'auto',
            aspectRatio: 2,
            fixedWeekCount: false,
            showNonCurrentDates: false,
            eventLimit: true,
            views: {
                month: {
                    eventLimit: 3
                }
            }
        });

        // Add month/year filter handler
        document.getElementById('monthYearFilter').addEventListener('change', function(e) {
            const date = moment(this.value + '-01');
            $('#calendar').fullCalendar('gotoDate', date);
        });

        // Update month/year filter when calendar navigation changes
        calendar.on('viewRender', function(view) {
            const currentDate = $('#calendar').fullCalendar('getDate');
            document.getElementById('monthYearFilter').value = currentDate.format('YYYY-MM');
        });

        function getHolidayClass(type) {
            switch(type) {
                case 'Regular Holiday':
                    return 'holiday-regular';
                case 'Special Non-Working Holiday':
                    return 'holiday-special';
                case 'Special Working Holiday':
                    return 'holiday-special-working';
                case 'pay-day':
                    return 'pay-day';
                case 'quarterly-sales':
                    return 'quarterly-sales';
                default:
                    return 'holiday-regular';
            }
        }

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Fix: Update the selector to use the correct route
        document.querySelector('a[href="{{ route("holidays.create") }}"]').addEventListener('click', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('addHolidayModal'));
            modal.show();
        });

        // Handle form submission
        document.getElementById('addHolidayForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the calendar
                    $('#calendar').fullCalendar('renderEvent', {
                        title: data.holiday.title,
                        start: data.holiday.date,
                        className: getHolidayClass(data.holiday.type),
                        allDay: true,
                        description: data.holiday.type
                    });
                    
                    // Close modal and reset form
                    bootstrap.Modal.getInstance(document.getElementById('addHolidayModal')).hide();
                    this.reset();
                } else {
                    alert('Error saving holiday');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving holiday');
            });
        });
    });
</script>
@endpush
