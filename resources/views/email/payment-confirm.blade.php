@extends('email.layouts.app')
@section('header')
    <h1>Payment Confirmed</h1>
    <p>Thank you for your payment</p>
@endsection
@section('content')
    <h2>Hello {{ $customer_name }},</h2>
    <p>We have successfully received your payment. Here are the payment details for your reference:</p>

    <div class="details">
        <p><strong>Transaction ID:</strong> {{ $payment_data->transaction_id }}</p>
        <p><strong>Booking ID:</strong> {{ $booking_data->booking_number }}</p>
        <p><strong>Amount Paid:</strong> {{ $booking_data->total_amount }}</p>
        <p><strong>Payment Mode:</strong> {{ $payment_data->payment_type }}</p>
        <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($payment_data->created_at)->format('d F Y | h:i A') }}</p>
    </div>
    <p>📲<strong>Track your booking in the Car Tub App:</strong></p>
    @include('email.layouts.support')
    <p>If you have any questions or did not authorize this transaction, please contact us immediately.</p>
    @include('email.layouts.footer-support')
@endsection