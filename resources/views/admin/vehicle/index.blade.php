@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Vehicle Management','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid user-list-wrapper table_card_header">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon"></div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="customer-vehicles-list">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Vehicle</th>
                                        <th>Number Plate</th>
                                        <th>Manufacture Year</th>
                                        <th>Color</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehicles as $vehicle)
                                    <tr>
                                        <td></td>
                                        <td>{{ $vehicle->customer?->name ?? "-" }}</td>
                                        <td>{{ $vehicle->customer?->email ?? "-" }}</td>
                                        <td>{{ $vehicle->model }}</td>
                                        <td>{{ $vehicle->license_plate }}</td>
                                        <td>{{ $vehicle->year }}</td>
                                       <td>{{ $vehicle->color }}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection