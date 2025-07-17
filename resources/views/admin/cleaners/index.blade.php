@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'User Management','url' => ''],
        ['label' => 'Cleaners','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid candidate-wrapper">
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        
                        <div class="col-xl col-md-4 col-sm-6"><label
                                class="form-label">Availability</label><select class="form-select"
                                aria-label="Select parent category">
                                <option selected="">Available</option>
                                <option value="1">Busy</option>
                                <option value="2">Onboarding</option>
                                <option value="3">Offline</option>
                                <option value="4">UI/UX Designer</option>
                            </select></div>
                        <div class="col-xl col-md-4 col-sm-6"><label
                                class="form-label">Rating</label><select class="form-select"
                                aria-label="Select parent category">
                                <option selected="" value="5">5 <i
                                        class="fa-solid fa-star txt-warning"></i></option>
                                <option value="4">4 <i class="fa-solid fa-star txt-warning"></i> and
                                    above</option>
                                <option value="3">3 <i class="fa-solid fa-star txt-warning"></i> and
                                    above</option>
                                <option value="2">2 <i class="fa-solid fa-star txt-warning"></i> and
                                    above</option>
                            </select></div>
                        <div class="col-xl col-md-4 col-sm-6"><label class="form-label">Jobs
                                Completed</label><select class="form-select"
                                aria-label="Select your experience">
                                <option selected=""> 0-50</option>
                                <option value="1">51-100</option>
                                <option value="2">100-200</option>
                            </select></div>
                        <div class="col common-f-start"><a class="btn btn-primary f-w-500"
                                href="#!">Filter</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body candidates-box px-0">
                    <div class="table-responsive custom-scrollbar">
                        <table class="table" id="candidates-table">
                            <thead>
                                <tr>
                                    <th> </th>
                                    <th> Photo / Name</th>
                                    <th> Phone</th>
                                    <th> Email</th>
                                    <th> Completed Jobs</th>
                                    <th> Rating</th>
                                    <th> Availability</th>
                                    <th> Status</th>
                                    <th> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cleaners as $cleaner)
                                    
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="common-flex align-items-center">
                                                <div class="position-relative">
                                                    <img class="img-fluid rounded-circle"
                                                        src="{{ $cleaner->profile_image_url }}" alt="user">
                                                    <div class="status">
                                                        @if($cleaner->is_available)
                                                        <div class="inner-dot bg-success"></div>
                                                        @else
                                                        <div class="inner-dot bg-danger"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div><a class="f-w-500" href="javascript:void(0)">{{ $cleaner->name }}</a>
                                                   
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>{{ $cleaner->phone }}</td>
                                        <td>{{ $cleaner->email }}</td>
                                        <td>{{ $cleaner->completed_job_count }}</td>
                                        <td>
                                            <div class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= round($cleaner->average_rating))
                                                        <i class="fa-solid fa-star txt-warning"></i>
                                                    @else
                                                        <i class="fa-regular fa-star txt-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>{{ $cleaner->is_available ? "Available" : "Not Available" }}</td>
                                        <td>
                                            <span class="badge {{ $cleaner->is_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $cleaner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="common-align gap-2 justify-content-start">
                                                @if(hasPermission('cleaners.edit'))
                                                <a class="square-white" href="javascript:void(0)"><svg>
                                                        <use
                                                            href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                                @endif
                                                @if(hasPermission('cleaners.destroy'))
                                                <a class="square-white trash-7" href="javascript:void(0)"><svg>
                                                        <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
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
@endsection