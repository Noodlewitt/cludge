<?php

namespace Cludge\Models;

use Cludge\Models\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Exceptions\RoleNotFoundException;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasRoles, HasRelatedOrganisations, HasSocial;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'selected_organisation'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Eager load the organisations with the user
     *
     * @var array
     */
    protected $with = ['organisations'];


    /**
     * Relationship to Organisation
     *
     * @return Eloquent\Collection
     */
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class);
    }

    /**
     * Give role to a user
     *
     * @param string $name The name of the role
     *
     * @return Void
     */
    public function giveRoleTo($name)
    {
        $role = Role::where('name', '=', $name)->first();

        if ($role) {
            $this->roles()->save($role);
        } else {
            throw new RoleNotFoundException("Role {$name} does not exist.");
        }
    }

    /**
     * Scope to find user account
     *
     * @param Elloquent\Query $query The query
     *
     * @return Elloquent\Query
     */
    public function scopeUserAccount($query)
    {
        $user = auth()->user();

        $query->where('users.id', '=', $user->id);

        return $query;
    }
}
