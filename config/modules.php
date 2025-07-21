<?php
return [
    'roles-permission' => [
        'name' => 'Role & Permission Management',
        'actions' => [ 'index', 'create', 'edit', 'destroy']
    ],
    'bookings' => [
        'name' => 'Bookings',
        'actions' => [ 'index', 'show', 'destroy', 'assign-cleaner']
    ],
    'dashboard' => [
        'name' => 'Dashboard',
        'actions' => [ 'dashboard', 'live-wash-status', 'today-wash']
    ],
    'users' => [
        'name' => 'Users',
        'actions' => [ 'index', 'edit', 'destroy']
    ],
    'cleaners' => [
        'name' => 'Cleaners',
        'actions' => [ 'index','create', 'edit', 'destroy','performance-reports']
    ],
    'vehicle' => [
        'name'=> 'Vehicle',
        'actions' => ['index','wash-type','wash-type-create','wash-types-edit','wash-types-destroy','wash-packages','wash-packages-create','wash-packages-edit','wash-packages-destroy']
    ],
];
