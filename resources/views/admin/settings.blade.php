@extends('admin.layouts.app')
@section('pageTitle', $pageTitle)
@section('content')
@include('admin.components.breadcrumb', [
    'title' => $pageTitle,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.dashboard')],
        ['label' => 'App Setting','url' => ''],
        ['label' => $pageTitle] // Last item, no URL
    ]
])
<div class="container-fluid main-setting">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>App Settings</h5>
                </div>
                <form method="post" action="{{ route('settings.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row g-lg-3 g-4">
                            <div class="col-lg-3 col-12">
                                <div class="nav flex-lg-column nav-pills nav-primary" id="ver-pills-tab"
                                    role="tablist" aria-orientation="vertical"><a class="nav-link active"
                                        id="ver-pills-general-tab" data-bs-toggle="pill"
                                        href="#ver-pills-general"> <svg class="stroke-icon">
                                            <use href="assets/svg/icon-sprite.svg#general-setting"></use>
                                        </svg>General</a>

                                    
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                <div class="tab-content" id="ver-pills-tabContent">
                                    <div class="tab-pane fade show active" id="ver-pills-general">
                                        
                                        @foreach ($settingsData as $setting)
                                            
                                            <div class="row"><label class="col-md-3 form-label">{{ $setting->title }}</label>
                                                <div class="col-md-9">
                                                    <div class="input-group"><input
                                                            class="form-control" type="text"
                                                            placeholder="Enter min order amount" name="{{ $setting->key }}" value="{{ $setting->value }}"></div>
                                                    
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                    </div>
                                </div>
                            </div>
                        </div><button class="btn btn-primary ms-auto d-block">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection