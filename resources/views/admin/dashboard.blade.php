@extends('admin.layouts.app')
@section('pageTitle', 'Dashboard')
@section('content')
@include('admin.components.breadcrumb', [
    'title' => 'Dashboard',
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Dashboard'] // Last item, no URL
    ]
])
<div class="container-fluid dashboard-13">
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card widget-13 widget-hover">
                <div class="card-body">
                    <div>
                        <div class="stat-content">
                            <div class="stat-square bg-light-primary">
                                <svg class="fill-primary">
                                    <use href="{{ asset('') }}assets/svg/icon-sprite.svg#stroke-package"></use>
                                </svg>
                            </div>
                            <div>
                                <p class="c-o-light mb-1">Total Bookings</p>
                                <h4><span class="counter" data-target="1284">0</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="common-space">
                        <div class="common-align font-success"><i class="me-1"
                                data-feather="trending-up"></i><span>+12.02%</span></div>
                        <div class="common-stat-option">
                            <div id="package-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
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
                                <h4><span class="counter" data-target="215750">0</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="common-space">
                        <div class="common-align font-danger"><i class="me-1"
                                data-feather="trending-down"></i><span>-0.03%</span></div>
                        <div class="progress" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
                                style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
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
                                <h4><span class="counter" data-target="342">0</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="common-space">
                        <div class="common-align font-success"><i class="me-1"
                                data-feather="trending-up"></i><span>+13.04%</span></div>
                        <div class="delivered-stat-option">
                            <div id="package-delivered"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
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
                                <h4><span class="counter" data-target="28">0</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="common-space">
                        <div class="common-align font-danger"><i class="me-1"
                                data-feather="trending-down"></i><span>-0.08%</span></div>
                        <div class="common-stat-option client-chart">
                            <div id="new-clients-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card heading-space">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>Live Wash Status</h5>
                        <div class="card-header-right-icon">
                            <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="shipmentTracking" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                        class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="shipmentTracking">
                                    <a class="dropdown-item" href="#!">This Month</a><a
                                        class="dropdown-item" href="#!">Previous Month</a><a
                                        class="dropdown-item" href="#!">Last 3 Months</a>
                                    <a class="dropdown-item" href="#!">Last 6 Months</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 shipment-tracking-table">
                    <div class="recent-table table-responsive custom-scrollbar">
                        <table class="table" id="shipment-tracking-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Vehicle</th>
                                    <th>Customer</th>
                                    <th>Cleaner</th>
                                    <th>Status</th>
                                    <th>ETA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Maruti Baleno
                                            (MH04AB1234)</a></td>
                                    <td>Rahul Sharma</td>
                                    <td>Akash</td>
                                    <td><span class="badge f-14 f-w-400 txt-warning">Washing</span></td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Hyundai Creta
                                            (MH01XY9876)</a></td>
                                    <td>Priya Nair</td>
                                    <td>Sameer</td>
                                    <td><span class="badge f-14 f-w-400 txt-danger">Waiting</span></td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Honda City
                                            (MH03CD5678)</a></td>
                                    <td>Aman Khan</td>
                                    <td>Rakesh</td>
                                    <td><span class="badge f-14 f-w-400 txt-success">Completed</span>
                                    </td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Maruti Baleno
                                            (MH04AB1234)</a></td>
                                    <td>Rahul Sharma</td>
                                    <td>Akash</td>
                                    <td><span class="badge f-14 f-w-400 txt-warning">Washing</span></td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Hyundai Creta
                                            (MH01XY9876)</a></td>
                                    <td>Priya Nair</td>
                                    <td>Sameer</td>
                                    <td><span class="badge f-14 f-w-400 txt-danger">Waiting</span></td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Honda City
                                            (MH03CD5678)</a></td>
                                    <td>Aman Khan</td>
                                    <td>Rakesh</td>
                                    <td><span class="badge f-14 f-w-400 txt-success">Completed</span>
                                    </td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Maruti Baleno
                                            (MH04AB1234)</a></td>
                                    <td>Rahul Sharma</td>
                                    <td>Akash</td>
                                    <td><span class="badge f-14 f-w-400 txt-warning">Washing</span></td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Hyundai Creta
                                            (MH01XY9876)</a></td>
                                    <td>Priya Nair</td>
                                    <td>Sameer</td>
                                    <td><span class="badge f-14 f-w-400 txt-danger">Waiting</span></td>
                                    <td>10 mins</td>
                                </tr>
                                <tr class="inbox-data">
                                    <td></td>
                                    <td><a href="order-details.html" target="_blank">Honda City
                                            (MH03CD5678)</a></td>
                                    <td>Aman Khan</td>
                                    <td>Rakesh</td>
                                    <td><span class="badge f-14 f-w-400 txt-success">Completed</span>
                                    </td>
                                    <td>10 mins</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-md-6">
            <div class="card delivery-chart sales-report">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>Bookings Trend</h5>
                        <div class="card-header-right-icon">
                            <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="deliveryDuration" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                        class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="deliveryDuration">
                                    <a class="dropdown-item" href="#!">This Week</a>
                                    <a class="dropdown-item" href="#!">This Month</a>
                                    <a class="dropdown-item" href="#!">Previous Month</a>
                                    <a class="dropdown-item" href="#!">Last 3 Months</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="delivery-duration-chart">
                        <div id="bookingTrendWeeklyChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>Today's Summary</h5>
                        <div class="card-header-right-icon">
                            <div class="dropdown icon-dropdown"><button class="btn dropdown-toggle"
                                    id="referralVisitOption" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="referralVisitOption"><a class="dropdown-item"
                                        href="#!">This Month</a><a class="dropdown-item"
                                        href="#!">Previous Month</a><a class="dropdown-item"
                                        href="#!">Last 3 Months</a><a class="dropdown-item"
                                        href="#!">Last 6 Months</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 treading-product">
                    <div class="recent-table table-responsive custom-scrollbar referral-visit">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Wash Type</th>
                                    <th>Avg. Duration</th>
                                    <th>Total Washes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="referral-wrapper">
                                            <div>
                                                <div class="bg-dark-primary">
                                                    <div class="social-wrapper"><svg
                                                            class="stroke-icon">
                                                            <use
                                                                href="{{ asset('assets/svg/icon-sprite.svg#s-facebook') }}">
                                                            </use>
                                                        </svg></div>
                                                </div><span class="f-w-500">Full Wash</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td> <button class="btn button-light-success f-w-500 txt-success">25
                                            mins</button>
                                    </td>
                                    <td class="f-w-500">52</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="referral-wrapper">
                                            <div>
                                                <div class="border-warning">
                                                    <div class="social-wrapper bg-light-warning"><svg
                                                            class="stroke-icon">
                                                            <use
                                                                href="{{ asset('assets/svg/icon-sprite.svg#s-instagram') }}">
                                                            </use>
                                                        </svg></div>
                                                </div>
                                            </div><span class="f-w-500">Exterior Only</span>
                                        </div>
                                    </td>
                                    <td> <button class="btn button-light-warning f-w-500 txt-warning">40
                                            mins</button>
                                    </td>
                                    <td class="f-w-500">28</td>
                                </tr>
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
                                            </div><span class="f-w-500">Interior Deep Clean</span>
                                        </div>
                                    </td>
                                    <td> <button class="btn button-light-info f-w-500 txt-info">20
                                            mins</button>
                                    </td>
                                    <td class="f-w-500">18</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="referral-wrapper">
                                            <div>
                                                <div class="border-info">
                                                    <div class="social-wrapper bg-light-info"><svg
                                                            class="stroke-icon">
                                                            <use
                                                                href="{{ asset('assets/svg/icon-sprite.svg#s-twitter') }}">
                                                            </use>
                                                        </svg></div>
                                                </div>
                                            </div><span class="f-w-500">Add-Ons (Polish etc.)</span>
                                        </div>
                                    </td>
                                    <td> <button class="btn button-light-danger f-w-500 txt-danger">35
                                            mins</button>
                                    </td>
                                    <td class="f-w-500">12</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>Hourly Booking Heatmap</h5>
                        <div class="card-header-right-icon">
                            <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="fleetStatus" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                        class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="fleetStatus">
                                    <a class="dropdown-item" href="#!">This Month</a><a
                                        class="dropdown-item" href="#!">Previous Month</a><a
                                        class="dropdown-item" href="#!">Last 3 Months</a>
                                    <a class="dropdown-item" href="#!">Last 6 Months</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="fleet-status-chart">
                        <div id="market-graph"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
