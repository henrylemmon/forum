<?php

namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

    /**
     * Filter the query by a given username
     *
     * @param string $username
     * @return mixed
     */
    protected function by(string $username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
