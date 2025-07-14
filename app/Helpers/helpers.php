<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


/**
 * Check if logged-in user has given permission.
 * Super Admin always has all permissions.
 *
 * @param string $permission
 * @return bool
 */
function hasPermission(string $permission): bool
{
    $user = Auth::user();

    if (! $user) {
        return false;
    }

    // If user is super_admin, always true
    if ($user->hasRole('super_admin')) {
        return true;
    }

    return $user->can($permission);
}

