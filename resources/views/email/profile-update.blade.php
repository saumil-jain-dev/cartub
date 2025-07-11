@extends('email.layouts.app')
@section('header')
    <h1>Car Tub</h1>
    <p>Profile Update Information 🔐</p>
@endsection
@section('content')
    <h2>Hello {{ $customer_name }},</h2>
    <p>Your profile details were successfully updated. Below are your profile details:</p>

    <div class="details">
        <p><strong>Name:</strong> {{ $user_data->name }}</p>
        <p><strong>Email:</strong> {{ $user_data->email }}</p>
        <p><strong>Phone:</strong> {{ $user_data->phone }}</p>
        @if($user_data->profile_picture)
            <p><strong>Profile Image:</strong>
            <div class="photos"><img src="{{ getImage($user_data->profile_picture) }}" /></div>
        </p>@endif
        <p><strong>Update Time:</strong> {{ \Carbon\Carbon::parse($user_data->updated_at)->format('d F Y | h:i A') }}</p>
    </div>
    <p>📲<strong>Manage your profile anytime with the Car Tub App:</strong></p>
    @include('email.layouts.support')
    <p>If you have any questions or did not authorize this profile details, please contact us immediately.</p>
    @include('email.layouts.footer-support')
@endsection