<?php

namespace App\Policies;

use App\Models\SmartLock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmartLockPolicy
{
    use HandlesAuthorization;

    public function view(User $user, SmartLock $lock)
    {
        return $user->can('view', $lock->property);
    }

    public function create(User $user, SmartLock $lock)
    {
        return $user->can('manageAccess', $lock->property);
    }

    public function update(User $user, SmartLock $lock)
    {
        return $user->can('manageAccess', $lock->property);
    }

    public function delete(User $user, SmartLock $lock)
    {
        return $user->can('manageAccess', $lock->property);
    }

    public function control(User $user, SmartLock $lock)
    {
        return $user->can('view', $lock->property) && 
               ($user->hasRole(['admin', 'landlord']) || 
                $lock->authorizedUsers()->where('user_id', $user->id)->exists());
    }

    public function viewLogs(User $user, SmartLock $lock)
    {
        return $user->can('manageAccess', $lock->property);
    }
} 