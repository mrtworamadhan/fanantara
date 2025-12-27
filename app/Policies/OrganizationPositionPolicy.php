<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OrganizationPosition;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPositionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OrganizationPosition');
    }

    public function view(AuthUser $authUser, OrganizationPosition $organizationPosition): bool
    {
        return $authUser->can('View:OrganizationPosition');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OrganizationPosition');
    }

    public function update(AuthUser $authUser, OrganizationPosition $organizationPosition): bool
    {
        return $authUser->can('Update:OrganizationPosition');
    }

    public function delete(AuthUser $authUser, OrganizationPosition $organizationPosition): bool
    {
        return $authUser->can('Delete:OrganizationPosition');
    }

    public function restore(AuthUser $authUser, OrganizationPosition $organizationPosition): bool
    {
        return $authUser->can('Restore:OrganizationPosition');
    }

    public function forceDelete(AuthUser $authUser, OrganizationPosition $organizationPosition): bool
    {
        return $authUser->can('ForceDelete:OrganizationPosition');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OrganizationPosition');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OrganizationPosition');
    }

    public function replicate(AuthUser $authUser, OrganizationPosition $organizationPosition): bool
    {
        return $authUser->can('Replicate:OrganizationPosition');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OrganizationPosition');
    }

}