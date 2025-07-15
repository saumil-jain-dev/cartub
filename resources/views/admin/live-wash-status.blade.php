@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid main-scope-project">
    <div class="row scope-bottom-wrapper">
        <div class="col-xxl-2 recent-xl-23 col-xl-3 box-col-3">
            <div class="card">
                <div class="card-body">
                    <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" id="car-pending-tab"
                                data-bs-toggle="pill" href="#car-pending-wash" role="tab"
                                aria-controls="car-pending-wash" aria-selected="false">
                                <div class="absolute-border"></div>
                                <div class="nav-rounded">
                                    <div class="product-icons"><svg>
                                            <use xlink:href="{{ asset('assets/svg/icon-sprite.svg#project-search') }}">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="product-tab-content">
                                    <h6>Pending</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" id="car-assigned-cleaner-tab"
                                data-bs-toggle="pill" href="#car-assigned-cleaner" role="tab"
                                aria-controls="car-assigned-cleaner" aria-selected="false">
                                <div class="absolute-border"></div>
                                <div class="nav-rounded">
                                    <div class="product-icons"><svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#project-target') }}">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="product-tab-content">
                                    <h6>Assigned</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" id="enroute-cleaner-tab"
                                data-bs-toggle="pill" href="#enroute-cleaner" role="tab"
                                aria-controls="enroute-cleaner" aria-selected="false">
                                <div class="absolute-border"></div>
                                <div class="nav-rounded">
                                    <div class="product-icons"><svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#project-badget') }}">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="product-tab-content">
                                    <h6>In Route</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="in-progress-wash-tab"
                                data-bs-toggle="pill" href="#in-progress-wash" role="tab"
                                aria-controls="in-progress-wash" aria-selected="false">
                                <div class="absolute-border"></div>
                                <div class="nav-rounded">
                                    <div class="product-icons"><svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#project-users') }}">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="product-tab-content">
                                    <h6>In Progress</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="completed-car-wash-tab"
                                data-bs-toggle="pill" href="#completed-car-wash" role="tab"
                                aria-controls="completed-car-wash" aria-selected="false">
                                <div class="absolute-border">

                                </div>
                                <div class="nav-rounded">
                                    <div class="product-icons"><svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-files') }}">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="product-tab-content">
                                    <h6>Completed</h6>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-10 recent-xl-77 col-xl-9 box-col-9">
            <div class="row">
                <div class="col-12">
                    <div class="common-project-header common-space m-b-20">
                        <div class="common-space">
                            <div class="pe-sm-3">
                                <h5>Dashboard<span class="badge badge-light-warning ms-2">In
                                        Pending</span></h5>
                            </div>
                        </div>
                        <div class="common-align">
                            
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 xl-100 box-col-12">
                    <div class="tab-content" id="add-product-pills-tabContent">
                        <div class="tab-pane fade show active" id="car-pending-wash" role="tabpanel"
                            aria-labelledby="car-pending-tab">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header card-no-border">
                                            <div class="common-space">
                                                <div class="left-header-content">
                                                    <h5>Pending Car</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 pt-0">
                                            <div
                                                class="recent-table table-responsive custom-scrollbar overall-budget">
                                                <table class="table" id="pending-wash">
                                                    <thead>
                                                        <tr>
                                                            <th>Booking ID</th>
                                                            <th>Customer Name</th>
                                                            <th>Phone</th>
                                                            <th>Wash Type</th>
                                                            <th>Scheduled Date & Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="car-assigned-cleaner" role="tabpanel"
                            aria-labelledby="car-assigned-cleaner-tab">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header card-no-border">
                                            <div class="common-space">
                                                <div class="left-header-content">
                                                    <h5>Assigned Car</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 pt-0">
                                            <div
                                                class="recent-table table-responsive custom-scrollbar overall-budget">
                                                <table class="table" id="assigned-wash">
                                                    <thead>
                                                        <tr>
                                                            <th>Booking ID</th>
                                                            <th>Cleaner Name</th>
                                                            <th>Customer Phone</th>
                                                            <th>Wash Type</th>
                                                            <th>Schedule</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="enroute-cleaner" role="tabpanel"
                            aria-labelledby="enroute-cleaner-tab">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header card-no-border">
                                            <div class="common-space">
                                                <div class="left-header-content">
                                                    <h5>In Route Cleaner</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 pt-0">
                                            <div
                                                class="recent-table table-responsive custom-scrollbar overall-budget">
                                                <table class="table" id="enroute-cleaner-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Booking ID</th>
                                                            <th>Vehicle Info</th>
                                                            <th>Wash Type</th>
                                                            <th>Location</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="in-progress-wash" role="tabpanel"
                            aria-labelledby="in-progress-wash-tab">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header card-no-border">
                                            <div class="common-space">
                                                <div class="left-header-content">
                                                    <h5>In Progress Car Wash</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 pt-0">
                                            <div
                                                class="recent-table table-responsive custom-scrollbar overall-budget">
                                                <table class="table" id="in-progress-car-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Booking ID</th>
                                                            <th>Vehicle</th>
                                                            <th>Wash Type</th>
                                                            <th>Washer Assigned</th>
                                                            <th>Start Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="completed-car-wash" role="tabpanel"
                            aria-labelledby="completed-car-wash-tab">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header card-no-border">
                                            <div class="common-space">
                                                <div class="left-header-content">
                                                    <h5>Pending Car</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 pt-0">
                                            <div class="recent-table table-responsive custom-scrollbar overall-budget">
                                                <table class="table" id="completed-car-wash-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Booking ID</th>
                                                            <th>Customer Name</th>
                                                            <th>Cleaner Name</th>
                                                            <th>Payment Status</th>
                                                            <th>Wash Type</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 xl-50 order-xxl-0 order-sm-2 col-md-6 box-col-6 box-ord-2">
                    <div class="card">
                        <div class="card-header card-no-border">
                            <div class="common-space">
                                <div class="left-header-content">
                                    <h5>Task Overview</h5>
                                    <p class="m-0 c-o-light">All 209 Task Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="summary-chart-box">
                                <div id="car-wash-summary-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        // map tab id -> status value
        const tabStatusMap = {
            'car-pending-tab': 'pending',
            'car-assigned-cleaner-tab': 'accepted',
            'enroute-cleaner-tab': 'in_route',
            'in-progress-wash-tab': 'in_progress',
            'completed-car-wash-tab': 'completed'
        };

        // On tab shown event
        $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            const tabId = $(e.target).attr('id'); // currently clicked tab id
            const status = tabStatusMap[tabId];

            if (status) {
                loadBookingsByStatus(status);
            }
        });

        // Load initial (first active tab) data
        loadBookingsByStatus('pending');

        function loadBookingsByStatus(status) {
            $.ajax({
                url: '{{ route("dashboard.bookings.by-status") }}', // You need to define this route
                method: 'GET',
                data: { status: status },
                success: function (response) {
                    renderTable(status, response.data);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function renderTable(status, data) {
            let $table;
            let columns;

            switch (status) {
                case 'pending':
                    $table = $('#pending-wash');
                    columns = [
                        { data: 'booking_number' },
                        { data: 'customer_name' },
                        { data: 'phone' },
                        { data: 'wash_type' },
                        { data: 'schedule' }
                    ];
                    break;

                case 'assigned':
                    $table = $('#assigned-wash');
                    columns = [
                        { data: 'booking_number' },
                        { data: 'cleaner_name' },
                        { data: 'customer_phone' },
                        { data: 'wash_type' },
                        { data: 'schedule' }
                    ];
                    break;

                // repeat for other statuses...
            }

            // destroy if already initialized
            if ( $.fn.DataTable.isDataTable( $table ) ) {
                $table.DataTable().destroy();
            }

            // empty tbody
            $table.find('tbody').empty();

            if (data.length === 0) {
                $table.find('tbody').html(`<tr><td colspan="${columns.length}" class="text-center">No records found</td></tr>`);
                return;
            }

            // fill tbody
            data.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.booking_number}</td>
                        <td>${item.customer?.name || '-'}</td>
                        <td>${item.customer?.phone || '-'}</td>
                        <td>${item.wash_type?.name || '-'}</td>
                        <td>${item.scheduled_date || '-'}</td>
                    </tr>
                `;
                $table.find('tbody').append(row);
            });

            // re-initialize DataTable
            $table.DataTable({
                pageLength: 10,
                responsive: true,
                destroy: true
            });
        }

        function formatDateTime(date, time) {
            return `${date} ${time || ''}`; // You can format with libraries if needed
        }
    });
</script>
@endsection