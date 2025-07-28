<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper">
            <a href="/"><img class="img-fluid for-light" src="{{ asset('assets/images/logo.png') }}" alt="" /><img
                    class="img-fluid for-dark" src="{{ asset('assets/images/logo.png') }}" alt="" /></a>

            <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid">
                </i></div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="/"><img class="img-fluid" src="{{ asset('assets/images/logo.png') }}" alt="" /></a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <a href="/"><img class="img-fluid" src="{{ asset('assets/images/logo.png') }}" alt="" /></a>

                        <div class="mobile-back text-end"><span>Back</span><i class="fa-solid fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-1">General</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title" href="{{ route('dashboard.dashboard') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use>
                            </svg>
                            <span class="lan-3">Dashboard</span>
                        </a>
                        {{-- <ul class="sidebar-submenu">
                            <li><a href="{{ route('dashboard.dashboard') }}">Overview </a></li>
                            <li><a href="live-wash-status">Live Wash Status </a></li>
                            @if(hasPermission('dashboard.today-wash'))
                            <li><a href="{{ route('dashboard.today-wash') }}">Today's Bookings</a></li>@endif
                        </ul> --}}
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-8">User Management</h6>
                        </div>
                    </li>
                    @if(hasPermission('users.index'))
                        <li class="sidebar-list">
                            <i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="{{ route('users.index') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                                </svg>
                                <span>Users</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ route('users.index') }}">User List</a></li>

                            </ul>
                        </li>
                    @endif
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"> </i>
                        
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#project-users') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-project') }}"></use>
                            </svg>
                            <span> Cleaners  </span>
                        </a>
                        
                        <ul class="sidebar-submenu">
                            @if(hasPermission('cleaners.create'))
                            <li><a href="{{ route('cleaners.create') }}">Add Cleaners</a></li>
                            @endif
                            @if(hasPermission('cleaners.index'))
                            <li><a href="{{ route('cleaners.index') }}">Cleaners List</a></li>
                            @endif

                            @if(hasPermission('cleaners.performance-reports'))<li><a href="{{ route('cleaners.performance-reports') }}">Performance Reports</a></li>@endif
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('profile') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#crm-user') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                            </svg>
                            <span>Admin Users</span>
                        </a>
                    </li>
                    @if(hasPermission('roles-permission.index'))
                        <li class="sidebar-list">
                            <i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('roles-permission.*') ? 'active' : '' }}"
                                href="{{ route('roles-permission.index') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#crm-user') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                                </svg>
                                <span>Roles & Permission</span>
                            </a>
                        </li>
                    @endif
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Vehicle Management</h6>
                        </div>
                    </li>
                    @if(hasPermission('vehicle.index'))
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('vehicle.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-api') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                            </svg>
                            <span>Customer Vehicles list</span>
                        </a>
                    </li>
                    @endif
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="assets/svg/icon-sprite.svg#stroke-table"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-table') }}"></use>
                            </svg>
                            <span>Vehicle Service</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @if(hasPermission('vehicle.wash-type'))
                            <li>
                                <a href="{{ route('vehicle.wash-type') }}">
                                    Wash Type<span class="sub-arrow"><i class="fa-solid fa-angle-right"></i></span>
                                </a>
                            </li>
                            @endif
                            @if(hasPermission('vehicle.wash-packages'))
                            <li><a href="{{ route('vehicle.wash-packages') }}">Wash Packages</a></li>
                            @endif
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Booking Management</h6>
                        </div>
                    </li>
                    @if(hasPermission('bookings.index'))
                        <li class="sidebar-list">
                            <i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('bookings.index') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-api') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                                </svg>
                                <span>All Bookings</span>
                            </a>
                        </li>
                    @endif
                    @if(hasPermission('bookings.create'))
                        <li class="sidebar-list">
                            <i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('bookings.create') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-api') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                                </svg>
                                <span>Manual Booking</span>
                            </a>
                        </li>
                    @endif
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Coupons Management</h6>
                        </div>
                    </li>
                    @if(hasPermission('coupons.index'))
                        <li class="sidebar-list">
                            <i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('coupons.index') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-api') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                                </svg>
                                <span>All Coupons</span>
                            </a>
                        </li>
                    @endif
                    <!-- <li class="sidebar-main-title">
                        <div>
                            <h6>Customer Management</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="customer-list">
                            <svg class="stroke-icon">
                                <use href="assets/svg/icon-sprite.svg#stroke-api"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="assets/svg/icon-sprite.svg#fill-api"></use>
                            </svg>
                            <span>All Customers</span>
                        </a>
                    </li> -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Payments & Transactions</h6>
                        </div>
                    </li>
                    @if(hasPermission('payment.index'))
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('payment.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-knowledgebase') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-knowledgebase') }}"></use>
                            </svg>
                            <span>Payment History</span>
                        </a>
                    </li>
                    @endif
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Feedback & Support</h6>
                        </div>
                    </li>
                    @if(hasPermission('customer-feedback.index'))
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('customer-feedback.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-knowledgebase') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-knowledgebase') }}"></use>
                            </svg>
                            <span>Customer feedback</span>
                        </a>
                    </li>
                    @endif
                    <li class="sidebar-main-title">
                        <div>
                            <h6>App Management</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="settings">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-api') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                            </svg>
                            <span>App Setting</span>
                        </a>
                    </li>
                    
                    {{-- <li class="sidebar-main-title">
                        <div>
                            <h6>Booking Management</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="settings">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-api') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-api') }}"></use>
                            </svg>
                            <span>App Setting</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="faq">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-support-tickets') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-support-tickets') }}"></use>
                            </svg>
                            <span>FAQ</span>
                        </a>
                    </li> --}}
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>