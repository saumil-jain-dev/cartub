@extends('admin.layouts.app')
@section('pageTitle', 'Role Permission')
@section('content')
@include('admin.components.breadcrumb', [
    'title' => 'Roles & Permission',
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Roles & Permission'] // Last item, no URL
    ]
])
<div class="container-fluid role-permission-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon">
                        <a class="btn btn-primary f-w-500" href="{{ route('roles-permission.create') }}"
                            data-bs-toggle="modal" data-bs-target="#rolePermission" id="addRoleBtn"><i
                            class="fa-solid fa-plus pe-2"></i>Add Permission
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product permission-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="roles-permission">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th> <span class="c-o-light f-w-600">Role Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Creation Date</span></th>
                                        <th> <span class="c-o-light f-w-600">Last Updated Date</span>
                                        </th>
                                        <th> <span class="c-o-light f-w-600">Status</span></th>
                                        <th> <span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roleData as $role)
                                        
                                        <tr class="product-removes inbox-data">
                                            <td></td>
                                            <td>
                                                <p>{{ ucfirst($role->name) }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $role->created_at->format('d M Y, h:i A') }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $role->updated_at->format('d M Y, h:i A') }}</p>
                                            </td>
                                            <td>
                                                <span class="badge {{ $role->status ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $role->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="common-align gap-2 justify-content-start">
                                                    <a class="square-white edit-role" href="#!" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}" data-role-status="{{ $role->status }}" data-permissions='@json($role->permissions->pluck("name"))'>
                                                        <svg>
                                                            <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                            </use>
                                                        </svg>
                                                    </a>
                                                    <a class="square-white trash-3" href="#!">
                                                        <svg>
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
    <div class="modal fade" id="rolePermission" tabindex="-1" aria-labelledby="rolePermission" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content category-popup">
                <div class="modal-header">
                    <h5 class="modal-title" id="modaldashboard">Create Role</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body custom-input">
                    @php
                        $modules = config('modules');
                    @endphp
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ route('roles-permission.store') }}" id="rolesubmit">
                                <div class="mb-lg-3 row mb-4 g-lg-3 g-2">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0" for="validationName">Name <span
                                                class="txt-danger">*</span></label>
                                    </div>
                                    @csrf
                                    <input type="hidden" name="id" id="role_id" value="">
                                    <div class="col-md-10">
                                        <input class="form-control" id="role_name" type="text"
                                            placeholder="Enter name"  name="role_name">
                                        
                                    </div>
                                    <div class="mb-lg-3 row mb-4 g-lg-3 g-2">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0" for="role_status">Status <span class="txt-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-select" name="status" id="role_status" required>
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="row g-lg-3 g-2">
                                    <div class="col-md-12">
                                        <label class="form-label mb-0" for="validationName">Permissions
                                            <span class="txt-danger">*</span></label>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="row permission-form g-2">
                                            @foreach ($modules as $module => $details)
                                                <div class="col-12">
                                                    <ul>
                                                        <li>{{ $details['name'] }}</li>
                                                        <li>
                                                            <div class="form-check">
                                                                <input class="form-check-input check-all all-permission" data-module="{{ $module }}"
                                                                    type="checkbox" >
                                                                <label class="form-check-label"
                                                                    for="flexCheckDefault">All</label>
                                                            </div>
                                                        </li>
                                                        @foreach ($details['actions'] as $action)
                                                            <li>
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-{{ $module }}"
                                                                        id="flexCheckDefault1" type="checkbox" name="permissions[]"
                                                                        value="{{ $module . '.' . $action }}"
                                                                    >
                                                                    <label class="form-check-label"
                                                                        for="flexCheckDefault1">@if($action == 'index') List @else{{ ucfirst($action) }}@endif</label>
                                                                </div>
                                                            </li>
                                                            
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <input class="btn btn-primary" type="submit"  value="Save"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('#rolesubmit').validate({
            rules: {
                role_name: {
                    required: true
                }
            },
            messages: {
                role_name: {
                    required: "Please enter a role name"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    // Handle "All" checkbox click
    $(document).ready(function () {
        $('.all-permission').on('change', function () {
            let module = $(this).data('module');
            let isChecked = $(this).is(':checked');
            $('.permission-' + module).prop('checked', isChecked);
        });

        // Handle individual permission uncheck → uncheck "All"
        $('input[type="checkbox"][name="permissions[]"]').on('change', function () {
            let classes = $(this).attr('class'); // e.g. permission-users
            let module = classes.split('-')[1];
            let allPermissions = $('.permission-' + module);
            let allChecked = allPermissions.length === allPermissions.filter(':checked').length;

            $('.all-permission[data-module="' + module + '"]').prop('checked', allChecked);
        });
    });

    //edit code
    $(document).ready(function () {
        $('.edit-role').on('click', function () {
            let roleId = $(this).data('role-id');
            let roleName = $(this).data('role-name');
            const roleStatus = $(this).data('role-status');
            let permissions = $(this).data('permissions'); // Array of permission strings

            // Reset form
            $('#rolesubmit')[0].reset();
            $('.all-permission').prop('checked', false);
            $('input[name="permissions[]"]').prop('checked', false);

            // Set form action to update route if needed
            // $('#rolesubmit').attr('action', `/roles-permission/${roleId}`);
            // $('#rolesubmit').append('<input type="hidden" name="_method" value="PUT">');

            // Fill role name
            $('#role_id').val(roleId);
            $('#role_name').val(roleName);
            $('#role_status').val(roleStatus);

            

            // Check permissions
            if (permissions && permissions.length) {
                permissions.forEach(function (perm) {
                    $('input[name="permissions[]"][value="' + perm + '"]').prop('checked', true);
                });
            }

            // Re-check "All" checkboxes if needed
            $('.all-permission').each(function () {
                let module = $(this).data('module');
                let allChecked = $('.permission-' + module).length === $('.permission-' + module + ':checked').length;
                $(this).prop('checked', allChecked);
            });

            // Open modal
            $('#rolePermission').modal('show');
        });

        // Reset form to create when opening modal manually
        $('#addRoleBtn').on('click', function () {
            $('#rolesubmit')[0].reset();
            $('#role_status').val('1');
            $('#role_id').val('');
            $('.all-permission').prop('checked', false);
            $('input[name="permissions[]"]').prop('checked', false);
        });
    });

</script>
@endsection