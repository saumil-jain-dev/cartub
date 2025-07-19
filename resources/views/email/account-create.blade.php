@extends('email.layouts.app')
@section('header')
    <h1>Welcome to Car Tub</h1>
    <p>Easy Wash, Anytime 🚗✨</p>
@endsection
@section('content')
    <h2>Hello {{ $customer_name }},</h2>
    <p>Thank you for joining Car Tub – your convenient car washing partner. You can now log in and start booking washes right from your phone.</p>

   <div class="details">
          <p><strong>Username:</strong> {{ $userData->email }}</p>
          <p><strong>Temporary Password:</strong> {{ $password }}</p>
          <p>Please change your password after first login.</p>
    </div>


    <p>📲 <strong>Download the Car Tub App:</strong></p>
    @include('email.layouts.support')
   
    @include('email.layouts.footer-support')
@endsection