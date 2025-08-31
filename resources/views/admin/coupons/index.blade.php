@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Coupons Management','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid">
    <div class="container-fluid e-category">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        @if(hasPermission('coupons.create'))
                        <div class="card-header-right-icon">
                            <a class="btn btn-primary f-w-500" id="addCouponBtn" data-bs-toggle="modal" href="#addCouponModal">
                                <i class="fa-solid fa-plus pe-2"></i>Add Coupon
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="list-product list-category">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="project-status">
                                    <thead>
                                        <tr>
                                            <th></th>

                                            <th> <span class="c-o-light f-w-600">Code</span></th>
                                            <th> <span class="c-o-light f-w-600">Discount</span></th>
                                            <th> <span class="c-o-light f-w-600">Start Date</span></th>
                                            <th> <span class="c-o-light f-w-600">End Date</span></th>
                                            <th> <span class="c-o-light f-w-600">Applicable To</span></th>
                                            <th> <span class="c-o-light f-w-600">Status</span>
                                            </th>
                                            <th> <span class="c-o-light f-w-600">Actions</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use Illuminate\Support\Carbon;
                                        @endphp
                                        @foreach ($couponData as $coupons)
                                            <tr class="product-removes inbox-data">
                                            <td></td>

                                            <td>
                                                <p class="f-light">{{ $coupons->code }}</p>
                                            </td>
                                            <td>
                                                <p class="f-light">@if ($coupons->discount_type === 'percentage')
                                                    {{ $coupons->discount_value }}%
                                                @elseif ($coupons->discount_type === 'fixed')
                                                    ${{ $coupons->discount_value }}
                                                @endif
                                                </p>
                                            </td>

                                            <td>
                                                <p class="f-light">{{ Carbon::parse($coupons->valid_from)->format('d F, Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="f-light">{{ Carbon::parse($coupons->valid_until)->format('d F, Y') }}</p>
                                            </td>
                                            <td>
                                                @if($coupons->user_ids == "" && $coupons->zipcodes == "")
                                                <p class="f-light">All User</p>
                                                @elseif($coupons->user_ids != "" && $coupons->zipcodes == "")
                                                <p class="f-light">Specified User</p>
                                                @elseif ($coupons->user_ids == "" && $coupons->zipcodes != "")
                                                <p class="f-light">Specified Area</p>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $coupons->is_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $coupons->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="common-align gap-2 justify-content-start">
                                                    @if(hasPermission('coupons.edit'))
                                                    <a class="square-white editCouponBtn" href="javascript:void(0)" data-id="{{ $coupons->id }}">
                                                        <svg>
                                                            <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}"></use>
                                                        </svg>
                                                    </a>
                                                    @endif
                                                    @if(hasPermission('coupons.destroy'))
                                                    <a class="square-white trash-3 delete-coupon"
                                                        href="javascript:void(0)" data-bs-title="Delete" data-id="{{ $coupons->id }}"><svg>
                                                            <use
                                                                href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}">
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
    </div>
</div>
<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCouponModalLabel">Add Coupon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('coupons.store') }}" id="couponForm">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="id" id="coupon_id">
          <!-- Coupon Code -->
          <div class="mb-3">
            <label for="code" class="form-label">Coupon Code</label>
            <input type="text" class="form-control" id="code" name="code">
          </div>

          <!-- Start Date -->
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date">
          </div>

          <!-- End Date -->
          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date">
          </div>

          <!-- Discount Type -->
          <div class="mb-3">
            <label class="form-label">Discount Type</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="discount_type" id="fixed" value="fixed" checked>
              <label class="form-check-label" for="fixed">Fixed</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="discount_type" id="percentage" value="percentage">
              <label class="form-check-label" for="percentage">Percentage</label>
            </div>
          </div>
          <!-- Discount Value -->
          <div class="mb-3">
            <label for="discount_value" class="form-label">Discount Value</label>
            <input type="number" class="form-control" id="discount_value" name="discount_value" min="0">
          </div>
          <!-- Discount Value -->
          <div class="mb-3">
            <label for="is_active" class="form-label">Status</label>
            <select class="form-control" id="is_active" name="is_active">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <!-- Coupon Applicable To -->
        <div class="mb-3">
            <label class="form-label">Coupon Applicable To</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="applicable_to" id="none" value="" checked>
                <label class="form-check-label" for="none">All</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="applicable_to" id="users" value="users">
                <label class="form-check-label" for="users">Specified User</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="applicable_to" id="area" value="area">
                <label class="form-check-label" for="area">Specified Area</label>
            </div>
        </div>

        <!-- Specified Users (Multiple) -->
        <div class="mb-3" id="usersDiv">
            <label for="users_select" class="form-label">Select Users</label>
            <select class="form-control" id="users_select" name="users[]" multiple>
                @foreach($userData as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
                <!-- Populate from DB -->
            </select>
        </div>


        <!-- Specified Area -->
        <div class="mb-3 d-none" id="areaDiv">
            <label for="zipcodes" class="form-label">Enter Zip Codes</label>
            <textarea class="form-control" id="zipcodes" name="zipcodes" rows="3"></textarea>
            <small class="text-muted">Enter zip codes like: AB12CD,560001,W1A1AA</small>
        </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Coupon</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#users_select').select2({
            placeholder: "Select users",
            allowClear: true,
            width: '100%' // ensure it respects container
        });
        function toggleApplicable() {
            if ($('#users').is(':checked')) {
                $('#usersDiv').removeClass('d-none');
                $('#areaDiv').addClass('d-none');
            } else if ($('#area').is(':checked')) {
                $('#usersDiv').addClass('d-none');
                $('#areaDiv').removeClass('d-none');
            } else {
                $('#usersDiv').addClass('d-none');
                $('#areaDiv').addClass('d-none');
            }
        }

        $('input[name="applicable_to"]').on('change', toggleApplicable);

        toggleApplicable(); // call initially


        // Validation
        $.validator.addMethod("greaterThan", function (value, element, params) {
            const startDate = $(params).val();
            if (!startDate || !value) {
                // If either field is empty, don't validate here
                return true;
            }
            return new Date(value) > new Date(startDate);
        }, 'End date must be after start date.');
        $('#addCouponModal form').validate({
            rules: {
                code: {
                    required: true,
                    remote: {
                        url: `${site_url}/admin/coupons/check-coupon-code`,
                        type: 'post',
                        data: {
                            code: function () {
                                return $('#code').val();
                            },
                            id: function () {
                                return $('#coupon_id').val();
                            },
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    }
                },
                start_date: {
                    required: true,
                    date: true
                },
                end_date: {
                    required: true,
                    date: true,
                    greaterThan: "#start_date"
                },
                discount_type: {
                    required: true
                },
                discount_value: {
                    required: true,
                    number: true
                },
                'users[]': {
                    required: function () {
                        return $('#users').is(':checked');
                    }
                },
                zipcodes: {
                    required: function () {
                        return $('#area').is(':checked');
                    }
                }
            },
            messages: {
                code: {
                    required: "Coupon code is required",
                    remote: "This coupon code is already taken"
                },
                start_date: "Start date is required",
                end_date: {
                    required: "End date is required",
                    greaterThan: "End date must be greater than start date"
                },
                discount_type: "Select a discount type",
                discount_value: "Enter a valid discount value",
                'users[]': "Select at least one user",
                zipcodes: "Enter at least one zip code"
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    $(document).on('click', '.delete-coupon', function () {
        const couponId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the coupon and all its details.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${site_url}/admin/coupons/${couponId}`,
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

    $(document).on('click', '.editCouponBtn', function () {
        const id = $(this).data('id');

        $.ajax({
            url: `${site_url}/admin/coupons/edit/${id}`,
            type: 'GET',
            success: function (data) {
                $('#coupon_id').val(data.id);
                $('#code').val(data.code);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#discount_value').val(data.discount_value);
                $('#discount_type').val(data.discount_type);

                if (data.applicable_to === 'users') {
                    $('#users').prop('checked', true).trigger('change');
                    $('#users_select').val(JSON.parse(data.user_ids)).trigger('change');
                } else if (data.applicable_to === 'area') {
                    $('#area').prop('checked', true).trigger('change');
                    $('#zipcodes').val(JSON.parse(data.zipcodes));
                }

                $('#is_active').val(data.is_active);

                $('#addCouponModal').modal('show');
            }
        });
    });

    $(document).on('click', '#addCouponBtn', function () {
        const $form = $('#couponForm');

        // Reset the form fields
        $form[0].reset();

        // Reset hidden id
        $('#coupon_id').val('');

        // Reset Select2
        $('#users_select').val(null).trigger('change');

        // Hide both applicable sections
        $('#usersDiv').addClass('d-none');
        $('#areaDiv').addClass('d-none');

        // Remove validation errors
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();

        // Optional: set is_active default
        $('#is_active').val('1');

        // Open the modal
        $('#addCouponModal').modal('show');
    });
</script>
@endsection
