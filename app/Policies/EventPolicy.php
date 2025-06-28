<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{

    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }
}