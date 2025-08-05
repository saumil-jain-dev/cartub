@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
    @include('admin.components.breadcrumb', [
        'title' => $pageTitle,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
            ['label' => 'Booking Management', 'url' => route('bookings.index')],
            ['label' => $pageTitle] // Last item, no URL
        ]
    ])
   <div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="map" style="width:100%; height:100vh;"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAFmrV-jN6567bNi-hsWYUN5tPpNqg8-Q&libraries=places"></script>
    <!-- Firebase -->
    

    <script>
        const bookingId = @json($bookingId);
        const destLat   = parseFloat(@json($destLat));
        const destLng   = parseFloat(@json($destLng));
        const destLatLng = { lat: destLat, lng: destLng };

        // 3) Map + markers + polyline
        let map, cleanerMarker, destMarker, routeLine;
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: destLatLng,
                zoom: 14
            });

            destMarker = new google.maps.Marker({
                position: destLatLng,
                map,
                title: 'Customer'
            });

            cleanerMarker = new google.maps.Marker({
                map,
                title: 'Cleaner',
                icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            });

            routeLine = new google.maps.Polyline({
                map,
                geodesic: true,
                strokeOpacity: 0.7,
                strokeWeight: 5
            });
        }

        $(document).ready(function() {
            initMap();
        });
    </script>
@endsection