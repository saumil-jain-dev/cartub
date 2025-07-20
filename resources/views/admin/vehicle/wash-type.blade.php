@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Vehicle Management','url' => ''],
        ['label' => 'Vehicle Service','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid e-category">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    @if(hasPermission('vehicle.wash-type-create'))
                    <div class="card-header-right-icon"><a class="btn btn-primary f-w-500 add-wash-type" href="javascript:void(0)"><i
                                class="fa-solid fa-plus pe-2"></i>Add Wash Type</a>
                        
                    </div>
                    @endif
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="list-product list-category">
                        <div class="recent-table table-responsive custom-scrollbar">
                            <table class="table" id="wash-type-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        
                                        <th>Type Name</th>
                                        <th>Description</th>
                                        <th>Duration (Minutes)</th>
                                        <th>Price ($)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Row 1 -->
                                    @foreach($wash_types as $wash_type)
                                    <tr>
                                        <td></td>
                                        
                                        <td>{{ $wash_type->name }}</td>
                                        <td>{{ $wash_type->description }}</td>
                                        <td>{{ $wash_type->duration_minutes }}</td>
                                        <td>{{ $wash_type->price }}</td>
                                        <td>
                                            <span class="badge {{ $wash_type->is_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $wash_type->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                        </td>
                                        <td>
                                            <div class="common-align gap-2 justify-content-start">
                                                @if (hasPermission('vehicle.wash-types-edit'))
                                                    
                                                    <a class="square-white edit-wash-type"
                                                        href="javascript:void(0)"
                                                        data-id="{{ $wash_type->id }}"
                                                        data-name="{{ $wash_type->name }}"
                                                        data-description="{{ $wash_type->description }}"
                                                        data-duration="{{ $wash_type->duration_minutes }}"
                                                        data-price="{{ $wash_type->price }}"
                                                        data-status="{{ $wash_type->is_active }}">
                                                            <svg>
                                                                <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}"></use>
                                                            </svg>
                                                    </a>
                                                @endif
                                                @if (hasPermission('vehicle.wash-types-destroy'))
                                                    
                                                    <a class="square-white trash-3 delete-wash-type" href="javascript:void(0);" data-id="{{ $wash_type->id }}">
                                                        <svg>
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
    <!-- Add Wash Type Modal -->
    <div class="modal fade" id="addWashTypeModal" tabindex="-1" aria-labelledby="addWashTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addWashTypeForm" method="POST" action="{{ route('vehicle.wash-types-store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="addWashTypeModalLabel">Add Wash Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (minutes) *</label>
                    <input type="number" class="form-control" name="duration" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price *</label>
                    <input type="number" class="form-control" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editWashTypeModal" tabindex="-1" aria-labelledby="editWashTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editWashTypeForm" method="POST" action="{{ route('vehicle.wash-types-edit') }}">
            @csrf
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editWashTypeModalLabel">Edit Wash Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Name *</label>
                    <input type="text" class="form-control" name="name" id="edit_name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="edit_description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="edit_duration" class="form-label">Duration *</label>
                    <input type="number" class="form-control" name="duration" id="edit_duration" required>
                </div>
                <div class="mb-3">
                    <label for="edit_price" class="form-label">Price *</label>
                    <input type="number" class="form-control" name="price" id="edit_price" required>
                </div>
                <div class="mb-3">
                    <label for="edit_status" class="form-label">Status</label>
                    <select class="form-control" name="status" id="edit_status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    //Add Wash type
    $(document).ready(function() {
        $('.add-wash-type').on('click', function() {
            $('#addWashTypeModal').modal('show');
        });
    });

    $('#addWashTypeForm').validate({
        rules: {
            name: {
                required: true
            },
            duration: {
                required: true,
                number: true
            },
            price: {
                required: true,
                number: true
            }
        },
        messages: {
            name: "Name is required",
            duration: {
                required: "Duration is required",
                number: "Duration must be a number"
            },
            price: {
                required: "Price is required",
                number: "Price must be a number"
            }
        },
        errorClass: 'text-danger',
        errorElement: 'small'
    });

    // Delete Wash Type
    $(document).on('click', '.delete-wash-type', function() {
        const roleId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the wash type and all its permissions.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${site_url}/admin/vehicle/wash-type/destroy/${roleId}`,
                    type: 'GET',
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

    //edit Wash Type
    $(document).ready(function() {
        $('.edit-wash-type').on('click', function() {
            const btn = $(this);
            $('#edit_id').val(btn.data('id'));
            $('#edit_name').val(btn.data('name'));
            $('#edit_description').val(btn.data('description'));
            $('#edit_duration').val(btn.data('duration'));
            $('#edit_price').val(btn.data('price'));
            $('#edit_status').val(btn.data('status') ?? 0);

            $('#editWashTypeModal').modal('show');
        });
    });

    $('#editWashTypeForm').validate({
        rules: {
            name: { required: true },
            duration: { required: true, number: true },
            price: { required: true, number: true }
        },
        messages: {
            name: "Name is required",
            duration: { required: "Duration is required", number: "Must be a number" },
            price: { required: "Price is required", number: "Must be a number" }
        },
        errorClass: 'text-danger',
        errorElement: 'small'
    });
</script>
@endsection