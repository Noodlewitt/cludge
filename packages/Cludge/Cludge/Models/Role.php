<?php

namespace Cludge\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Relationship to Permissions
     *
     * @return EloquentCollection
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Give permission to a role
     *
     * @param  Permission $permission The permission
     *
     * @return Void
     */
    public function givePermissionTo(Permission $permission)
    {
        $this->permissions()->save($permission);
    }
}
