<?php

namespace App\Policies;

use App\Models\Setlist;
use App\Models\User;

class SetlistPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Setlist $setlist): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Setlist $setlist): bool
    {
        return $user->isAdmin() || $setlist->created_by === $user->id;
    }

    public function delete(User $user, Setlist $setlist): bool
    {
        return $user->isAdmin() || $setlist->created_by === $user->id;
    }
}
