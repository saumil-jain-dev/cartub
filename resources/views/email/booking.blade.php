@extends('email.layouts.app')
@section('header')
    <h1>Booking Confirmed!</h1>
    <p>Thank you for choosing Car Tub 🚗</p>
@endsection
@section('content')
    <h2>Hello {{ $customer_name }},</h2>
    <p>Your booking has been successfully confirmed. Below are your booking details:</p>

    <div class="details">
        <p><strong>Booking ID:</strong> {{ $booking_data->booking_number }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking_data->scheduled_date)->format('d F Y') }}</p>
        <p><strong>Time Slot:</strong> {{ \Carbon\Carbon::parse($booking_data->scheduled_time)->format('H:i A') }}</p>
        <p><strong>Service Type:</strong> {{ $booking_data->service_name }}</p>
        <p><strong>Location:</strong> {{ $booking_data->address }}</p>
    </div>
    <p>📲<strong>Manage your booking anytime with the Car Tub App:</strong></p>
    @include('email.layouts.support')
    <p>If you need to make changes or cancel, please do so at least 1 hour before your scheduled slot.</p>
    @include('email.layouts.footer-support')
@endsection