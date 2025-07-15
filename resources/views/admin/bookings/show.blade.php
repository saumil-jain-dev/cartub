@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
    @include('admin.components.breadcrumb', [
        'title' => $pageTitle,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
            ['label' => 'Booking Management', 'url' => route('bookings.index')],
            ['label' => $pageTitle] // Last item, no URL
        ]
    ])
<div class="container-fluid">
    <div class="row">
        <div class="col-xxl-9 col-xl-8 box-col-8e">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Order Status</h5>
                        </div>
                        <div class="card-body track-order-details">
                            <h6 id="order-status-timeline">
                                <div class="status-bar progress step-three"></div>
                                <div class="main-status-line">
                                    @php
                                        $currentStep = 1;

                                        if ($bookingDetails->status === 'pending') {
                                            $currentStep = 1;
                                        } elseif ($bookingDetails->status === 'accepted' && $bookingDetails->cleaner_id) {
                                            $currentStep = 2;
                                        } elseif ($bookingDetails->status === 'in_route') {
                                            $currentStep = 3;
                                        } elseif ($bookingDetails->status === 'in_progress') {
                                            $currentStep = 4;
                                        } elseif ($bookingDetails->status === 'completed') {
                                            $currentStep = 5;
                                        }

                                        $steps = [
                                            1 => 'Pending',
                                            2 => 'Assigned',
                                            3 => 'In Route',
                                            4 => 'In Progress',
                                            5 => 'Completed',
                                        ];
                                    @endphp

                                    <ul class="order-status">
                                        @foreach ($steps as $step => $label)
                                            <li>
                                                <div class="order-process {{ $currentStep >= $step ? 'active' : '' }}"><span>{{ $step }}</span></div>
                                                <h6>{{ $label }}</h6>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5>Order Number: {{ $bookingDetails->booking_number }}</h5>
                            </div>
                        </div>
                        <div class="card-body order-details-product pt-0">
                            <div class="table-responsive custom-scrollbar">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            {{-- <th scope="col">Car Image</th> --}}
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Wash Type</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            {{-- <td>
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="assets/images/car.jpg"
                                                        alt="headphone"></div>
                                            </td> --}}
                                            <td>
                                                <ul>
                                                    <li>
                                                        <h6>{{ optional($bookingDetails->customer)->name ?? '-' }}</h6>
                                                    </li>
                                                    <li>
                                                        <p class="mt-2">{{ $bookingDetails->address }}</p>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>{{ optional($bookingDetails->washType)->name ?? '-' }}</td>
                                            <td>${{ number_format($bookingDetails->gross_amount,2) }}</td>
                                            <td>${{ number_format($bookingDetails->gross_amount,2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 box-col-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-no-border">
                            <div class="header-top summary-header">
                                <h5>Summary</h5>
                                <div class="card-header-right-icon"><a class="btn btn-primary"
                                        href="order-invoice" target="_blank"><i
                                            class="fa-regular fa-file-lines pe-2 f-14"></i>Invoice</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul class="tracking-total">
                                <li>
                                    <h6>Subtotal </h6><span> $
                                        {{ number_format($bookingDetails->gross_amount,2) }}</span>
                                </li>
                                <li>
                                    <h6>Coupon Discount</h6><span>(-){{ number_format($bookingDetails->discount_amount ?? 0 ,2) }}</span>
                                </li>
                                <li>
                                    <h6>Extra </h6><span class="txt-primary">0.00</span>
                                </li>
                                <li>
                                    <h6>Total</h6><span>${{ number_format($bookingDetails->total_amount ?? 0 ,2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5>Customer Details</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul class="customer-details">
                                <li>
                                    <h6>Name </h6><span>{{ optional($bookingDetails->customer)->name ?? '-' }}</span>
                                </li>
                                <li>
                                    <h6>Email Address:</h6><span>{{ optional($bookingDetails->customer)->email ?? '-' }}</span>
                                </li>
                                <li>
                                    <h6>Billing Address:</h6><span>{{ $bookingDetails->address }}</span>
                                </li>
                                <li>
                                    <h6>Slot:</h6><span>
                                        @php
                                            use Illuminate\Support\Carbon;
                                        @endphp
                                        {{ Carbon::parse($bookingDetails->scheduled_date)->format('d M Y') }},
                                        {{ Carbon::parse($bookingDetails->scheduled_time)->format('h:i A') }}
                                    </span>
                                </li>
                                <li>
                                    <h6>Payment Mode:</h6><span>{{ optional($bookingDetails->payment)->payment_type ?? '-' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12"> </div>
            </div>
        </div>
    </div>
</div>
@endsection