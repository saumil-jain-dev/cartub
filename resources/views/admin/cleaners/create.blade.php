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
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Cleaners Profile</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x">
                                </i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="custom-input" method="POST" action="{{ route('cleaners.store') }}" id="cleanerForm">
                                {{-- <div class="row mb-2">
                                    <div class="profile-title">
                                        <div class="d-flex"> <img class="img-70 rounded-circle" alt=""
                                                src="assets/images/user/7.jpg">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">WILLIAM C. JENNINGS</h5>
                                                <p>DESIGNER</p>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            {{-- <div class="mb-3"><label class="form-label">Cleaner ID</label><input
                                    class="form-control" type="text" disabled placeholder="123456"
                                    value="123456"></div> --}}
                                    @csrf
                            <div class="mb-3"><label class="form-label">Email Address</label><input
                                    class="form-control" type="email" name="email"
                                    placeholder="Enter Email" value="{{ old('email') }}"></div>
                            <div class="mb-3"><label class="form-label">Mobile Number</label>
                                <input class="form-control" type="tel" name="phone" placeholder="Enter Mobile Number" value="{{ old('phone') }}">
                            </div>
                            <div class="mb-3"><label class="form-label">Password</label><input
                                    class="form-control" type="password" name="password" placeholder="Password"></div>
                            <div class="mb-3"><label class="form-label">Confirm Password</label><input
                                    class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password"></div>

                           
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                    <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Profile</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="row custom-input">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label" for="customFirstName">First
                                        Name</label><input class="form-control" id="customFirstName"
                                        type="text" placeholder="First Name" name="fname" value="{{ old('fname') }}">
                                        @error('fname')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label" for="customLastName">Last
                                        Name</label><input class="form-control" id="customLastName"
                                        type="text" placeholder="Last name" name="lname" value="{{ old('lname') }}">
                                    @error('lname')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label"
                                        for="customGender">Gender</label><select
                                        class="form-control btn-square" id="customGender" name="gender">
                                        <option value="">--Select--</option>
                                        <option value="1" @if(old('gender') == 1) selected @endif>Male</option>
                                        <option value="2" @if(old('gender') == 2) selected @endif>Female</option>
                                        <option value="3" @if(old('gender') == 3) selected @endif>Other</option>
                                    </select> @error('gender')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label">Date of Birth</label>
                                <input class="form-control" id="human-friendly" type="date" name="dob" value="{{ old('dob') }}"
                                    placeholder="Date of Birth"> @error('dob')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3"><label class="form-label"
                                        for="customAddress">Address</label><textarea
                                        class="form-control" id="customAddress" type="text" rows="2.5"
                                        placeholder="Home address" name="address">{{ old('address') }}</textarea> @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label"
                                        for="customCity">City</label><input class="form-control"
                                        id="customCity" type="text" placeholder="City" name="city" value="{{ old('city') }}"> @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label"
                                        for="customPostalCode">Postal
                                        Code</label><input class="form-control" id="customPostalCode"
                                        type="number" placeholder="Postal code" name="zipcode" value="{{ old('zipcode') }}"> @error('zipcode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3"><label class="form-label"
                                        for="customCountry">Country</label>
                                    <input class="form-control btn-square" id="customCountry"
                                        type="text" placeholder="Country" name="country" value="{{ old('country') }}"> @error('country')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror</div>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                    <div class="card-footer text-end"><button class="btn btn-primary"
                            type="submit">Add Cleaner</button></div>
                </form>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAFmrV-jN6567bNi-hsWYUN5tPpNqg8-Q&libraries=places"></script>
<script>
    $(document).ready(function () {
        const autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('customAddress'),
            { types: ['geocode'] }
        );

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();

            let city = '', country = '', zipcode = '';

            place.address_components.forEach(function (component) {
                const types = component.types;

                if (types.includes('locality')) {
                    city = component.long_name;
                }
                if (types.includes('country')) {
                    country = component.long_name;
                }
                if (types.includes('postal_code')) {
                    zipcode = component.long_name;
                }
            });

            $('#customCity').val(city);
            $('#customCountry').val(country);
            $('#customPostalCode').val(zipcode);
        });
    });
</script>
<script>
$(document).ready(function () {
    $('#cleanerForm').validate({
        rules: {
            email: { required: true, email: true },
            phone: { required: true, maxlength: 12 },
            password: { required: true, minlength: 6 },
            password_confirmation: { equalTo: '[name="password"]' },
            fname: { required: true },
            lname: { required: true },
            gender: { required: true },
            dob: { required: true, date: true },
            address: { required: true },
            city: { required: true },
            zipcode: { required: true, digits: true },
            country: { required: true },
        },
        messages: {
            cpassword: { equalTo: "Passwords do not match" },
        },
        errorClass: 'text-danger',
        errorElement: 'small',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>

@endsection