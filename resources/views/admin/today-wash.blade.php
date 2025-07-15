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
<div class="container-fluid common-order-history">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('bookings.index') }}">
                        <div class="row g-3 custom-input">
                           
                            <div class="col-xl col-md-6"><label class="form-label">Payment
                                    Status</label><select class="form-select" name="payment_status">
                                    <option value="">Select Payment Status</option>
                                    <option value="paid" {{ request()->input('payment_status') == 'paid' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ request()->input('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select></div>
                            <div class="col-xl col-md-6"><label class="form-label">Payment
                                    Methods</label><select class="form-select" name="payment_method">
                                    <option value="">Select Payment</option>
                                    <option value="apple_pay" {{ request()->input('payment_method') == 'apple_pay' ? 'selected' : '' }}>Apple Pay</option>
                                    <option value="google_pay" {{ request()->input('payment_method') == 'google_pay' ? 'selected' : '' }}>Google Pay</option>
                                    <option value="card" {{ request()->input('payment_method') == 'card' ? 'selected' : '' }}>Credit Card</option>
                                </select></div>
                                <div class="col d-flex justify-content-start align-items-center m-t-40">
                                    <button type="submit" class="btn btn-primary f-w-500">Submit</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card heading-space">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5>New Orders</h5>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="order-history-wrapper">
                                <div class="recent-table table-responsive custom-scrollbar">
                                    <table class="table" id="order-history-table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th> <span class="f-light f-w-600">Order Number</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Order Date</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Customer Name</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Total Amount</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Payment Status</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Payment Method</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Booking Status</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookingData as $booking)
                                            <tr class="inbox-data">
                                                <td></td>
                                                <td> <a href="javascript:void(0)">{{ $booking->booking_number }}</a></td>
                                                <td>
                                                    <p class="c-o-light">{{ $booking->created_at->format('d M Y h:i:A') }}</p>
                                                </td>
                                                <td>
                                                    <p class="c-o-light">{{ optional($booking->customer)->name ?? '-' }}</p>
                                                </td>
                                                <td>
                                                    <p class="c-o-light">${{ $booking->total_amount }} </p>
                                                </td>
                                                <td>
                                                    @if ($booking->payment_status === 'pending')
                                                        <span class="badge badge-light-warning">Pending</span>
                                                    @elseif ($booking->payment_status === 'failed')
                                                        <span class="badge badge-light-danger">Failed</span>
                                                    @elseif ($booking->payment_status === 'paid')
                                                        <span class="badge badge-light-success">Completed</span>
                                                    @else
                                                        <span class="badge badge-light-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="c-o-light">{{ optional($booking->payment)->payment_type ?? '-' }}</p>
                                                </td>
                                                
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
                                                <td>
                                                    <div
                                                        class="common-align gap-2 justify-content-start">
                                                        @if(hasPermission('bookings.show'))
                                                        <a class="square-white"
                                                            href="{{ route('bookings.show',$booking->id) }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="View"><svg>
                                                                <use
                                                                    href="{{ asset('assets/svg/icon-sprite.svg#fill-view') }}">
                                                                </use>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        @if(hasPermission('bookings.destroy'))
                                                            <a class="square-white trash-3 delete-booking"
                                                            href="javascript:void(0)" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="Delete"
                                                            data-id="{{ $booking->id }}"
                                                            >
                                                            <svg>
                                                                <use
                                                                    href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}">
                                                                </use>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
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
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).on('click', '.delete-booking', function () {
        const bookingId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the booking and all its details.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${site_url}/admin/bookings/${bookingId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>
@endsection