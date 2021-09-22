<?php

namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular'];

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


    /**
     * Filter the query by the most replies
     *
     * @return mixed
     */
    protected function popular()
    {
        // method one
        /*$this->builder->getQuery()->orders = [];*/
        /*return $this->builder->orderBy('replies_count', 'desc');*/

        // method two
        return $this->builder->reorder('replies_count', 'desc');

        // method 3 requires doing nothing and the tests work but the site dont work
        /*return $this->builder->orderBy('replies_count', 'desc');*/

        // method 4 requires changing the order of the ->latest() in the getThreads on ThreadController
    }
}
