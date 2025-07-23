@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Payments & Transactions','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid common-order-history">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('payment.index') }}">
                        <div class="row g-3 custom-input">
                            <div class="col-xl col-md-6"> <label class="form-label"
                                    for="datetime-local">From: </label>
                                <div class="input-group flatpicker-calender"><input class="form-control"
                                        id="datetime-local" placeholder="dd/mm/yyyy" name="from_date" value="{{ request()->input('from_date') }}"></div>
                            </div>
                            <div class="col-xl col-md-6"> <label class="form-label"
                                    for="datetime-local3">To: </label>
                                <div class="input-group flatpicker-calender"><input class="form-control"
                                        id="datetime-local3" placeholder="dd/mm/yyyy" name="to_date" value="{{ request()->input('to_date') }}"></div>
                            </div>
                            <div class="col-xl col-md-6"><label class="form-label">Payment
                                        Status</label><select class="form-select" name="status">
                                        <option value="">Select Payment Status</option>
                                        <option value="paid" {{ request()->input('status') == 'paid' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ request()->input('status') == 'failed' ? 'selected' : '' }}>Failed</option>
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
                    </div>
                </form>
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
                                                <th> <span class="f-light f-w-600">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                use Illuminate\Support\Carbon;
                                            @endphp
                                            @foreach($payments as $payment)
                                            <tr class="inbox-data">
                                                <td></td>
                                                <td> <a href="{{ route('bookings.show',$payment->booking_id) }}">{{ $payment->bookings?->booking_number }}</a></td>
                                                <td>
                                                    <p class="c-o-light">{{ Carbon::parse($payment->created_at)->format('d M Y, H:i A') }}</p>
                                                </td>
                                                <td>
                                                    <p class="c-o-light">{{ $payment->bookings?->customer?->name }}</p>
                                                </td>
                                                <td>
                                                    <p class="c-o-light">${{ $payment->amount }} </p>
                                                </td>
                                                <td>
                                                    @if ($payment->status === 'pending')
                                                        <span class="badge badge-light-warning">Pending</span>
                                                    @elseif ($payment->status === 'failed')
                                                        <span class="badge badge-light-danger">Failed</span>
                                                    @elseif ($payment->status === 'paid')
                                                        <span class="badge badge-light-success">Completed</span>
                                                    @else
                                                        <span class="badge badge-light-secondary">{{ ucfirst($payment->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="c-o-light">{{ $payment->payment_type }}</p>
                                                </td>
                                                <td>
                                                    <div
                                                        class="common-align gap-2 justify-content-start">
                                                        <a class="square-white"
                                                            href="{{ route('bookings.show',$payment->booking_id) }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="View"><svg>
                                                                <use
                                                                    href="{{ asset('assets/svg/icon-sprite.svg#fill-view') }}">
                                                                </use>
                                                            </svg></a>
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