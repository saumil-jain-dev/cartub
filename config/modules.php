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
];
