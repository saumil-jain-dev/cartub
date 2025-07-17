@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'User Management','url' => ''],
        ['label' => 'Users','url' => route('users.index')],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid">
    <div class="user-profile">
        <div class="row">
            <!-- user profile first-style start-->
            <!-- user profile first-style end-->
            <div class="col-12">
                <div class="card user-bio">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start pb-0">
                                    <h6><i class="fa-solid fa-user pe-2"></i>Name</h6>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-envelope pe-2"></i> Email</h6>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-phone pe-2"></i>Contact Us</h6><span>{{ $user->phone }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start pb-0">
                                    <h6><i class="fa-solid fa-calendar-check pe-2"></i>Total Bookings</h6>
                                    <span>{{ $user->booking_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- user profile menu start-->
            <div class="col-12">
                <div class="row scope-bottom-wrapper user-profile-wrapper">
                    <div class="col-xxl-3 user-xl-25 col-xl-4 box-col-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="sidebar-left-icons nav nav-pills" id="user-profile-pills-tab"
                                    role="tablist">
                                    <li class="nav-item"> <a class="nav-link active"
                                            id="wash-activity-tab" data-bs-toggle="pill"
                                            href="#wash-activity" role="tab"
                                            aria-controls="wash-activity" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i
                                                        class="fa-solid fa-timeline"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Wash Activity</h6>
                                            </div>
                                        </a></li>
                                    <li class="nav-item"> <a class="nav-link" id="booking-summary-tab"
                                            data-bs-toggle="pill" href="#booking-summary" role="tab"
                                            aria-controls="booking-summary" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i
                                                        class="fa-solid fa-list-check"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Booking Summary</h6>
                                            </div>
                                        </a></li>
                                    <li class="nav-item"><a class="nav-link" id="saved-vehicles-tab"
                                            data-bs-toggle="pill" href="#saved-vehicles" role="tab"
                                            aria-controls="saved-vehicles" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i
                                                        class="fa-regular fa-bell"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Saved Vehicles</h6>
                                            </div>
                                        </a></li>
                                    <li class="nav-item"><a class="nav-link" id="payment-history-tab"
                                            data-bs-toggle="pill" href="#payment-history" role="tab"
                                            aria-controls="payment-history" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i
                                                        class="fa-solid fa-gears"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Payment History</h6>
                                            </div>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-9 user-xl-75 col-xl-8 box-col-8e">
                        <div class="row">
                            <div class="col-12">
                                <div class="tab-content" id="user-profile-pills-tabContent">
                                    <div class="tab-pane fade show active" id="wash-activity"
                                        role="tabpanel" aria-labelledby="wash-activity-tab">
                                        <div class="notification">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Recent Wash Activity</h5>
                                                </div>
                                                <div class="card-body dark-timeline">
                                                    <ul>
                                                        @php
                                                            use Illuminate\Support\Carbon;
                                                        @endphp
                                                        @if(count($recentBooking) > 0)
                                                            @foreach ($recentBooking as $recent_booking)
                                                                <li class="d-flex">
                                                                <div class="activity-dot-primary"></div>
                                                                <div class="w-100 ms-3">
                                                                    <p
                                                                        class="d-flex justify-content-between mb-2">
                                                                        <span class="date-content light-background">
                                                                        
                                                                        {{ Carbon::parse($recent_booking->scheduled_date)->format('d M Y') }},
                                                                        {{ Carbon::parse($recent_booking->scheduled_time)->format('h:i A') }}
                                                                        {{-- </span><span>Today</span>   --}}
                                                                    </p>
                                                                    <h6>{{ $recent_booking->service_name }}<span
                                                                            class="dot-notification"></span>
                                                                    </h6>
                                                                    <div
                                                                        class="table-responsive custom-scrollbar">
                                                                        <table
                                                                            class="table border-bottom-table">
                                                                            <thead>
                                                                                <tr
                                                                                    class="border-bottom-primary">
                                                                                    <th scope="col">Number</th>
                                                                                    <th scope="col">Vehicle
                                                                                    </th>
                                                                                    <th scope="col">Address
                                                                                    </th>
                                                                                    <th scope="col">Payment
                                                                                    </th>
                                                                                    <th scope="col">Status
                                                                                    </th>
                                                                                    <th scope="col">Washed
                                                                                        by</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr
                                                                                    class="border-bottom-secondary">
                                                                                    <th scope="row">{{ $recent_booking->booking_number }}</th>
                                                                                    <td> {{ $recent_booking->vehicle?->model }} ({{ $recent_booking->vehicle?->license_plate }})</td>
                                                                                    <td>{{ $recent_booking->address }}</td>
                                                                                    <td>₹{{ $recent_booking->total_amount }} via {{ $recent_booking->payment?->payment_type ?? "-" }}</td>
                                                                                    <td> @php
                                                                                            if ($recent_booking->status === 'pending' && $recent_booking->cleaner_id) {
                                                                                                $badgeText = 'Assigned';
                                                                                                $badgeClass = 'badge-info';
                                                                                            } else {
                                                                                                switch ($recent_booking->status) {
                                                                                                    case 'pending':
                                                                                                        $badgeText = 'Pending';
                                                                                                        $badgeClass = 'badge-warning';
                                                                                                        break;
                                                                                                    case 'in_route':
                                                                                                        $badgeText = 'In Route';
                                                                                                        $badgeClass = 'badge-primary';
                                                                                                        break;
                                                                                                    case 'in_progress':
                                                                                                        $badgeText = 'In Progress';
                                                                                                        $badgeClass = 'badge-secondary';
                                                                                                        break;
                                                                                                    case 'completed':
                                                                                                        $badgeText = 'Completed';
                                                                                                        $badgeClass = 'badge-success';
                                                                                                        break;
                                                                                                    case 'cancelled':
                                                                                                        $badgeText = 'Cancelled';
                                                                                                        $badgeClass = 'badge-danger';
                                                                                                        break;
                                                                                                    default:
                                                                                                        $badgeText = ucfirst($recent_booking->status);
                                                                                                        $badgeClass = 'badge-dark';
                                                                                                }
                                                                                            }
                                                                                        @endphp

                                                                                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                                                                    </td>
                                                                                    <td> {{ $recent_booking->cleaner?->name ?? '-' }}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                                
                                                            
                                                        @else
                                                        <li class="d-flex">
                                                            <div class="w-100 ms-3">
                                                                <p>No recent wash found</p>
                                                            </div>
                                                        </li>
                                                        @endif
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="booking-summary" role="tabpanel"
                                        aria-labelledby="booking-summary-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Booking Summary</h5>
                                            </div>
                                            <div class="card-body projects-wrapper">
                                                <div class="row g-4">
                                                    <div class="col-12">
                                                        <div
                                                            class="table-responsive signal-table custom-scrollbar">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Booking ID</th>
                                                                        <th scope="col">Date & Time</th>
                                                                        <th scope="col">Wash Type
                                                                        </th>
                                                                        <th scope="col">Vehicle</th>
                                                                        <th scope="col">Amount</th>
                                                                        <th scope="col">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(count($bookings) > 0)
                                                                    @foreach ($bookings as $booking)
                                                                        
                                                                        <tr>
                                                                            <th scope="row">{{ $booking->booking_number }}</th>
                                                                            <td>{{ Carbon::parse($recent_booking->scheduled_date)->format('d M Y') }},
                                                                        {{ Carbon::parse($recent_booking->scheduled_time)->format('h:i A') }}</td>
                                                                            <td>{{ $booking->service_name }}</td>
                                                                            <td>{{ $booking->vehicle?->model ?? "-" }}</td>
                                                                            <td>₹{{ $booking->total_amount }}</td>
                                                                            <td>
                                                                                @php
                                                                                    if ($booking->status === 'pending' && $booking->cleaner_id) {
                                                                                        $badgeText = 'Assigned';
                                                                                        $badgeClass = 'badge-info';
                                                                                    } else {
                                                                                        switch ($booking->status) {
                                                                                            case 'pending':
                                                                                                $badgeText = 'Pending';
                                                                                                $badgeClass = 'badge-warning';
                                                                                                break;
                                                                                            case 'in_route':
                                                                                                $badgeText = 'In Route';
                                                                                                $badgeClass = 'badge-primary';
                                                                                                break;
                                                                                            case 'in_progress':
                                                                                                $badgeText = 'In Progress';
                                                                                                $badgeClass = 'badge-secondary';
                                                                                                break;
                                                                                            case 'completed':
                                                                                                $badgeText = 'Completed';
                                                                                                $badgeClass = 'badge-success';
                                                                                                break;
                                                                                            case 'cancelled':
                                                                                                $badgeText = 'Cancelled';
                                                                                                $badgeClass = 'badge-danger';
                                                                                                break;
                                                                                            default:
                                                                                                $badgeText = ucfirst($booking->status);
                                                                                                $badgeClass = 'badge-dark';
                                                                                        }
                                                                                    }
                                                                                @endphp

                                                                                <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td colspan="6">No data found</td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="saved-vehicles" role="tabpanel"
                                        aria-labelledby="saved-vehicles-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Saved Vehicles</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-4">
                                                    <div class="col-12 ">
                                                        <div
                                                            class="table-responsive signal-table custom-scrollbar">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Vehicle</th>
                                                                        <th scope="col">Number Plate
                                                                        </th>
                                                                        <th scope="col">Color</th>
                                                                        <th scope="col">Mfg Year</th>
                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(count($vehicles) > 0)
                                                                    @foreach ($vehicles as $vehicle)
                                                                        
                                                                        <tr>
                                                                            <td>{{ $vehicle->model }}</td>
                                                                            <td>{{ $vehicle->license_plate }}</td>
                                                                            <td>{{ $vehicle->color }}</td>
                                                                            <td>{{ $vehicle->year }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td colspan="4">No record found</td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="payment-history" role="tabpanel"
                                        aria-labelledby="payment-history-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Settings</h5>
                                            </div>
                                            <div class="card-body setting-wrapper">
                                                <div class="row g-4">
                                                    <div class="col-12">
                                                        <div
                                                            class="table-responsive signal-table custom-scrollbar">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Transaction ID
                                                                        </th>
                                                                        <th scope="col">Date & Time</th>
                                                                        <th scope="col">Amount</th>
                                                                        <th scope="col">Method</th>
                                                                        <th scope="col">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    
                                                                    @if(count($bookings) > 0 )
                                                                        @foreach($bookings as $booking)
                                                                        <tr>
                                                                            <th scope="row">{{ $booking->payment?->transaction_id }}</th>
                                                                            <td>{{ Carbon::parse($booking->payment?->created_at)->format('d M Y, H:i A') }}</td>
                                                                            <td>₹{{ $booking->total_amount }}</td>
                                                                            <td>{{ $booking->payment?->payment_type }}</td>
                                                                            <td><span
                                                                                    class="badge badge-light-success">{{ ucfirst($booking->payment_status) }}</span>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td colspan="5">No record found</td>
                                                                    </tr>
                                                                    @endif
                                                                    
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
                        </div>
                    </div>
                </div><!-- user profile menu end-->
            </div>
        </div>
    </div><!-- Container-fluid Ends-->
</div>
@endsection