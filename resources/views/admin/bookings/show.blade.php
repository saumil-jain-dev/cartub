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
                @if(in_array($bookingDetails->status, ['pending','accepted','in_route','mark_as_arrived']))
                    <a href="{{ route('bookings.track', $bookingDetails->id) }}"
                        class="btn btn-primary mb-3">
                        Track Booking
                    </a>
                @endif
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
                                            <td>{{ $bookingDetails->service_name ?? '-' }}</td>
                                            <td>£{{ number_format($bookingDetails->total_amount,2) }}</td>
                                            <td>£{{ number_format($bookingDetails->gross_amount,2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 ord-xxl-1 box-ord-1">
                                    <div class="row review-box">
                                        <div class="col-6">
                                            <div class="md-sidebar"><a class="btn btn-primary md-sidebar-toggle"
                                                    href="#!">seller profile</a>
                                                <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                                                    <div class="email-left-aside">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="accordion seller-profile"
                                                                    id="accordionPanelsStayOpenExample">
                                                                    <div class="accordion-item">
                                                                        <div class="accordion-header"><button
                                                                                class="accordion-button" type="button"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#panelsStayOpen-collapseOne"
                                                                                aria-expanded="true"
                                                                                aria-controls="panelsStayOpen-collapseOne">Cleaner
                                                                                Details
                                                                                Details</button></div>
                                                                        <div class="accordion-collapse collapse show"
                                                                            id="panelsStayOpen-collapseOne">
                                                                            <div class="accordion-body">
                                                                                <div class="common-f-start">
                                                                                    <img class="img-40 b-r-8"
                                                                                        src="{{ getImageAdmin($bookingDetails->cleaner?->profile_picture) }}"
                                                                                        alt="#">
                                                                                    <div>
                                                                                        <h5>{{ $bookingDetails->cleaner?->name }}</h5>
                                                                                    </div>
                                                                                </div>
                                                                                <ul class="seller-details">
                                                                                   
                                                                                    <li>
                                                                                        <div><i
                                                                                                class="fa-solid fa-phone"></i>
                                                                                            <h6>Phone Number </h6>
                                                                                        </div><span>+{{ $bookingDetails->cleaner?->country_code }}
                                                                                            {{ $bookingDetails->cleaner?->phone }}</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <div><i
                                                                                                class="fa-solid fa-envelope"></i>
                                                                                            <h6>Email</h6>
                                                                                        </div>
                                                                                        <span>{{ $bookingDetails->cleaner?->email }}</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <div><i
                                                                                                class="fa-solid fa-euro-sign"></i>
                                                                                            <h6>Order Tips</h6>
                                                                                        </div>
                                                                                        <span><strong>{{ $bookingDetails->tip?->tip }}</strong></span>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item">
                                                                        <div class="accordion-header"><button
                                                                                class="accordion-button" type="button"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#panelsStayOpen-collapseFour"
                                                                                aria-expanded="false"
                                                                                aria-controls="panelsStayOpen-collapseFour">Cleaner
                                                                                Rating &amp; Reviews</button></div>
                                                                        <div class="accordion-collapse collapse show"
                                                                            id="panelsStayOpen-collapseFour">
                                                                            <div class="accordion-body">
                                                                                <div class="review-people">
                                                                                    <ul
                                                                                        class="review-list custom-scrollbar">
                                                                                        <li>
                                                                                            <div class="people-box"><img
                                                                                                    class="img-fluid"
                                                                                                    src="assets/images/14.png"
                                                                                                    alt="">
                                                                                                <div
                                                                                                    class="people-comment">
                                                                                                    <div
                                                                                                        class="people-name">
                                                                                                        <a class="name"
                                                                                                            href="javascript:void(0)">{{ $bookingDetails->customer->name }}</a>
                                                                                                        <div
                                                                                                            class="date-time">
                                                                                                            <h6
                                                                                                                class="text-content">
                                                                                                                {{ $bookingDetails->service->name }}
                                                                                                            </h6>
                                                                                                            <div
                                                                                                                class="product-rating">
                                                                                                                <div
                                                                                                                    class="common-flex">
                                                                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                                                                        @if ($i <= round($bookingDetails->rating?->rating))
                                                                                                                            <i class="fa-solid fa-star txt-warning"></i>
                                                                                                                        @else
                                                                                                                            <i class="fa-regular fa-star txt-warning"></i>
                                                                                                                        @endif
                                                                                                                    @endfor
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p>{{ $bookingDetails->rating?->comment }}
                                                                                            </p>
                                                                                        </li>

                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card">
                                                <div class="card-body pt-0">
                                                    <div class="recent-activity notification">
                                                        <h5>Car wash Photos</h5>
                                                        <ul>
                                                            <li class="d-flex">
                                                                <div class="activity-dot-primary"></div>
                                                                <div class="w-100 ms-3">
                                                                    <p class="d-flex justify-content-between mb-2"><span
                                                                            class="date-content light-background">Before
                                                                            Wash
                                                                        </span></p>
                                                                    <div class="recent-images">
                                                                        <div class="avatars">
                                                                            @foreach($bookingDetails->beforePhoto as $image)
                                                                            <div class="avatar"><img
                                                                                    class="b-r-8 img-100" src="{{ getImageAdmin($image->photo_path) }}" alt="#">
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="d-flex">
                                                                <div class="activity-dot-primary"></div>
                                                                <div class="w-100 ms-3">
                                                                    <p class="d-flex justify-content-between mb-2"><span
                                                                            class="date-content light-background">After
                                                                            Wash
                                                                        </span></p>
                                                                    <div class="recent-images">
                                                                        <div class="avatars">
                                                                            
                                                                            @foreach($bookingDetails->afterPhoto as $image)
                                                                            <div class="avatar"><img
                                                                                    class="b-r-8 img-100" src="{{ getImageAdmin($image->photo_path) }}" alt="#">
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
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
                                        href="{{ route('bookings.invoice',$bookingDetails->id) }}" target="_blank"><i
                                            class="fa-regular fa-file-lines pe-2 f-14"></i>Invoice</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul class="tracking-total">
                                <li>
                                    <h6>Subtotal </h6><span> £
                                        {{ number_format($bookingDetails->gross_amount,2) }}</span>
                                </li>
                                <li>
                                    <h6>Coupon Discount</h6><span>(-){{ number_format($bookingDetails->discount_amount ?? 0 ,2) }}</span>
                                </li>
                                <li>
                                    <h6>Extra </h6><span class="txt-primary">0.00</span>
                                </li>
                                <li>
                                    <h6>Total</h6><span>£{{ number_format($bookingDetails->total_amount ?? 0 ,2) }}</span>
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