<!-- Core Libraries -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

<!-- Feather Icons -->
<script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>

<!-- Scrollbar -->
<script src="{{ asset('assets/js/scrollbar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>

<!-- Configuration and Sidebar -->
<script src="{{ asset('assets/js/config.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-pin.js') }}"></script>

<!-- Slick Carousel -->
<script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/slick/slick.js') }}"></script>
<script src="{{ asset('assets/js/header-slick.js') }}"></script>

<!-- Apex Charts -->
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>

<!-- Counter -->
<script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>

<!-- DataTables Core -->
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/dataTables.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/dataTables.select.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/select.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

<!-- DataTables Extensions -->
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.autoFill.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/autoFill.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.keyTable.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/keyTable.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/fixedHeader.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.responsive.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/rowReorder.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/custom.js') }}"></script>

<!-- Flatpickr Date Picker -->
<!-- <script src="{{ asset('assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/flat-pickr/custom-flatpickr.js') }}"></script> -->
<script src="{{ asset('assets/js/flat-pickr/moment.js') }}"></script>
<script src="{{ asset('assets/js/flat-pickr/custom-range-btn.js') }}"></script>

<!-- Modal Validation -->
<script src="{{ asset('assets/js/modalpage/validation-modal.js') }}"></script>

<!-- Select Picker -->
<script src="{{ asset('assets/js/select/bootstrap-select.min.js') }}"></script>

<!-- Dashboard Scripts -->
<script src="{{ asset('assets/js/dashboard/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{--
<script src="assets/js/trash_popup.js"></script> --}}

<!-- Theme and Custom Scripts -->
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/script1.js') }}"></script>
<script src="{{ asset('assets/js/custom-project.js') }}"></script>
<script>
    const site_url = "{{ URL('/') }}"
</script>
@if (\Session::has('success') || \Session::has('error') || \Session::has('info') || \Session::has('warning') || !empty($errors->all()))
    <script>

        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: true,
            positionClass: 'toast-bottom-right', // You can change this to your preferred position
            preventDuplicates: false,
            onclick: null,
            showDuration: '300', // Duration of the fade-in animation in milliseconds
            hideDuration: '1000', // Duration of the fade-out animation in milliseconds
            timeOut: '5000', // Time the notification is displayed in milliseconds (5 seconds in this case)
            extendedTimeOut: '1000', // Extra time to display the notification if a user hovers over it in milliseconds
            showEasing: 'swing', // Easing animation for show
            hideEasing: 'linear', // Easing animation for hide
            showMethod: 'fadeIn', // Animation method for show
            hideMethod: 'fadeOut' // Animation method for hide
        };

        @foreach(['success', 'error', 'info', 'warning'] as $type)
            @if (\Session::has($type))
                toastr.{{ $type }}("{{ session($type) }}");
            @endif
        @endforeach

        @if (!empty($errors->all()))
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
@endif