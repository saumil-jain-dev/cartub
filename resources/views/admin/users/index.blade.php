@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'Users','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}">
                        <div class="row g-3">
                            <div class="col-xl col-md-4 col-sm-6"><label class="form-label">Customer
                                    Vechile</label>
                                <select class="form-select" aria-label="Select parent category" name="customer-vehicle">
                                    <option selected="">1</option>
                                    <option value="1">2</option>
                                    <option value="2">3</option>
                                    <option value="3">4</option>
                                    <option value="4">5</option>
                                </select>
                            </div>
                            <div class="col d-flex justify-content-start align-items-center m-t-40">
                                <button type="submit" class="btn btn-primary f-w-500">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon"><a class="btn btn-primary f-w-500" href="#"><i
                                class="fa-solid fa-plus pe-2"></i>Add User</a></div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="roles-permission">
                                <thead>
                                    <tr>
                                        <th></th>
                                        
                                        <th> <span class="c-o-light f-w-600">Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Phone No.</span></th>
                                        <th> <span class="c-o-light f-w-600">Email</span></th>
                                        <th> <span class="c-o-light f-w-600">Total Bookings</span></th>
                                        <th> <span class="c-o-light f-w-600">Creation Date</span></th>
                                        <th> <span class="c-o-light f-w-600">Status</span></th>
                                        <th> <span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="user-row">
                                        <td></td>
                                        
                                        <td><a href="user-profile">Ravi Mehta</a></td>
                                        <td>
                                            <p>98765xxxxx</p>
                                        </td>
                                        <td>
                                            <p>ravi@email.com</p>
                                        </td>
                                        <td>
                                            <p>12</p>
                                        </td>
                                        <td>
                                            <p>15 Feb 2024, 04:20 AM</p>
                                        </td>
                                        <td><span class="badge badge-light-success">Active</span></td>
                                        <td>
                                            <div class="common-align gap-2 justify-content-start">
                                                <a class="square-white" href="#"><svg>
                                                        <use
                                                            href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                        </use>
                                                    </svg></a>
                                                <a class="square-white trash-7" href="#!"><svg>
                                                        <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}">
                                                        </use>
                                                    </svg></a>
                                            </div>
                                        </td>
                                    </tr>
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