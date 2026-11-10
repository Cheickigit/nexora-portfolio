<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class PortfolioOwnedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->portfolio !== null;
    }

    public function create(User $user): bool
    {
        return $user->portfolio !== null;
    }

    public function view(User $user, Model $model): bool
    {
        return $this->owns($user, $model);
    }

    public function update(User $user, Model $model): bool
    {
        return $this->owns($user, $model);
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->owns($user, $model);
    }

    protected function owns(User $user, Model $model): bool
    {
        return $user->portfolio !== null
            && $user->portfolio->id === $model->portfolio_id;
    }
}