@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Booking Management','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid dashboard-13">
<div class="card">
    <div class="card-header">
        <h5>Booking Form</h5>
        <p class="f-m-light mt-1"> Fill up your true details and next proceed.</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="row shipping-form g-5">
                    <div class="col-xl-9 shipping-border checkout-cart">
                        <div class="nav nav-pills horizontal-options shipping-options"
                            id="cart-options-tab" role="tablist" aria-orientation="vertical"><a
                                class="nav-link b-r-0 active" id="bill-wizard-tab"
                                data-bs-toggle="pill" href="#bill-wizard" role="tab"
                                aria-controls="bill-wizard" aria-selected="true">
                                <div class="cart-options">
                                    <div class="stroke-icon-wizard"><i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="cart-options-content">
                                        <h6>Customer Info</h6>
                                    </div>
                                </div>
                            </a><a class="nav-link b-r-0" id="ship-wizard-tab" data-bs-toggle="pill"
                                href="#ship-wizard" role="tab" aria-controls="ship-wizard"
                                aria-selected="false">
                                <div class="cart-options">
                                    <div class="stroke-icon-wizard"><i
                                            class="fa-solid fa-truck"></i></div>
                                    <div class="cart-options-content">
                                        <h6>Wash Type & Package</h6>
                                    </div>
                                </div>
                            </a><a class="nav-link b-r-0" id="payment-wizard-tab"
                                data-bs-toggle="pill" href="#payment-wizard" role="tab"
                                aria-controls="payment-wizard" aria-selected="false">
                                <div class="cart-options">
                                    <div class="stroke-icon-wizard"><i
                                            class="fa-solid fa-money-bill-1"></i></div>
                                    <div class="cart-options-content">
                                        <h6>Payment</h6>
                                    </div>
                                </div>
                            </a><a class="nav-link b-r-0" id="finish-wizard-tab"
                                data-bs-toggle="pill" href="#finish-wizard" role="tab"
                                aria-controls="finish-wizard" aria-selected="false">
                                <div class="cart-options">
                                    <div class="stroke-icon-wizard"><i
                                            class="fa-solid fa-check-square"></i></div>
                                    <div class="cart-options-content">
                                        <h6>Finish</h6>
                                    </div>
                                </div>
                            </a></div>
                        <div class="tab-content dark-field shipping-content shipping-wizard basic-wizard"
                            id="cart-options-tabContent">
                            <div class="tab-pane fade show active" id="bill-wizard" role="tabpanel"
                                aria-labelledby="bill-wizard-tab">
                                <form class="row g-3 needs-validation" novalidate="">
                                    <div class="col-md-6"><label class="form-label"
                                            for="customSelectCustomerName">Customer
                                            Name</label><select class="form-select select2"
                                            id="customSelectCustomerName" >
                                            <option selected="" disabled="" value="1">Select
                                                Customer Name</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone }}"> {{ $user->name }} </option>
                                            @endforeach
                                            
                                        </select></div>
                                    <div class="col-sm-6"><label class="form-label"
                                            for="customName">Full Name</label><input
                                            class="form-control" id="customName" type="text"
                                            placeholder="Enter full name"></div>
                                    <div class="col-sm-6"><label class="form-label"
                                            for="customContact">Contact Number</label><input
                                            class="form-control" id="customContact" type="number"
                                            placeholder="Enter number"></div>
                                    <div class="col-sm-6"><label class="form-label"
                                            for="customEmail">Email</label><input
                                            class="form-control" id="customEmail" type="email"
                                            placeholder="pixelstrap@example.com"></div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="customSelectCustomerVehicle">Customer Vehicle</label>
                                        <select class="form-select" id="customSelectCustomerVehicle">
                                            <option selected disabled>Select Vehicle</option>
                                            <!-- Options will be populated dynamically -->
                                        </select>
                                    </div>
                                    <div class="col-12"> <label class="form-label"
                                            for="currentAddress1">Customer Booking Address
                                        </label><textarea class="form-control" id="currentAddress1"
                                            rows="3"
                                            placeholder="Enter your Customer booking address"></textarea>
                                    </div>
                                    <div class="col-md-4"><label class="form-label"
                                            for="customSelectCountry">Country</label>
                                            
                                            <input
                                            class="form-control" id="customSelectCountry" type="text"
                                            placeholder="Enter Country">
                                            
                                        </div>
                                    <div class="col-md-4 col-sm-6"> <label class="form-label"
                                            for="customstate">State</label><input
                                            class="form-control" id="customstate" type="text"
                                            placeholder="Enter state"></div>
                                    <div class="col-md-4 col-sm-6"><label class="form-label"
                                            for="customPostalCode">Postal Code</label><input
                                            class="form-control" id="customPostalCode" type="text"
                                            placeholder="Enter postal code">
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <div class="col-12 justify-content-end common-flex">
                                        <button class="btn btn-primary" type="button"
                                            onclick="proceedNextButtonClick('ship-wizard-tab')">Proceed
                                            to Next<i
                                                class="fa-solid fa-truck proceed-next"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade shipping-wizard" id="ship-wizard"
                                role="tabpanel" aria-labelledby="ship-wizard-tab">
                                <div class="row g-md-3 g-4">
                                    <div class="col-xxl-5 col-md-6 box-col-6">
                                        <div class="shipping-title">
                                            <h6>Wash Type</h6>
                                        </div>
                                        <div class="row g-3 mt-0 flex-column">
                                            <div class="col-md-12">
                                                <label class="form-label" for="washTypeSelect">Select Wash Type</label>
                                                <select class="form-select" id="washTypeSelect" name="service_id">
                                                    <option selected disabled>Select Wash Type</option>
                                                    @foreach($wash_types as $wash_type)
                                                        <option value="{{ $wash_type->id }}" data-price="{{ $wash_type->price }}">{{ $wash_type->name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label" for="packageSelect">Select Package(s)</label>
                                                <select class="form-select" id="packageSelect" multiple name="add_ons_id[]">
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->name }}</option>
                                                    @endforeach
                                                    
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 justify-content-end common-flex">
                                        <button class="btn btn-primary"
                                            onclick="proceedNextButtonClick('bill-wizard-tab')"><i
                                                class="fa-solid fa-truck proceed-prev"></i>Proceed
                                            to Back</button><button class="btn btn-primary"
                                            type="button"
                                            onclick="proceedNextButtonClick('payment-wizard-tab')">Proceed
                                            to Next<i
                                                class="fa-solid fa-truck proceed-next"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade shipping-wizard" id="payment-wizard"
                                role="tabpanel" aria-labelledby="payment-wizard-tab">
                                <div class="payment-info-wrapper">
                                    <div class="row shipping-method g-3">
                                       
                                        <div class="col-12">
                                            <div class="card-wrapper border rounded-3 light-card">
                                                <div>
                                                    <div class="form-check radio radio-primary">
                                                        <input
                                                            class="form-check-input"
                                                            id="shipping-choose7"
                                                            type="radio"
                                                            name="paymentMethod"
                                                            value="cod"
                                                            checked
                                                        >
                                                        <label class="form-check-label mb-0 f-w-500" for="shipping-choose7">
                                                            Cash On Delivery
                                                        </label>
                                                    </div>
                                                    <p>After your order is Washed, make a
                                                        cash payment</p>
                                                </div>
                                                <div> <img src="{{ asset('assets/images/shared_image.jpeg') }}"
                                                        alt="delivery"></div>
                                            </div>
                                        </div>
                                        <div class="common-flex main-custom-form">
                                            <div class="input-group">
                                                <label class="input-group-text"
                                                    for="inputGroupSelect01">Apply Coupon</label>
                                                    
                                                <select class="form-select" id="couponSelect">
                                                    <option selected="" value="">Select Your Coupon code
                                                    </option>
                                                    @foreach($coupons as $coupon)
                                                        <option 
                                                            value="{{ $coupon->id }}"
                                                            data-type="{{ $coupon->discount_type }}"
                                                            data-value="{{ $coupon->discount_value }}"
                                                        >
                                                            {{ $coupon->code }} - 
                                                            {{ $coupon->discount_type == 'fixed' ? '£'.$coupon->discount_value.' Off' : $coupon->discount_value.'% Off' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 justify-content-end common-flex">
                                            <button class="btn btn-primary" type="button"
                                                onclick="proceedNextButtonClick('ship-wizard-tab')">
                                                <i
                                                    class="fa-solid fa-truck proceed-prev"></i>Proceed
                                                to Back</button><button class="btn btn-primary"
                                                type="button"
                                                onclick="proceedNextButtonClick('finish-wizard-tab')">
                                                Proceed to Next<i
                                                    class="fa-solid fa-truck proceed-next"></i></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade shipping-wizard finish-wizard1"
                                id="finish-wizard" role="tabpanel"
                                aria-labelledby="finish-wizard-tab">
                                <div class="finish-shipping"><svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#ord-success') }}">
                                        </use>
                                    </svg>
                                    <div class="mt-sm-3">
                                        <h5>Order Placed Successfully</h5>
                                        <p class="mb-0 c-o-light">A confirmation email with
                                            your order details has been sent to you.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="shipping-info">
                            <h5>Current Cart </h5>
                            <div class="overflow-auto custom-scrollbar">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            
                                            <th scope="col">Product Detail </th>
                                            <th scope="col">Price </th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartItems">
                                        {{-- <tr>
                                            <td>
                                                <div>
                                                    <h6>Interior Wash </h6>
                                                </div>
                                            </td>
                                            <td>
                                                <p>£0.00</p>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Sub Total :</td>
                                            <td colspan="2" id="subTotal">£0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Discount :</td>
                                            <td colspan="2" id="discount">£0.00</td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Total (£) :</td>
                                            <td colspan="2" id="total">£0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <input type="hidden" name="discount_code" id="discount_code">
                                <input type="hidden" name="discount_id" id="discount_id">
                                <input type="hidden" name="subtotal" id="subtotal_amount">
                                <input type="hidden" name="discount" id="discount_amount">
                                <input type="hidden" name="total_amount" id="total_amount">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
@endsection
@section('scripts')
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAFmrV-jN6567bNi-hsWYUN5tPpNqg8-Q&libraries=places"
    async defer></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#packageSelect').select2({
        placeholder: "Select packages",
        allowClear: true,
        width: '100%' // ensure it respects container
    });
    $('#customSelectCustomerName').on('change', function () {
        var selected = $(this).find('option:selected');
        var customerId = selected.val();

        // Fill name, email, phone
        $('#customName').val(selected.data('name'));
        $('#customContact').val(selected.data('phone'));
        $('#customEmail').val(selected.data('email'));

        // Clear vehicle dropdown
        $('#customSelectCustomerVehicle').html('<option selected disabled>Loading...</option>');

        // Fetch vehicle data
        $.ajax({
            url: `${site_url}/admin/bookings/get-customer-vehicles/${customerId}`,
            method: 'GET',
            success: function (response) {
                var options = '<option selected disabled>Select Vehicle</option>';
                if (response.length > 0) {
                    $.each(response, function (index, vehicle) {
                        options += `<option value="${vehicle.id}" data-model="${vehicle.model}">
                                        ${vehicle.model} ( ${vehicle.license_plate} )
                                    </option>`;
                    });
                } else {
                    options = '<option disabled>No vehicles found</option>';
                }
                $('#customSelectCustomerVehicle').html(options);
            },
            error: function () {
                $('#customSelectCustomerVehicle').html('<option disabled>Error loading vehicles</option>');
            }
        });
    });
});
</script>
<script>
    let autocomplete;

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            $('#currentAddress1')[0],
            { types: ['geocode'] }
        );

        autocomplete.setFields(['address_component', 'geometry']);

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();

            let addressComponents = {
                country: '',
                state: '',
                postal_code: ''
            };

            $.each(place.address_components, function (i, component) {
                const types = component.types;

                if (types.includes('country')) {
                    addressComponents.country = component.long_name;
                }
                if (types.includes('administrative_area_level_1')) {
                    addressComponents.state = component.long_name;
                }
                if (types.includes('postal_code')) {
                    addressComponents.postal_code = component.long_name;
                }
            });

            // Fill form fields
            $('#customSelectCountry').val(addressComponents.country);
            $('#customstate').val(addressComponents.state);
            $('#customPostalCode').val(addressComponents.postal_code);

            // Latitude & Longitude
            if (place.geometry) {
                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
            }
        });
    }

    // Initialize on page load
    $(window).on('load', function () {
        initAutocomplete();
    });
    function proceedNextButtonClick(targetTabId) {
        const currentTabId = $('.nav-link.active').attr('id');

        // Step 1: Validate customer info
        if (currentTabId === 'bill-wizard-tab') {
            const vehicle = $('#customSelectCustomerVehicle').val();
            const address = $('#currentAddress1').val();

            if (!vehicle) {
                toastr.error("Please select a customer vehicle.");
                return;
            }

            if (!address || address.trim().length === 0) {
                toastr.error("Please enter the customer address.");
                return;
            }
        }

        // Step 2: Validate Wash Type
        if (currentTabId === 'ship-wizard-tab') {
            const washType = $('#washTypeSelect').val();
            if (!washType) {
                toastr.error("Please select a wash type.");
                return;
            }
        }

        // Proceed to next tab
        $(`#${targetTabId}`).tab('show');
    }

    function proceedPrevButtonClick(targetTabId) {
        // Simply show the previous tab
        $(`#${targetTabId}`).tab('show');
    }

    let selectedCoupon = {
        type: null,
        value: 0,
        id: null,
        code: null,
    };

    function updateCart() {
        let cartItemsHtml = '';
        let subTotal = 0;

        // Wash Type
        const washOption = $('#washTypeSelect option:selected');
        if (washOption.val()) {
            const washPrice = parseFloat(washOption.data('price'));
            subTotal += washPrice;
            cartItemsHtml += `
                <tr>
                    <td><h6>${washOption.text()}</h6></td>
                    <td><p>£${washPrice.toFixed(2)}</p></td>
                </tr>
            `;
        }

        // Packages
        $('#packageSelect option:selected').each(function () {
            const pkgName = $(this).text();
            const pkgPrice = parseFloat($(this).data('price'));
            subTotal += pkgPrice;
            cartItemsHtml += `
                <tr>
                    <td><h6>${pkgName}</h6></td>
                    <td><p>£${pkgPrice.toFixed(2)}</p></td>
                </tr>
            `;
        });

        // Apply Coupon
        let discountAmount = 0;
        if (selectedCoupon.type === 'fixed') {
            discountAmount = parseFloat(selectedCoupon.value) || 0;
        } else if (selectedCoupon.type === 'percentage') {
            discountAmount = subTotal * (selectedCoupon.value / 100);
        }

        // Tax Calculation
        const subAfterDiscount = Math.max(0, subTotal - discountAmount);
        const taxAmount = 0;
        const total = subAfterDiscount + taxAmount;

        // Update HTML
        $('#cartItems').html(cartItemsHtml);
        $('#subTotal').text(`£${subTotal.toFixed(2)}`);
        $('#discount').text(`- £${discountAmount.toFixed(2)}`);
        $('#tax').text(`£${taxAmount.toFixed(2)}`);
        $('#total').text(`£${total.toFixed(2)}`);

        // Update hidden inputs
        $('#discount_code').val(selectedCoupon.code || '');
        $('#discount_id').val(selectedCoupon.id || '');
        $('#subtotal_amount').val(subtotal.toFixed(2));
        $('#discount_amount').val(discountAmount.toFixed(2));
        $('#total_amount').val(total.toFixed(2));

    }

    // Event Listeners
    $(document).ready(function () {
        $('#washTypeSelect, #packageSelect').on('change', updateCart);
    });

    $(document).on('change', '#couponSelect', function () {
        const couponId = $(this).val();
        const customerId = $('#customSelectCustomerName').val();
        const zipCode = $('#customPostalCode').val();
        // If user selects "none" or blank, remove coupon
        if (!couponId) {
            selectedCoupon = { type: null, value: 0 };
            updateCart(); // Recalculate totals without discount
            toastr.info("Coupon removed.");
            return;
        }
        if (!couponId || !customerId || !zipCode) {
            toastr.error("Please ensure customer and address are selected before applying coupon.");
            $(this).val('');
            return;
        }

        $.ajax({
            url: `${site_url}/admin/bookings/validate-coupon`,
            method: 'POST',
            data: {
                coupon_id: couponId,
                customer_id: customerId,
                zipcode: zipCode,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    selectedCoupon.type = res.data.type;     // fixed / percentage
                    selectedCoupon.value = res.data.value;
                    selectedCoupon.code = res.data.code;

                    updateCart();
                    toastr.success("Coupon applied successfully!");
                } else {
                    selectedCoupon = { type: null, value: 0 };
                    $('#couponSelect').val('');
                    updateCart();
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error("Server error while applying coupon.");
            }
        });
    });
</script>

@endsection