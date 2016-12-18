<?php

namespace Cludge\Repositories;

class UserRepository extends BasicRepository
{
    /**
     * Any optional fields that need to be filtered
     *
     * @var array
     */
    protected $optional = ['password'];

    /**
     * Stub for scopes method
     *
     * @param Eloquent\Query &$query The query
     *
     * @return void
     */
    protected function scopes(&$query)
    {
        $user = auth()->user();

        if ( ! $user->can('edit_all_users')) {
            $query->userAccount($query);
        }
    }
}