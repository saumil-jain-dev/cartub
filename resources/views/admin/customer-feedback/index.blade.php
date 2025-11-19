@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Feedback & Support','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid manage-review-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body px-0 pt-0">
                    <div class="top-body">
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="form-group"><label class="form-label">
                                </label></div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group"><label class="form-label"></label></div>
                            </div>
                        </div>
                    </div>
                    <div class="manage-review">
                        <div class="recent-table table-responsive custom-scrollbar">
                            <table class="table" id="customer-feedback-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th> <span class="c-o-light f-w-600">Customer Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Cleaner Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Review</span></th>
                                        <th> <span class="c-o-light f-w-600">Date</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        use Illuminate\Support\Carbon;
                                    @endphp
                                    @foreach($ratings as $rating)
                                    <tr class="product-removes inbox-data">
                                        <td></td>
                                        <td></td>
                                        <td>{{ $rating->customer?->name }}</td>
                                        <td>
                                            <div class="product-names">
                                                
                                                <p>{{ $rating->cleaner?->name }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= round($rating->rating))
                                                        <i class="fa-solid fa-star txt-warning"></i>
                                                    @else
                                                        <i class="fa-regular fa-star txt-warning"></i>
                                                    @endif
                                                @endfor

                                            </div>
                                            <div class="customer-review">
                                                <span>{{ $rating->comment }}</span>
                                            </div>
                                        </td>
                                        <td>{{ Carbon::parse($rating->created_at)->format('d M Y, H:i A') }}</td>

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
