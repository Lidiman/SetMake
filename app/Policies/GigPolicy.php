<?php

namespace App\Policies;

use App\Models\Gig;
use App\Models\User;

class GigPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Gig $gig): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Gig $gig): bool
    {
        return $user->isAdmin() || $gig->created_by === $user->id;
    }

    public function delete(User $user, Gig $gig): bool
    {
        return $user->isAdmin() || $gig->created_by === $user->id;
    }
}
