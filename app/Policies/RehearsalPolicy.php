<?php

namespace App\Policies;

use App\Models\Rehearsal;
use App\Models\User;

class RehearsalPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Rehearsal $rehearsal): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Rehearsal $rehearsal): bool
    {
        return $user->isAdmin() || $rehearsal->created_by === $user->id;
    }

    public function delete(User $user, Rehearsal $rehearsal): bool
    {
        return $user->isAdmin() || $rehearsal->created_by === $user->id;
    }
}
