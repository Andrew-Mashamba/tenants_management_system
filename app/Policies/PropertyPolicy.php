<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Property $property)
    {
        return $user->hasRole(['admin', 'landlord']) || 
               $property->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user)
    {
        return $user->hasRole(['admin', 'landlord']);
    }

    public function update(User $user, Property $property)
    {
        return $user->hasRole(['admin', 'landlord']) || 
               $property->users()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }

    public function delete(User $user, Property $property)
    {
        return $user->hasRole(['admin', 'landlord']);
    }

    public function manageDocuments(User $user, Property $property)
    {
        return $this->update($user, $property);
    }

    public function manageAccounting(User $user, Property $property)
    {
        return $this->update($user, $property);
    }

    public function manageAccess(User $user, Property $property)
    {
        return $this->update($user, $property);
    }
} 