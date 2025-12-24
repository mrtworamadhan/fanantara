<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SavingType;
use Illuminate\Auth\Access\HandlesAuthorization;

class SavingTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SavingType');
    }

    public function view(AuthUser $authUser, SavingType $savingType): bool
    {
        return $authUser->can('View:SavingType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SavingType');
    }

    public function update(AuthUser $authUser, SavingType $savingType): bool
    {
        return $authUser->can('Update:SavingType');
    }

    public function delete(AuthUser $authUser, SavingType $savingType): bool
    {
        return $authUser->can('Delete:SavingType');
    }

    public function restore(AuthUser $authUser, SavingType $savingType): bool
    {
        return $authUser->can('Restore:SavingType');
    }

    public function forceDelete(AuthUser $authUser, SavingType $savingType): bool
    {
        return $authUser->can('ForceDelete:SavingType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SavingType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SavingType');
    }

    public function replicate(AuthUser $authUser, SavingType $savingType): bool
    {
        return $authUser->can('Replicate:SavingType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SavingType');
    }

}