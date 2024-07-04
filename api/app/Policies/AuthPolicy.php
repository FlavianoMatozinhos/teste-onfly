<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class AuthPolicy
{
    use HandlesAuthorization;

    public function logout(User $user)
    {
        return $user->id !== null;
    }
}
