@extends('email.layouts.app')
@section('header')
    <h1>Service Completed</h1>
    <p>Your car is shining and ready! 🎉</p>
@endsection
@section('content')
    <h2>Hello {{ $customer_name }},</h2>
    <p>Your car wash is complete! Here's a quick summary of your service:</p>

    <div class="details">
        <p><strong>Booking ID:</strong> {{ $booking_data->booking_number }}</p>
        <p><strong>Service Type:</strong> {{ $booking_data->service_name }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking_data->scheduled_date)->format('d F Y') }}</p>
        <p><strong>Cleaner:</strong> {{ $booking_data->cleaner->name ?? "-" }}</p>
        <p><strong>Location:</strong> {{ $booking_data->address }}</p>
    </div>
    <div class="photos">
        @if($booking_data->afterPhoto)
            @foreach ($booking_data->afterPhoto as $image)
                <img src="{{ getImage( $image->photo_path ) }}" alt="Front View" />    
            @endforeach
        @endif
    </div>
    <p>📲<strong>View service history in the Car Tub App:</strong></p>
    @include('email.layouts.support')
    <p>We'd love your feedback! Help us improve by rating the service in the app.</p>
    @include('email.layouts.footer-support')
@endsection