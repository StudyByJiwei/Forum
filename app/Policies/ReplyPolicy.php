<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return ! $user->lastReply->wasJustPublished();
    }

    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }
}
