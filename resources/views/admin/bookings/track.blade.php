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
 <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database-compat.js"></script>

    <script>

         const firebaseConfig = {
                apiKey:     "AIzaSyAEAhr_ofPGlN8iADspxCaZ-GRyQ5JNbkI",
                authDomain: "cartub-5d584.firebaseapp.com",
                databaseURL:"https://cartub-5d584-default-rtdb.firebaseio.com",
                projectId:  "cartub-5d584",
                storageBucket:"cartub-5d584.firebasestorage.app",
                messagingSenderId:"1041741383694",
                appId:      "1:1041741383694:web:91e747e06fa8cfc04d545f",
                measurementId: "G-LE50W28NGY"
            };

        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();


        const bookingId = @json($bookingId);
        const cleanerId = @json($cleanerId);
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
                // path: path,
                map,
                geodesic: true,
                strokeOpacity: 0.7,
                strokeWeight: 5
            });
        }

        $(document).ready(function() {
            initMap();
              console.log('cleaner id',cleanerId);
            database.ref('cleaner_locations/' + cleanerId).on('value', (snapshot) => {
                const data = snapshot.val();
                console.log(data,"daata");
                if (!data) return;
            
                // Extract the first object (e.g., booking_4)
                   const bookingKey = Object.keys(data).find(
                        (key) => data[key].booking_id == bookingId
                    );
                
                    if (!bookingKey) {
                        console.log("No matching booking found for bookingId:", bookingId);
                        return;
                    }
                
                    const bookingData = data[bookingKey];

            
                console.log(bookingData, "bookingData");
                console.log(bookingId,"bookingId");
                if (bookingData && bookingData.booking_id == bookingId) {
                    const driverLat = parseFloat(bookingData.latitude);
                    const driverLng = parseFloat(bookingData.longitude);
                    const driverLatLng = { lat: driverLat, lng: driverLng };
                    console.log(driverLatLng,"driverLatLng");
                     console.log(driverLat,"driverLat");
                                console.log(driverLng,"longitude");
                    // Update cleaner marker position
                    cleanerMarker.setPosition(driverLatLng);
                    map.panTo(driverLatLng);
            
                    // Update route line
                    path.push(driverLatLng);
                    routeLine.setPath(path);
            
                    // Info window with details
                    const infoWindow = new google.maps.InfoWindow({
                        content: `Speed: ${bookingData.speed} | Heading: ${bookingData.heading} | Accuracy: ${bookingData.accuracy}`
                    });
                    infoWindow.open(map, cleanerMarker);
                }
            });
        });
    </script>
@endsection
