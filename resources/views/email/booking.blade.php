@extends('email.layouts.app')
@section('header')
    <h1>Booking Confirmed 🎉</h1>
    <p>Thank you for choosing CarTub 🚗</p>
@endsection
@section('content')
    <h2>Hi {{ $customer_name }},</h2>
    <p>We're excited to let you know your CarTub booking is confirmed. Here are the details:</p>

    <div class="details">
        <p><strong>📅 Date:</strong> {{ \Carbon\Carbon::parse($booking_data->scheduled_date)->format('d F Y') }} at {{ \Carbon\Carbon::parse($booking_data->scheduled_time)->format('H:i A') }}</p>
        
        <p><strong>🧼 Service:</strong> {{ $booking_data->service_name }}</p>
        <p><strong>📍 Location:</strong> {{ $booking_data->address }}</p>
        <p><strong>🔢 Booking ID:</strong> {{ $booking_data->booking_number }}</p>
    </div>
    <p👉<strong>Download the CarTub App to track and manage your booking:</strong></p>
    @include('email.layouts.support')
    <p>Need to cancel or reschedule? Please do so at least 1 hour before your wash.</p>
    <p>Thanks for trusting CarTub. We'll make your car shine! ✨</p>
    @include('email.layouts.footer-support')
@endsection