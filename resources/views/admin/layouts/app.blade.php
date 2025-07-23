<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('pageTitle','Car tub | Easy Wash, Anytime')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    @include('admin.layouts.css')
    <style>
        .header-breadcrumb{
            color: #000;
        }
        .code-error{
            color: red;
        }
    </style>
    @yield('styles')
</head>
<body>
    @include('admin.layouts.loader')
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('admin.layouts.header')
        <div class="page-body-wrapper">
            @include('admin.layouts.sidebar')
            <div class="page-body">
                
                @yield('content')
            </div>
            @include('admin.layouts.footer')
        </div>
    </div>
    @include('admin.layouts.js')
    @yield('scripts')
</body>
<html>