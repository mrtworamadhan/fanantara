<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SavingTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class SavingTransactionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SavingTransaction');
    }

    public function view(AuthUser $authUser, SavingTransaction $savingTransaction): bool
    {
        return $authUser->can('View:SavingTransaction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SavingTransaction');
    }

    public function update(AuthUser $authUser, SavingTransaction $savingTransaction): bool
    {
        return $authUser->can('Update:SavingTransaction');
    }

    public function delete(AuthUser $authUser, SavingTransaction $savingTransaction): bool
    {
        return $authUser->can('Delete:SavingTransaction');
    }

    public function restore(AuthUser $authUser, SavingTransaction $savingTransaction): bool
    {
        return $authUser->can('Restore:SavingTransaction');
    }

    public function forceDelete(AuthUser $authUser, SavingTransaction $savingTransaction): bool
    {
        return $authUser->can('ForceDelete:SavingTransaction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SavingTransaction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SavingTransaction');
    }

    public function replicate(AuthUser $authUser, SavingTransaction $savingTransaction): bool
    {
        return $authUser->can('Replicate:SavingTransaction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SavingTransaction');
    }

}