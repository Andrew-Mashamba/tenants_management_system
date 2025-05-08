<?php

namespace App\Policies;

use App\Models\AccountingIntegration;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountingIntegrationPolicy
{
    use HandlesAuthorization;

    public function view(User $user, AccountingIntegration $integration)
    {
        return $user->can('view', $integration->property);
    }

    public function create(User $user, AccountingIntegration $integration)
    {
        return $user->can('manageAccounting', $integration->property);
    }

    public function update(User $user, AccountingIntegration $integration)
    {
        return $user->can('manageAccounting', $integration->property);
    }

    public function delete(User $user, AccountingIntegration $integration)
    {
        return $user->can('manageAccounting', $integration->property);
    }

    public function sync(User $user, AccountingIntegration $integration)
    {
        return $user->can('manageAccounting', $integration->property);
    }

    public function manageMappings(User $user, AccountingIntegration $integration)
    {
        return $user->can('manageAccounting', $integration->property);
    }
} 