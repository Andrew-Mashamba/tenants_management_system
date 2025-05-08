<?php

namespace App\Policies;

use App\Models\PropertyDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyDocumentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, PropertyDocument $document)
    {
        return $user->hasRole('admin') ||
               $document->is_public ||
               $user->id === $document->uploaded_by ||
               $user->hasRole('property_manager') && $user->properties->contains($document->property_id);
    }

    public function create(User $user)
    {
        return $user->hasRole(['admin', 'property_manager']);
    }

    public function update(User $user, PropertyDocument $document)
    {
        return $user->hasRole('admin') ||
               $user->id === $document->uploaded_by ||
               $user->hasRole('property_manager') && $user->properties->contains($document->property_id);
    }

    public function delete(User $user, PropertyDocument $document)
    {
        return $user->hasRole('admin') ||
               $user->id === $document->uploaded_by ||
               $user->hasRole('property_manager') && $user->properties->contains($document->property_id);
    }
}
