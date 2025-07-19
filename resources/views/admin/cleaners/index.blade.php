@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'User Management','url' => ''],
        ['label' => 'Cleaners','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid candidate-wrapper">
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('cleaners.index') }}">
                        <div class="row g-3">
                            <div class="col-xl col-md-4 col-sm-6"><label
                                    class="form-label">Availability</label><select class="form-select"
                                    aria-label="Select parent category" name="availability">
                                    <option value="">Select Availability</option>
                                    <option value="1" {{ request()->input('availability') == '1' ? 'selected' : '' }}>Available</option>
                                    <option value="0" {{ request()->input('availability') == '0' ? 'selected' : '' }}>Not Available</option>
                                </select></div>
                            {{-- <div class="col-xl col-md-4 col-sm-6"><label
                                    class="form-label">Rating</label><select class="form-select"
                                    aria-label="Select parent category" name="ratting">
                                    <option value="">Select Rating</option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select></div> --}}
                            <div class="col-xl col-md-4 col-sm-6"><label class="form-label">Jobs
                                    Completed</label><select class="form-select"
                                    aria-label="Select your experience" name="jobs_completed">
                                    <option value="">Select Jobs Completed</option>
                                    <option value="1" {{ request()->input('jobs_completed') == '1' ? 'selected' : '' }}> 0-50</option>
                                    <option value="2" {{ request()->input('jobs_completed') == '2' ? 'selected' : '' }}>51-100</option>
                                    <option value="3" {{ request()->input('jobs_completed') == '3' ? 'selected' : '' }}>100-200</option>
                                </select></div>
                            <div class="col common-f-start"><button type="submit" class="btn btn-primary f-w-500">Filter</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body candidates-box px-0">
                    <div class="table-responsive custom-scrollbar">
                        <table class="table" id="candidates-table">
                            <thead>
                                <tr>
                                    <th> </th>
                                    <th> Photo / Name</th>
                                    <th> Phone</th>
                                    <th> Email</th>
                                    <th> Availability</th>
                                    <th> Status</th>
                                    <th> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cleaners as $cleaner)
                                    
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="common-flex align-items-center">
                                                <div class="position-relative">
                                                    <img class="img-fluid rounded-circle"
                                                        src="{{ $cleaner->profile_image_url }}" alt="user">
                                                    <div class="status">
                                                        @if($cleaner->is_available)
                                                        <div class="inner-dot bg-success"></div>
                                                        @else
                                                        <div class="inner-dot bg-danger"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div><a class="f-w-500" href="javascript:void(0)">{{ $cleaner->name }}</a>
                                                   
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>{{ $cleaner->phone }}</td>
                                        <td>{{ $cleaner->email }}</td>
                                        
                                        {{-- <td>
                                            <div class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= round($cleaner->average_rating))
                                                        <i class="fa-solid fa-star txt-warning"></i>
                                                    @else
                                                        <i class="fa-regular fa-star txt-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td> --}}
                                        <td>{{ $cleaner->is_available ? "Available" : "Not Available" }}</td>
                                        <td>
                                            <span class="badge {{ $cleaner->is_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $cleaner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                
                                                @if(hasPermission('cleaners.edit'))
                                                    <a href="{{ route('cleaners.edit', $cleaner->id) }}" 
                                                    class="btn btn-success" 
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-placement="top" 
                                                    data-bs-title="Approve">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                @endif
                                                @if(hasPermission('cleaners.destroy')) <button class="btn btn-danger delete-user" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reject" data-id="{{ $cleaner->id }}"><i class="fa-solid fa-circle-xmark"> </i></button>@endif
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
@endsection
@section('scripts')
<script>
    $(document).on('click', '.delete-user', function () {
        const bookingId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the cleaner and all its details.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${site_url}/admin/cleaners/${bookingId}`,
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