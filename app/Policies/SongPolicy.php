<?php

namespace App\Policies;

use App\Models\Song;
use App\Models\User;

class SongPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Song $song): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Song $song): bool
    {
        return $user->isAdmin() || $song->created_by === $user->id;
    }

    public function delete(User $user, Song $song): bool
    {
        return $user->isAdmin() || $song->created_by === $user->id;
    }
}
