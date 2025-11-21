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
<div class="container-fluid user-list-wrapper">
    <div class="row">
        
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon"><a class="btn btn-primary f-w-500" href="#"><i
                                class="fa-solid fa-plus pe-2"></i>Add User</a></div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="api-manage-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th> <span class="c-o-light f-w-600">Customer Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Cleaner Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Review</span></th>
                                        <th> <span class="c-o-light f-w-600">Date</span></th>
                                        <th> <span class="c-o-light f-w-600">Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        use Illuminate\Support\Carbon;
                                    @endphp
                                    @foreach($ratings as $rating)
                                    <tr class="user-row">
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
                                        <td>
                                            <div class="common-align gap-2 justify-content-start">
                                                
                                                <a class="square-white trash-7 delete-feedback" href="javascript:void(0)" data-bs-title="Delete" data-id="{{ $rating->id }}"><svg>
                                                        <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                                
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
@endsection
@section('scripts')
<script>
$(document).on('click', '.delete-feedback', function () {
    const ratingId = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the customer ratting and feedback.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${site_url}/admin/customer-feedback/${ratingId}`,
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