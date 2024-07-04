<?php

namespace App\Policies;

use App\Models\Expenses;
use App\Models\User;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Expenses $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    public function create(User $user)
    {
        return $user->id !== null;
    }

    public function update(User $user, Expenses $expense)
    {
        return $user->id === $expense->user_id;
    }

    public function delete(User $user, Expenses $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    public function restore(User $user, Expenses $expense): bool
    {
        return false;
    }

    public function forceDelete(User $user, Expenses $expense): bool
    {
        return false;
    }
}
