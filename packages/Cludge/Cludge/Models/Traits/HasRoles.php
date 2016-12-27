<?php

namespace Cludge\Models\Traits;

use Cludge\Models\Role;
use Input;

trait HasRoles {

    /**
     * Add the event listener
     *
     * @return void
     */
    public static function bootHasRoles()
    {
        static::saved(function ($model) {
            $model->syncRoles($model);
        });
    }

    /**
     * Relationsip to roles
     *
     * @return EloquentCollection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign a role to the user
     *
     * @param string $role The role to add
     *
     * @return User
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * Check if the user has a role
     *
     * @param mixed $role The role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }


    /**
     * Relate the roles
     *
     * @param mixed $model The model
     *
     * @return aliases
     */
    public function syncRoles($model)
    {
        $roles = request()->get('roles');
        if (is_array($roles)) {
            $model->roles()->sync($roles);
        }
    }
}