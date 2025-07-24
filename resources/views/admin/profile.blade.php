@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'User Management','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Admin Profile</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="javascript:void(0)" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <form class="custom-input" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            <div class="row mb-2">
                                <div class="profile-title">
                                    <div class="d-flex justify-content-center">
                                        <img class="img-100 b-r-8" src="{{ getImageAdmin($adminUser->profile_picture) }}" alt="#">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3"> <label class="form-label"
                                    for="formFile1">Choose
                                    Profile Picture</label><input class="form-control"
                                    id="formFile1" type="file" aria-label="file example" name="profile_picture">
                                    @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                <div class="invalid-feedback">Invalid form file selected</div>
                            </div>
                            
                            <div class="col-12 mb-3"><label class="form-label"
                                    for="customLastname">
                                    Name</label><input class="form-control" id="customLastname"
                                    type="text" placeholder="Enter last name" value ="{{  $adminUser->name }}" name="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-12 mb-3"><label class="form-label"
                                    for="customEmail">Email</label><input class="form-control"
                                    id="customEmail" type="email"
                                    placeholder="pixelstrap@example.com" name="email" value="{{ $adminUser->email }}">
                                     @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-12 mb-3"><label class="form-label"
                                    for="customContact1">Contact Number</label><input
                                    class="form-control" id="customContact1" type="number"
                                    placeholder="Enter number" name="phone" value="{{ $adminUser->phone }}" >
                                     @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-12 mt-3 text-center">
                                <div class="text-end"><input class="btn btn-primary"
                                        type="submit" value="Update Profile"></div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <form class="card" method="POST" action="{{ route('profile.change-password') }}" id="changePassword">
                    @csrf
                    <div class="card-header">
                        <h5 class="card-title">Change Password</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                            data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                            class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row custom-input">
                            <div class="col-lg-4 mb-3 position-relative"><label class="col-form-label"
                                    for="inputTooltipPassword">Old Password</label><input
                                    class="form-control required" id="inputTooltipPassword"
                                    type="password" placeholder="Enter your Old password" name="old_password">
                                </div>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            <div class="col-lg-4 mb-3 position-relative"><label class="col-form-label"
                                    for="inputTooltipPassword">New Password</label><input
                                    class="form-control required" id="new_password"
                                    type="password" placeholder="Enter your New password" name="new_password">
                                </div>
                                @error('new_password')
                                   <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                            <div class="col-lg-4 mb-3 position-relative"><label class="col-form-label"
                                    for="inputConfirmPassword">Confirm Password</label><input
                                    class="form-control required confirmation" id="inputConfirmPassword"
                                    type="password" placeholder="Enter your confirm password" name="cpassword"
                                    >
                                </div>
                                @error('cpassword')
                                   <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                        </div>
                        <div class="mt-3 text-end"><input class="btn btn-primary"
                            type="submit" value="Change Password">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#profileForm').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    digits: true,
                    minlength: 7,
                    maxlength: 15
                },
                profile_picture: {
                    extension: "jpg|jpeg|png|webp"
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    maxlength: "Maximum 255 characters allowed"
                },
                email: {
                    required: "Please enter your email",
                    email: "Enter a valid email"
                },
                phone: {
                    digits: "Enter only digits",
                    minlength: "At least 7 digits required",
                    maxlength: "Maximum 15 digits allowed"
                },
                profile_picture: {
                    extension: "Only jpg, jpeg, png, and webp files are allowed"
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.col-12').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            }
        });
    });

    $(document).ready(function () {
        $("#changePassword").validate({
            rules: {
                old_password: {
                    required: true
                },
                new_password: {
                    required: true,
                    minlength: 8
                },
                cpassword: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                old_password: {
                    required: "Please enter your old password"
                },
                new_password: {
                    required: "Please enter a new password",
                    minlength: "Password must be at least 8 characters"
                },
                cpassword: {
                    required: "Please confirm your new password",
                    equalTo: "Passwords do not match"
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.position-relative').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>
@endsection