@extends('email.layouts.app')
@section('header')
    <h1>Your Car Wash is Done - Here's the Result!</h1>
    <p>Your car is shining and ready! 🎉</p>
@endsection
@section('content')
    <h2>Dear {{ $customer_name }},</h2>
    <p>We're happy to confirm that your car wash is now complete.</p>
    <p>Here's how your car looks now:</p>
    <div class="photos">
        @if($booking_data->afterPhoto)
            @foreach ($booking_data->afterPhoto as $image)
                <img src="{{ getImage( $image->photo_path ) }}" alt="Front View" />    
            @endforeach
        @endif
    </div>
    <p>🪞 What a difference!</p>
    <div class="details">
        <p><strong>Service:</strong> {{ $booking_data->service_name }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking_data->scheduled_date)->format('d F Y') }}</p>
        <p><strong>Cleaner:</strong> {{ $booking_data->cleaner->name ?? "-" }}</p>
        <p><strong>Location:</strong> {{ $booking_data->address }}</p>
        <p><strong>Booking ID:</strong> {{ $booking_data->booking_number }}</p>
    </div>
    <p>Want to relive the shine? Check your before & after photos in the CarTub App.</p>
    <p>📲<strong>Manage your service anytime:</strong></p>
    @include('email.layouts.support')
    <p>We’d appreciate your feedback! ⭐ Please rate your service in the app.</p>
    @include('email.layouts.footer-support')
@endsection