<?php

namespace App\Policies;

use App\Models\Performance;
use App\Models\User;

class PerformancePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Performance $performance): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Performance $performance): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Performance $performance): bool
    {
        return $user->isAdmin();
    }
}
