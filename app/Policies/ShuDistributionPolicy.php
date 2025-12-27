<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ShuDistribution;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShuDistributionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ShuDistribution');
    }

    public function view(AuthUser $authUser, ShuDistribution $shuDistribution): bool
    {
        return $authUser->can('View:ShuDistribution');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ShuDistribution');
    }

    public function update(AuthUser $authUser, ShuDistribution $shuDistribution): bool
    {
        return $authUser->can('Update:ShuDistribution');
    }

    public function delete(AuthUser $authUser, ShuDistribution $shuDistribution): bool
    {
        return $authUser->can('Delete:ShuDistribution');
    }

    public function restore(AuthUser $authUser, ShuDistribution $shuDistribution): bool
    {
        return $authUser->can('Restore:ShuDistribution');
    }

    public function forceDelete(AuthUser $authUser, ShuDistribution $shuDistribution): bool
    {
        return $authUser->can('ForceDelete:ShuDistribution');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ShuDistribution');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ShuDistribution');
    }

    public function replicate(AuthUser $authUser, ShuDistribution $shuDistribution): bool
    {
        return $authUser->can('Replicate:ShuDistribution');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ShuDistribution');
    }

}