<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'gender', 'biography',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function social_profiles()
    {
      return $this->hasMany('App\SocialProfile');
    }

    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }

    public function rooms()
    {
      return $this->belongsToMany(Room::class);
    }

    /**
    * Check User in required role
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
        if (is_array($roles))
        {
          return $this->hasAnyRole($roles);
        }

        return $this->hasRole($roles);
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
    * Check single role
    * @param string $roles
    */
    public function hasRole($roles)
    {
        return null !== $this->roles()->where('name', $roles)->first();
    }

    /**
    * Get all admin users
    */
    public function scopeAdmins($query)
    {
      return $query->join('role_user', 'users.id', '=', 'role_user.user_id')
                   ->where('role_user.role_id', 1 );
    }
}
