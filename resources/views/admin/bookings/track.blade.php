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
    <script src="https://www.gstatic.com/firebasejs/9.x.x/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.x.x/firebase-database.js"></script>

    <script>

        const firebaseConfig = {
            apiKey:     "AIzaSyBL5FYCKi17Bd-WbSxk9MwCSvd2xsYQejY",
            authDomain: "cartub-7a7b5.firebaseapp.com",
            databaseURL:"https://cartub-7a7b5-default-rtdb.europe-west1.firebasedatabase.app",
            projectId:  "cartub-7a7b5",
            storageBucket:"cartub-7a7b5.firebasestorage.app",
            messagingSenderId:"188507095259",
            appId:      "1:188507095259:web:a929d71b852a3423a84cfe",
            measurementId: "G-TT5M9Y9WWC"
        };

        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();


        const bookingId = @json($bookingId);
        const destLat   = parseFloat(@json($destLat));
        const destLng   = parseFloat(@json($destLng));
        const destLatLng = { lat: destLat, lng: destLng };

        // 3) Map + markers + polyline
        let map, cleanerMarker, destMarker, routeLine , path = [destLatLng];;
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
                path: path,
                map,
                geodesic: true,
                strokeOpacity: 0.7,
                strokeWeight: 5
            });
        }

        $(document).ready(function() {
            initMap();

            database.ref('bookings/' + bookingId + '/driverLocation').on('value', (snapshot) => {
                const data = snapshot.val();
                if (data) {
                    const driverLat = parseFloat(data.lat);
                    const driverLng = parseFloat(data.lng);
                    const driverLatLng = { lat: driverLat, lng: driverLng };

                    // Update cleaner marker position
                    cleanerMarker.setPosition(driverLatLng);
                    map.panTo(driverLatLng);

                    // Update route line
                    path.push(driverLatLng);
                    routeLine.setPath(path);
                }
            });
        });
    </script>
@endsection
