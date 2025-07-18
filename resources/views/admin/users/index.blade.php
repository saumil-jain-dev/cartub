@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'User Management','url' => ''],
        ['label' => 'Users','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}">
                        <div class="row g-3">
                            <div class="col-xl col-md-4 col-sm-6"><label class="form-label">Customer
                                    Vechile</label>
                                <select class="form-select" aria-label="Select parent category" name="vehicle_count">
                                    <option value="">Select Count</option>
                                    <option value="1" {{ request()->input('vehicle_count') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ request()->input('vehicle_count') == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ request()->input('vehicle_count') == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ request()->input('vehicle_count') == '4' ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ request()->input('vehicle_count') == '5' ? 'selected' : '' }}>5</option>
                                </select>
                            </div>
                            <div class="col d-flex justify-content-start align-items-center m-t-40">
                                <button type="submit" class="btn btn-primary f-w-500">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon"><a class="btn btn-primary f-w-500" href="#"><i
                                class="fa-solid fa-plus pe-2"></i>Add User</a></div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="roles-permission">
                                <thead>
                                    <tr>
                                        <th></th>
                                        
                                        <th> <span class="c-o-light f-w-600">Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Phone No.</span></th>
                                        <th> <span class="c-o-light f-w-600">Email</span></th>
                                        <th> <span class="c-o-light f-w-600">Total Bookings</span></th>
                                        <th> <span class="c-o-light f-w-600">Creation Date</span></th>
                                        <th> <span class="c-o-light f-w-600">Status</span></th>
                                        <th> <span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr class="user-row">
                                        <td></td>
                                        
                                        <td><a href="{{ route('users.profile',$user->id) }}">{{ $user->name }}</a></td>
                                        <td>
                                            <p>{{ $user->phone }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $user->email }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $user->booking_count }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $user->created_at->format('d M Y, h:i A') }}</p>
                                        </td>
                                        <td><span class="badge {{ $user->is_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                </span></td>
                                        <td>
                                            <div class="common-align gap-2 justify-content-start">
                                                @if(hasPermission('users.edit'))
                                                <a class="square-white edit-user" href="javascript:void(0)"data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-phone="{{ $user->phone }}"
                                                    data-status="{{ $user->is_active }}"><svg>
                                                        <use
                                                            href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                                @endif
                                                @if(hasPermission('users.destroy'))
                                                <a class="square-white trash-7 delete-user" href="javascript:void(0)" data-bs-title="Delete" data-id="{{ $user->id }}"><svg>
                                                        <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}">
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
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.update') }}" id="editUserForm">
                @csrf
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="editUserName" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" id="editUserPhone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="editUserStatus" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
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
            text: "This will delete the user and all its details.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${site_url}/admin/users/${bookingId}`,
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

    $(document).ready(function () {
        const modal = new bootstrap.Modal($('#editUserModal')[0]);

        $('.edit-user').on('click', function () {
            $('#editUserId').val($(this).data('id'));
            $('#editUserName').val($(this).data('name'));
            $('#editUserEmail').val($(this).data('email'));
            $('#editUserPhone').val($(this).data('phone'));
            $('#editUserStatus').val($(this).data('status'));

            modal.show();
        });

        $('#editUserForm').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 255
                },
                phone: {
                    required: true,
                    maxlength: 10
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Name is required"
                },
                email: {
                    required: "Email is required",
                    email: "Enter a valid email"
                },
                status: {
                    required: "Status is required"
                },
                phone: {
                    required: "Phone is required"
                },
            },
            errorClass: 'text-danger',
            errorElement: 'small',
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection