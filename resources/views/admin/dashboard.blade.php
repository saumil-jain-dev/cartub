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
<div class="container-fluid dashboard-13">
    <div class="row">
        <div class="col-xl-3 col-sm-6 d-none d-md-flex">
             <a href="{{ route('bookings.index') }}" class="text-decoration-none">
            <div class="card widget-13 widget-hover">
                <div class="card-body">
                    <div>
                        <div class="stat-content">
                            <div class="stat-square bg-light-primary">
                                <svg class="fill-primary">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-package') }}"></use>
                                </svg>
                            </div>
                            <div>
                                <p class="c-o-light mb-1">Total Bookings</p>
                                <h4><span id="totalBookings" class="counter" data-target="{{ $total_booking_count }}">{{ $total_booking_count }}</span></h4>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="common-space">
                        <div class="common-align font-success"><i class="me-1"
                                data-feather="trending-up"></i><span>+12.02%</span></div>
                        <div class="common-stat-option">
                            <div id="package-chart"></div>
                        </div>
                    </div> --}}
                </div>
            </div>
             </a>
        </div>
        <div class="col-xl-3 col-sm-6 d-none d-md-flex">
            <a href="{{ route('payment.index') }}" class="text-decoration-none">
            <div class="card widget-13 widget-hover">
                <div class="card-body">
                    <div>
                        <div class="stat-content">
                            <div class="stat-square bg-light-secondary">
                                <svg class="fill-secondary">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-progress-delivery') }}">
                                    </use>
                                </svg>
                            </div>
                            <div>
                                <p class="c-o-light mb-1">Total Revenue</p>
                                <h4><span id="totalRevenue" class="counter" data-target="{{ $total_revenue }}">{{ $total_revenue }}</span></h4>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="common-space">
                        <div class="common-align font-danger"><i class="me-1"
                                data-feather="trending-down"></i><span>-0.03%</span></div>
                        <div class="progress" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
                                style="width: 75%;"></div>
                        </div>
                    </div> --}}
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-3 col-sm-6 d-none d-md-flex">
             <a href="{{ route('users.index') }}" class="text-decoration-none">
            <div class="card widget-13 widget-hover">
                <div class="card-body">
                    <div>
                        <div class="stat-content">
                            <div class="stat-square bg-light-warning">
                                <svg class="fill-warning">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-delivered') }}"></use>
                                </svg>
                            </div>
                            <div>
                                <p class="c-o-light mb-1">Active Customers</p>
                                <h4><span id="activeCustomers" class="counter" data-target="{{ $total_active_customer }}">{{ $total_active_customer }}</span></h4>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="common-space">
                        <div class="common-align font-success"><i class="me-1"
                                data-feather="trending-up"></i><span>+13.04%</span></div>
                        <div class="delivered-stat-option">
                            <div id="package-delivered"></div>
                        </div>
                    </div> --}}
                </div>
            </div>
             </a>
        </div>
        <div class="col-xl-3 col-sm-6 d-none d-md-flex">
             <a href="{{ route('cleaners.index') }}" class="text-decoration-none">
            <div class="card widget-13 widget-hover">
                <div class="card-body">
                    <div>
                        <div class="stat-content">
                            <div class="stat-square bg-light-success">
                                <svg class="fill-success">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-client') }}"></use>
                                </svg>
                            </div>
                            <div>
                                <p class="c-o-light mb-1">Active Cleaners</p>
                                <h4><span id="activeCleaners" class="counter" data-target="{{ $total_active_cleaner }}">{{ $total_active_cleaner }}</span></h4>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="common-space">
                        <div class="common-align font-danger"><i class="me-1"
                                data-feather="trending-down"></i><span>-0.08%</span></div>
                        <div class="common-stat-option client-chart">
                            <div id="new-clients-chart"></div>
                        </div>
                    </div> --}}
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-7">
            <div class="card heading-space">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>Live Wash Status</h5>
                        <div class="card-header-right-icon">
                            <div class="dropdown icon-dropdown">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 shipment-tracking-table">
                    <div class="recent-table table-responsive custom-scrollbar">
                        <table class="table" id="shipment-tracking-table liveWashTable">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Status</th>
                                    <th style="display: block;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="liveWashTableBody">
                                @foreach($live_wash_data as $booking)
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="{{ route('bookings.show',$booking->id) }}" target="_blank">{{ $booking->vehicle?->model }}
                                            ({{ $booking->vehicle?->license_plate }})</a></td>


                                    <td>
                                        @php
                                            if ($booking->status === 'pending' && $booking->cleaner_id) {
                                                $badgeText = 'Assigned';
                                                $badgeClass = 'badge-info';
                                            } else {
                                                switch ($booking->status) {
                                                    case 'pending':
                                                        $badgeText = 'Pending';
                                                        $badgeClass = 'f-14 f-w-400 txt-danger';
                                                        break;
                                                    case 'in_route':
                                                        $badgeText = 'In Route';
                                                        $badgeClass = 'f-14 f-w-400 txt-primary';
                                                        break;
                                                    case 'in_progress':
                                                        $badgeText = 'In Progress';
                                                        $badgeClass = 'f-14 f-w-400 txt-secondary';
                                                        break;
                                                    case 'completed':
                                                        $badgeText = 'Completed';
                                                        $badgeClass = 'f-14 f-w-400 txt-success';
                                                        break;
                                                    case 'cancelled':
                                                        $badgeText = 'Cancelled';
                                                        $badgeClass = 'f-14 f-w-400 txt-danger';
                                                        break;
                                                    default:
                                                        $badgeText = ucfirst($booking->status);
                                                        $badgeClass = 'f-14 f-w-400 txt-dark';
                                                }
                                            }
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                    </td>
                                </tr>

                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 d-none d-md-flex">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>Service Summary</h5>
                        <div class="card-header-right-icon">
                            {{-- <div class="dropdown icon-dropdown"><button class="btn dropdown-toggle"
                                    id="referralVisitOption" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="referralVisitOption"><a class="dropdown-item"
                                        href="#!">This Month</a><a class="dropdown-item"
                                        href="#!">Previous Month</a><a class="dropdown-item"
                                        href="#!">Last 3 Months</a><a class="dropdown-item"
                                        href="#!">Last 6 Months</a></div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 treading-product">
                    <div class="recent-table table-responsive custom-scrollbar referral-visit">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Wash Type</th>
                                    <th style="display: block;">Total Washes</th>
                                </tr>
                            </thead>
                            <tbody id="liveWashTypeTableBody">
                                @foreach ($washTypes as $washType)
                                    <tr>
                                        <td>
                                            <div class="referral-wrapper">
                                                <div>
                                                    <div class="border-secondary">
                                                        <div class="social-wrapper bg-light-secondary"><svg
                                                                class="stroke-icon">
                                                                <use
                                                                    href="{{ asset('assets/svg/icon-sprite.svg#s-pinterest') }}">
                                                                </use>
                                                            </svg></div>
                                                    </div>
                                                </div><span class="f-w-500">{{ $washType->name }}</span>
                                            </div>
                                        </td>


                                        <td class="f-w-500">{{ $washType->bookings_count }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

 <div class="modal fade" id="assignBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bookings.assign-cleaner') }}" method="POST" id="assignCleanerForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Cleaner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="booking_id" id="modalBookingId">
                        <input type="hidden" name="type" value="dashboard">
                        <div class="mb-3">
                            <label>Booking Number</label>
                            <input type="text" class="form-control" id="modalBookingNumber" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Cleaner</label>
                            <select name="cleaner_id" class="form-control" id="modalCleanerSelect" required>
                                <option value="">Loading available cleaners...</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Assign</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Firebase SDKs (compat version) -->
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database-compat.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function(){
             const firebaseConfig = {
                apiKey:     "AIzaSyAEAhr_ofPGlN8iADspxCaZ-GRyQ5JNbkI",
                authDomain: "cartub-5d584.firebaseapp.com",
                databaseURL:"https://cartub-5d584-default-rtdb.firebaseio.com",
                projectId:  "cartub-5d584",
                storageBucket:"cartub-5d584.firebasestorage.app",
                messagingSenderId:"1041741383694",
                appId:      "1:1041741383694:web:91e747e06fa8cfc04d545f",
                measurementId: "G-LE50W28NGY"
            };

            firebase.initializeApp(firebaseConfig);
            const bookingsRef = firebase.database().ref("bookings");

            const $totalBookings   = $('#totalBookings');
            const $totalRevenue    = $('#totalRevenue');
            const $activeCustomers = $('#activeCustomers');
            const $activeCleaners  = $('#activeCleaners');
            const $liveWashBody    = $('#liveWashTableBody');
            const $liveWashTypeTableBody = $('#liveWashTypeTableBody');


            const METRICS_URL = "{{ route('dashboard.metrics') }}";
            function refreshDashboard() {
                $.getJSON(METRICS_URL, function(data) {

                    // 1) Update cards
                    $totalBookings.text(data.total_booking_count);
                    $totalRevenue.text(parseFloat(data.total_revenue).toFixed(2));
                    $activeCustomers.text(data.total_active_customer);
                    $activeCleaners.text(data.total_active_cleaner);

                    // 2) Update live-wash table
                    $liveWashBody.empty();
                    $.each(data.live_wash_data, function(_, b) {
                        let actionBtns = `
                            <button class="btn btn-sm btn-primary me-1 assign-booking" data-number="${b.booking_number}" data-id="${b.id}"><i class="fa fa-edit"></i>
                            </button>
                        `;
                        const row = `
                        <tr>
                            <td><a href="/bookings/${b.id}" target="_blank">${b.vehicle}</a></td>
                            <td><span class="badge f-14 f-w-400 txt-dark">${b.status}</span></td>
                            <td style="display: block;">${actionBtns}</td>

                        </tr>`;
                        $liveWashBody.append(row);
                    });

                    // 3) WashType Booking Count Update
                    $liveWashTypeTableBody.empty();
                    $.each(data.wash_types, function(_, w) {
                        const rows = `
                        <tr>

                            <td><div class="referral-wrapper">
                                                <div>
                                                    <div class="border-secondary">
                                                        <div class="social-wrapper bg-light-secondary"><svg
                                                                class="stroke-icon">
                                                                <use
                                                                    href="{{ asset('assets/svg/icon-sprite.svg#s-pinterest') }}">
                                                                </use>
                                                            </svg></div>
                                                    </div>
                                                </div><span class="f-w-500">${w.name }</span>
                                            </div></td>

                            <td class="f-w-500" style="display:block;"> ${w.bookings_count}</td>

                        </tr>`;
                        $liveWashTypeTableBody.append(rows);
                        loadTable();
                    });
                }).fail(function(err){
                    console.error('Dashboard refresh failed', err);
                });

            }

            bookingsRef.on('child_added',   refreshDashboard,() => table.ajax.reload(null,false));
            bookingsRef.on('child_changed', refreshDashboard,() => table.ajax.reload(null,false));
            refreshDashboard();
            loadTable();

        });
        function loadTable(){
            setTimeout(() => {

                $('#liveWashTable').DataTable({
                    pageLength: 5,
                    responsive: true
                });
            }, 2000);
        }
        $(document).ready(function() {

            const modal = new bootstrap.Modal($('#assignBookingModal')[0]);

            $(document).on('click','.assign-booking',function(){

                const bookingId = $(this).data('id');
                const bookingNumber = $(this).data('number');
                console.log(bookingNumber,"bookingNumber");
                $('#modalBookingId').val(bookingId);
                $('#modalBookingNumber').val(bookingNumber);

                const $select = $('#modalCleanerSelect');
                $select.html('<option>Loading...</option>');

                $.ajax({

                    url: `${site_url}/admin/bookings/${bookingId}/available-cleaners`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (cleaners) {
                        $select.html('<option value="">Select Cleaner</option>');

                        if (cleaners.length === 0) {
                            $select.html('<option value="">No cleaner available</option>');
                        } else {
                            $.each(cleaners, function (i, cleaner) {
                                $select.append(`<option value="${cleaner.id}">${cleaner.name}</option>`);
                            });
                        }
                    },
                    error: function () {
                        $select.html('<option value="">Failed to load cleaners</option>');
                    }
                });

                modal.show();
            });
        });
    </script>
@endsection
