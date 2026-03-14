<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Entry;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Entry');
    }

    public function view(AuthUser $authUser, Entry $entry): bool
    {
        return $authUser->can('View:Entry');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Entry');
    }

    public function update(AuthUser $authUser, Entry $entry): bool
    {
        return $authUser->can('Update:Entry');
    }

    public function delete(AuthUser $authUser, Entry $entry): bool
    {
        return $authUser->can('Delete:Entry');
    }

    public function restore(AuthUser $authUser, Entry $entry): bool
    {
        return $authUser->can('Restore:Entry');
    }

    public function forceDelete(AuthUser $authUser, Entry $entry): bool
    {
        return $authUser->can('ForceDelete:Entry');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Entry');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Entry');
    }

    public function replicate(AuthUser $authUser, Entry $entry): bool
    {
        return $authUser->can('Replicate:Entry');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Entry');
    }

}