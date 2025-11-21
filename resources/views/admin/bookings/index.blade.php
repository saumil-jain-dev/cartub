@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('styles')
<style>
    table.dataTable tr th.select-checkbox.selected::after {
    content: "✔";
    margin-top: -11px;
    margin-left: -4px;
    text-align: center;
    text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
}

    /* Selected row styling */
    tr.selected {
        background-color: #e3f2fd !important;
        border-left: 4px solid #2196f3 !important;
    }

    tr.selected td {
        background-color: #e3f2fd !important;
    }

        /* Checkbox styling */
    .select-checkbox input[type="checkbox"] {
        cursor: pointer;
    }

    #select-all {
        cursor: pointer;
    }

        /* Delete button styling */
    #bulk-delete-btn {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-width: 140px;
        justify-content: center;
    }

    #bulk-delete-btn:hover {
        background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
    }

    #bulk-delete-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }

    /* Remove the pseudo-element since we're using FontAwesome icon */

        /* Hide elements when no data */
    .no-data #select-all,
    .no-data #bulk-delete-btn {
        display: none !important;
    }

    /* Initially hide delete button until selection */
    #bulk-delete-btn {
        display: none;
    }

    #bulk-delete-btn.show {
        display: inline-flex !important;
    }

        /* Styling for empty table state */
    .no-data .dataTables_wrapper {
        opacity: 0.7;
    }

    .no-data .dataTables_info {
        color: #6c757d;
        font-style: italic;
    }

    /* Header layout improvements */
    .header-top h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .header-top {
        padding: 15px 0;
    }
</style>
@endsection
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Booking Management','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid common-order-history">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('bookings.index') }}">
                        <div class="row g-3 custom-input">
                            <div class="col-xl col-md-6"> <label class="form-label"
                                    for="datetime-local">From: </label>
                                <div class="input-group flatpicker-calender"><input class="form-control"
                                        id="datetime-local" name="from_date" value="{{ request()->input('from_date') }}" placeholder="dd/mm/yyyy"></div>
                            </div>
                            <div class="col-xl col-md-6"> <label class="form-label"
                                    for="datetime-local3">To: </label>
                                <div class="input-group flatpicker-calender"><input class="form-control"
                                        id="datetime-local3"  name="to_date" value="{{ request()->input('to_date') }}" placeholder="dd/mm/yyyy"></div>
                            </div>
                            <div class="col-xl col-md-6"><label class="form-label">Payment
                                    Status</label><select class="form-select" name="payment_status">
                                    <option value="">Select Payment Status</option>
                                    <option value="paid" {{ request()->input('payment_status') == 'paid' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ request()->input('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select></div>
                            <div class="col-xl col-md-6"><label class="form-label">Payment
                                    Methods</label><select class="form-select" name="payment_method">
                                    <option value="">Select Payment</option>
                                    <option value="apple_pay" {{ request()->input('payment_method') == 'apple_pay' ? 'selected' : '' }}>Apple Pay</option>
                                    <option value="google_pay" {{ request()->input('payment_method') == 'google_pay' ? 'selected' : '' }}>Google Pay</option>
                                    <option value="card" {{ request()->input('payment_method') == 'card' ? 'selected' : '' }}>Credit Card</option>
                                </select></div>
                                <div class="col d-flex justify-content-start align-items-center m-t-40">
                                    <button type="submit" class="btn btn-primary f-w-500">Submit</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card heading-space">
                <div class="card-header card-no-border">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h5 class="mt-4">New Orders</h5>
                        <button id="bulk-delete-btn" class="btn btn-danger d-none">
                            <i class="fa-solid fa-trash me-2"></i>Delete
                        </button>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="order-history-wrapper">
                                <div class="recent-table table-responsive custom-scrollbar">
                                    <table class="table" id="order-history-table">
                                        <thead>
                                            <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                <th> <span class="f-light f-w-600">Order Number</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Order Date</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Customer Name</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Total Amount</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Payment Status</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Payment Method</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Booking Status</span>
                                                </th>
                                                <th> <span class="f-light f-w-600">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookingData as $booking)
                                            <tr class="inbox-data">
                                                <td></td>
                                                <td> <a href="javascript:void(0)">{{ $booking->booking_number }}</a></td>
                                                <td>
                                                    <p class="c-o-light">{{ $booking->created_at->format('d M Y h:i:A') }}</p>
                                                </td>
                                                <td>
                                                    <p class="c-o-light">{{ optional($booking->customer)->name ?? '-' }}</p>
                                                </td>
                                                <td>
                                                    <p class="c-o-light">£{{ $booking->total_amount }} </p>
                                                </td>
                                                <td>
                                                    @if ($booking->payment_status === 'pending')
                                                        <span class="badge badge-light-warning">Pending</span>
                                                    @elseif ($booking->payment_status === 'failed')
                                                        <span class="badge badge-light-danger">Failed</span>
                                                    @elseif ($booking->payment_status === 'paid')
                                                        <span class="badge badge-light-success">Completed</span>
                                                    @else
                                                        <span class="badge badge-light-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="c-o-light">{{ optional($booking->payment)->payment_type ?? '-' }}</p>
                                                </td>
                                                <td>
                                                    @php
                                                        if ($booking->status === 'pending' && $booking->cleaner_id) {
                                                            $badgeText = 'Assigned';
                                                            $badgeClass = 'badge-info';
                                                        } else {
                                                            switch ($booking->status) {
                                                                case 'pending':
                                                                    $badgeText = 'Pending';
                                                                    $badgeClass = 'badge-warning';
                                                                    break;
                                                                case 'in_route':
                                                                    $badgeText = 'In Route';
                                                                    $badgeClass = 'badge-primary';
                                                                    break;
                                                                case 'in_progress':
                                                                    $badgeText = 'In Progress';
                                                                    $badgeClass = 'badge-secondary';
                                                                    break;
                                                                case 'completed':
                                                                    $badgeText = 'Completed';
                                                                    $badgeClass = 'badge-success';
                                                                    break;
                                                                case 'cancelled':
                                                                    $badgeText = 'Cancelled';
                                                                    $badgeClass = 'badge-danger';
                                                                    break;
                                                                case 'mark_as_arrived':
                                                                    $badgeText = 'Marked As Arrived';
                                                                    $badgeClass = 'badge-dark';
                                                                    break;
                                                                default:
                                                                    $badgeText = ucfirst($booking->status);
                                                                    $badgeClass = 'badge-dark';
                                                            }
                                                        }
                                                    @endphp

                                                    <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                                </td>
                                                <td>
                                                    <div
                                                        class="common-align gap-2 justify-content-start">
                                                        @if(hasPermission('bookings.show'))
                                                        <a class="square-white"
                                                            href="{{ route('bookings.show',$booking->id) }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="View"><svg>
                                                                <use
                                                                    href="{{ asset('assets/svg/icon-sprite.svg#fill-view') }}">
                                                                </use>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        @if (hasPermission('bookings.assign-cleaner'))
                                                            @if($booking->status == "pending" && !$booking->cleaner_id)
                                                                <a class="square-white assign-booking" href="javascript:void(0);" data-id="{{ $booking->id }}" data-number="{{ $booking->booking_number }}">
                                                                    <svg>
                                                                        <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                                        </use>
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                        @endif
                                                        @if(hasPermission('bookings.cancel'))
                                                            @if($booking->status == "pending" || $booking->status == "accepted" || $booking->status == "in_route" || $booking->status == "mark_as_arrived")
                                                                <a class="square-white trash-3 cancel-booking"
                                                                    href="javascript:void(0)" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-title="Cancel Booking"
                                                                    data-id="{{ $booking->id }}"
                                                                    >
                                                                    <i class="fa-solid fa-circle-xmark"> </i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                        @if(hasPermission('bookings.destroy'))
                                                            <a class="square-white trash-3 delete-booking"
                                                            href="javascript:void(0)" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="Delete"
                                                            data-id="{{ $booking->id }}"
                                                            >
                                                            <svg>
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
    <div class="modal fade" id="assignBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bookings.assign-cleaner') }}" method="POST" id="assignCleanerForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Cleaner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="booking_id" id="modalBookingId">

                        <div class="mb-3">
                            <label>Booking Number</label>
                            <input type="text" class="form-control" id="modalBookingNumber" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Cleaner</label>
                            <select name="cleaner_id" class="form-control" id="modalCleanerSelect" required>
                                <option value="">Loading available cleaners...</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Assign</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    // Delete functionality is now handled in datatable.custom.js

    //Cancel booking
    $(document).on('click', '.cancel-booking', function () {
        const bookingId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will cancel the booking",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${site_url}/admin/bookings/cancel-booking/${bookingId}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Cancelled!',
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
        const modal = new bootstrap.Modal($('#assignBookingModal')[0]);

        $('.assign-booking').on('click', function () {
            const bookingId = $(this).data('id');
            const bookingNumber = $(this).data('number');

            $('#modalBookingId').val(bookingId);
            $('#modalBookingNumber').val(bookingNumber);

            const $select = $('#modalCleanerSelect');
            $select.html('<option>Loading...</option>');

            $.ajax({

                url: `${site_url}/admin/bookings/${bookingId}/available-cleaners`,
                type: 'GET',
                dataType: 'json',
                success: function (cleaners) {
                    $select.html('<option value="">Select Cleaner</option>');

                    if (cleaners.length === 0) {
                        $select.html('<option value="">No cleaner available</option>');
                    } else {
                        $.each(cleaners, function (i, cleaner) {
                            $select.append(`<option value="${cleaner.id}">${cleaner.name}</option>`);
                        });
                    }
                },
                error: function () {
                    $select.html('<option value="">Failed to load cleaners</option>');
                }
            });

            modal.show();
        });
    });

    //Assign cleaner
    $(document).ready(function () {
        $('#assignCleanerForm').validate({
            rules: {
                cleaner_id: {
                    required: true
                }
            },
            messages: {
                cleaner_id: {
                    required: "Please select a cleaner."
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger',
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });

        // Handle select-all checkbox functionality
        $('#select-all').on('change', function() {
            const isChecked = $(this).is(':checked');
            const table = window.bookingsTable || $("#order-history-table").DataTable();

            console.log('Select-all checkbox changed:', isChecked);
            console.log('Table reference:', table);

            if (isChecked) {
                // Select all rows on current page
                table.rows({ page: 'current' }).select();
                console.log('Selected all rows on current page');
            } else {
                // Deselect all rows
                table.rows().deselect();
                console.log('Deselected all rows');
            }
        });

        // Update select-all checkbox state when individual rows are selected/deselected
        $(document).on('change', 'input[type="checkbox"]:not(#select-all)', function() {
            const table = window.bookingsTable || $("#order-history-table").DataTable();
            const totalRows = table.rows({ page: 'current' }).count();
            const selectedRows = table.rows({ selected: true, page: 'current' }).count();

            console.log('Individual checkbox changed. Total rows:', totalRows, 'Selected rows:', selectedRows);

            if (selectedRows === 0) {
                $('#select-all').prop('indeterminate', false).prop('checked', false);
            } else if (selectedRows === totalRows) {
                $('#select-all').prop('indeterminate', false).prop('checked', true);
            } else {
                $('#select-all').prop('indeterminate', true);
            }
        });

        // Debug: Check if checkboxes are properly rendered
        setTimeout(function() {
            console.log('Checking checkboxes after page load...');
            console.log('Select-all checkbox:', $('#select-all').length);
            console.log('Individual checkboxes:', $('input[type="checkbox"]:not(#select-all)').length);
            console.log('DataTable checkboxes:', $('.select-checkbox input[type="checkbox"]').length);

            // Log current selection state
            console.log('Current selected rows (manual):', $('tr.selected').length);
            console.log('Current selected rows (DataTable):', window.bookingsTable ? window.bookingsTable.rows({ selected: true }).count() : 'N/A');

            // Update table UI after page load
            updateTableUI();

                    // Test delete button visibility
        console.log('Testing delete button visibility...');
        // $('#bulk-delete-btn').removeClass('d-none').addClass('show');
        setTimeout(() => {
            // $('#bulk-delete-btn').addClass('d-none').removeClass('show');
            console.log('Delete button test completed');
        }, 2000);

        // Add a global function for debugging
        window.debugSelection = function() {
            console.log('=== SELECTION DEBUG ===');
            console.log('Manual selected rows:', $('tr.selected').length);
            console.log('DataTable selected rows:', window.bookingsTable ? window.bookingsTable.rows({ selected: true }).count() : 'N/A');
            console.log('All checkboxes:', $('input[type="checkbox"]:not(#select-all)').length);
            console.log('Checked checkboxes:', $('input[type="checkbox"]:not(#select-all):checked').length);
            console.log('Delete button visible:', $('#bulk-delete-btn').hasClass('show'));
            console.log('Delete button display:', $('#bulk-delete-btn').css('display'));
            console.log('Delete button classes:', $('#bulk-delete-btn').attr('class'));
            console.log('=====================');
        };

        // Add a function to force show the delete button
        window.forceShowDeleteButton = function() {
            $('#bulk-delete-btn').removeClass('d-none').addClass('show').show().css({
                'display': 'inline-flex !important',
                'visibility': 'visible',
                'opacity': '1'
            });
            console.log('Delete button forced to show');
        };

        // Call debug function
        window.debugSelection();

        // Check if any checkboxes are already checked and show delete button if needed
        const checkedCheckboxes = $('input[type="checkbox"]:not(#select-all):checked');
        if (checkedCheckboxes.length > 0) {
            console.log('Found', checkedCheckboxes.length, 'already checked checkboxes');
            checkedCheckboxes.each(function() {
                $(this).closest('tr').addClass('selected');
            });
            updateBulkDeleteButton();
        }

        // Set up periodic check to ensure delete button visibility
        setInterval(function() {
            const selectedRows = $('tr.selected').length;
            if (selectedRows > 0 && !$('#bulk-delete-btn').is(':visible')) {
                console.log('Periodic check: Found selected rows but delete button not visible, forcing show');
                $('#bulk-delete-btn').removeClass('d-none').addClass('show').show().css({
                    'display': 'inline-flex !important',
                    'visibility': 'visible',
                    'opacity': '1'
                });
            }
        }, 1000);
        }, 2000);

                                // Function to update bulk delete button visibility
        function updateBulkDeleteButton() {
            // Count selected rows from both methods
            const manualSelectedRows = $('tr.selected').length;
            const table = window.bookingsTable || $("#order-history-table").DataTable();
            let dtSelectedRows = 0;

            try {
                dtSelectedRows = table.rows({ selected: true }).count();
            } catch (e) {
                console.log('DataTable selection count failed, using manual count only');
            }

            const totalSelected = manualSelectedRows + dtSelectedRows;

            console.log('Selection update - Manual:', manualSelectedRows, 'DataTable:', dtSelectedRows, 'Total:', totalSelected);

            if (totalSelected > 0) {
                $('#bulk-delete-btn').removeClass('d-none').addClass('show').show();
                console.log('Bulk delete button shown, total selected:', totalSelected);

                // Force the button to be visible
                $('#bulk-delete-btn').css({
                    'display': 'inline-flex !important',
                    'visibility': 'visible',
                    'opacity': '1'
                });
            } else {
                $('#bulk-delete-btn').addClass('d-none').removeClass('show').hide();
                console.log('Bulk delete button hidden, no rows selected');
            }
        }

        // Function to check if table has data and update UI accordingly
        function updateTableUI() {
            const table = window.bookingsTable || $("#order-history-table").DataTable();
            const rowCount = table.rows().count();
            const tableContainer = $('.order-history-wrapper');

            if (rowCount === 0) {
                // No data - hide checkbox and delete button, add no-data class
                $('#select-all').hide();
                $('#bulk-delete-btn').hide();
                tableContainer.addClass('no-data');
                console.log('No data in table, hiding UI elements');
            } else {
                // Has data - show checkbox, hide delete button initially, remove no-data class
                $('#select-all').show();
                $('#bulk-delete-btn').hide();
                tableContainer.removeClass('no-data');
                console.log('Table has data, showing checkbox, hiding delete button');
            }
        }

                        // Simplified checkbox selection handler - this should work for all checkboxes
        $(document).on('change', 'input[type="checkbox"]', function(e) {
            // Skip the select-all checkbox for this handler
            if ($(this).attr('id') === 'select-all') {
                return;
            }

            const row = $(this).closest('tr');
            const isChecked = $(this).is(':checked');

            console.log('Checkbox changed:', isChecked, 'Row:', row.length > 0 ? 'found' : 'not found');
            console.log('Checkbox element:', this);
            console.log('Row element:', row);

            if (isChecked) {
                row.addClass('selected');
                console.log('Row selected, added selected class');

                // Directly show the delete button
                $('#bulk-delete-btn').removeClass('d-none').addClass('show').show().css({
                    'display': 'inline-flex !important',
                    'visibility': 'visible',
                    'opacity': '1'
                });
                console.log('Delete button directly shown');
            } else {
                row.removeClass('selected');
                console.log('Row deselected, removed selected class');
            }

            // Force update the delete button visibility
            setTimeout(() => {
                updateBulkDeleteButton();
            }, 100);

            console.log('Total selected rows after change:', $('tr.selected').length);
        });

        // Also add a click handler as backup
        $(document).on('click', 'input[type="checkbox"]:not(#select-all)', function(e) {
            const isChecked = $(this).is(':checked');
            console.log('Checkbox clicked, will be:', !isChecked);

            // Use setTimeout to wait for the checkbox state to change
            setTimeout(() => {
                const newState = $(this).is(':checked');
                console.log('Checkbox state after click:', newState);

                if (newState) {
                    const row = $(this).closest('tr');
                    row.addClass('selected');
                    console.log('Row selected via click handler');

                    // Force show delete button
                    $('#bulk-delete-btn').removeClass('d-none').addClass('show').show().css({
                        'display': 'inline-flex !important',
                        'visibility': 'visible',
                        'opacity': '1'
                    });
                    console.log('Delete button shown via click handler');
                }
            }, 50);
        });

                // Manual select-all functionality
        $('#select-all').on('change', function() {
            const isChecked = $(this).is(':checked');
            const checkboxes = $('input[type="checkbox"]:not(#select-all)');

            // Update all checkboxes
            checkboxes.prop('checked', isChecked);

            // Update row selection classes
            if (isChecked) {
                $('tbody tr').addClass('selected');
            } else {
                $('tbody tr').removeClass('selected');
            }

            // Also update DataTable selection if available
            const table = window.bookingsTable || $("#order-history-table").DataTable();
            if (table && typeof table.rows === 'function') {
                if (isChecked) {
                    table.rows({ page: 'current' }).select();
                } else {
                    table.rows().deselect();
                }
            }

            // Update bulk delete button visibility
            updateBulkDeleteButton();

            console.log('Manual select-all:', isChecked ? 'All selected' : 'All deselected');
        });
    });
</script>
@endsection
