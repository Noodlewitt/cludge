<?php

namespace Cludge\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * Relationship to Roles
     *
     * @return EloquentCollection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
