<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ShuAllocation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShuAllocationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ShuAllocation');
    }

    public function view(AuthUser $authUser, ShuAllocation $shuAllocation): bool
    {
        return $authUser->can('View:ShuAllocation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ShuAllocation');
    }

    public function update(AuthUser $authUser, ShuAllocation $shuAllocation): bool
    {
        return $authUser->can('Update:ShuAllocation');
    }

    public function delete(AuthUser $authUser, ShuAllocation $shuAllocation): bool
    {
        return $authUser->can('Delete:ShuAllocation');
    }

    public function restore(AuthUser $authUser, ShuAllocation $shuAllocation): bool
    {
        return $authUser->can('Restore:ShuAllocation');
    }

    public function forceDelete(AuthUser $authUser, ShuAllocation $shuAllocation): bool
    {
        return $authUser->can('ForceDelete:ShuAllocation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ShuAllocation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ShuAllocation');
    }

    public function replicate(AuthUser $authUser, ShuAllocation $shuAllocation): bool
    {
        return $authUser->can('Replicate:ShuAllocation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ShuAllocation');
    }

}