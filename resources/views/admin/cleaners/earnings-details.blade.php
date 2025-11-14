@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
    @include('admin.components.breadcrumb', [
        'title' => $pageTitle,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
            ['label' => 'User Management', 'url' => ''],
            ['label' => 'Cleaners', 'url' => route('cleaners.index')],
            ['label' => 'Performance Reports', 'url' => route('cleaners.performance-reports')],
            ['label' => $pageTitle] // Last item, no URL
        ]
    ])
    <div class="container-fluid customer-order-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>{{ $cleaner->name }} - Earnings Details</h5>
                            <a href="{{ route('cleaners.performance-reports') }}" class="btn btn-primary">Back to Performance Reports</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body bg-light-primary">
                                        <h6 class="mb-2">Total Earnings</h6>
                                        <h3 class="mb-0">£{{ number_format($totalEarnings, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body bg-light-success">
                                        <h6 class="mb-2">Total Tips</h6>
                                        <h3 class="mb-0">£{{ number_format($totalTips, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body bg-light-info">
                                        <h6 class="mb-2">Total Amount</h6>
                                        <h3 class="mb-0">£{{ number_format($totalAmount, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="customer-order-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="earnings-details-table">
                                    <thead>
                                        <tr>
                                            <th><span class="c-o-light f-w-600">Date</span></th>
                                            <th><span class="c-o-light f-w-600">Booking ID</span></th>
                                            <th><span class="c-o-light f-w-600">Service</span></th>
                                            <th><span class="c-o-light f-w-600">Wash Time</span></th>
                                            <th><span class="c-o-light f-w-600">Amount</span></th>
                                            <th><span class="c-o-light f-w-600">Tip</span></th>
                                            <th><span class="c-o-light f-w-600">Total</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($earnings as $earning)
                                            <tr class="product-removes inbox-data">
                                                <td>{{ \Carbon\Carbon::parse($earning->earned_on)->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('bookings.show', $earning->booking->id) }}">
                                                        {{ $earning->booking->booking_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $earning->booking->service->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if($earning->booking->job_start_time && $earning->booking->job_end_time)
                                                        {{ \Carbon\Carbon::parse($earning->booking->job_start_time)->format('H:i') }} -
                                                        {{ \Carbon\Carbon::parse($earning->booking->job_end_time)->format('H:i') }}
                                                        ({{ $earning->booking->job_duration ?? 0 }} mins)
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>£{{ number_format($earning->amount, 2) }}</td>
                                                <td>£{{ number_format($earning->tip, 2) }}</td>
                                                <td>£{{ number_format($earning->amount + $earning->tip, 2) }}</td>
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
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#earnings-details-table').DataTable({
            order: [[0, 'desc']],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection
