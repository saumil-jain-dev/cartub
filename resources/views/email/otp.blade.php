@extends('email.layouts.app')
@section('header')
    <h1>Car Tub</h1>
    <p>OTP Verification 🔐</p>
@endsection
@section('content')
    <h2>Hello {{ $customer_name }},</h2>
    <p>To continue, please use the One-Time Password (OTP) below to verify your identity:</p>

    <div class="otp-box">{{ $otp }}</div>

    <p>This OTP is valid for the next <strong>10 minutes</strong>. Please do not share this code with anyone.</p>
    <p>📲 <strong>Download the Car Tub App:</strong></p>
    @include('email.layouts.support')
    <p>If you did not request this, please ignore this email or contact our support team immediately.</p>
    @include('email.layouts.footer-support')
@endsection