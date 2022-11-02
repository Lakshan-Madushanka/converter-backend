<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $authUser, User $currentUSer)
    {
        return $authUser->id === $currentUSer->id ?
            $this->allow() :
            $this->denyAsNotFound();
    }
}
