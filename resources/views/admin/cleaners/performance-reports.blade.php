@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
    @include('admin.components.breadcrumb', [
        'title' => $pageTitle,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
            ['label' => 'User Management', 'url' => ''],
            ['label' => 'Cleaners', 'url' => ''],
            ['label' => $pageTitle] // Last item, no URL
        ]
    ])
    <div class="container-fluid customer-order-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2">
                                <div class="col-auto"><label class="form-label"></label></div>
                                <div class="col-auto">
                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="customer-order-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="customer-order-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th> <span class="c-o-light f-w-600">Cleaner Name</span></th>
                                            <th> <span class="c-o-light f-w-600">Total Jobs Completed
                                                </span></th>
    <th> <span class="c-o-light f-w-600">Monthly Average Jobs</span>
                                            </th>
                                            <th> <span class="c-o-light f-w-600">Customer Rating
                                                    (Avg.)</span></th>
                                            </th>
                                        <th> <span class="c-o-light f-w-600">Total Earnings</span></th>
                                        <th> <span class="c-o-light f-w-600">Total Tips Earned</span>
                                        </th>


                                                                                       </tr>

                                                                                       </thead>
                                <tbody>
                                    @foreach ($cleaners as $cleaner)
                                        <tr class="product-removes inbox-data">
                                            <td></td>
                                            <td>
                                                <div class="customer-details"><img class="{{ $cleaner->profile_image_url }}" alt="user">
                                                    <div><a href="{{ route('cleaners.edit', $cleaner->id) }}">{{ $cleaner->name }}</a>
                                                        <p class="c-o-light">{{ $cleaner->email }}</p>
                                                    </div>
                                                    </div>
                                                </td>
                                                <td>{{ $cleaner->completed_job_count }}</td>
                                                <td>{{ $cleaner->monthly_average_completed_bookings }}</td>
                                                <td><i class="fa-solid fa-star txt-warning"></i> {{ number_format($cleaner->average_rating, 2) }} / 5</td>
                                                <td>£{{ $cleaner->total_earned }}</td>
                                                <td>£{{ $cleaner->total_tip_earned }}</td>
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