<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OrganizationStructure;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationStructurePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OrganizationStructure');
    }

    public function view(AuthUser $authUser, OrganizationStructure $organizationStructure): bool
    {
        return $authUser->can('View:OrganizationStructure');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OrganizationStructure');
    }

    public function update(AuthUser $authUser, OrganizationStructure $organizationStructure): bool
    {
        return $authUser->can('Update:OrganizationStructure');
    }

    public function delete(AuthUser $authUser, OrganizationStructure $organizationStructure): bool
    {
        return $authUser->can('Delete:OrganizationStructure');
    }

    public function restore(AuthUser $authUser, OrganizationStructure $organizationStructure): bool
    {
        return $authUser->can('Restore:OrganizationStructure');
    }

    public function forceDelete(AuthUser $authUser, OrganizationStructure $organizationStructure): bool
    {
        return $authUser->can('ForceDelete:OrganizationStructure');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OrganizationStructure');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OrganizationStructure');
    }

    public function replicate(AuthUser $authUser, OrganizationStructure $organizationStructure): bool
    {
        return $authUser->can('Replicate:OrganizationStructure');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OrganizationStructure');
    }

}