<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Traits\Role;
use App\Traits\Log;

class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use Role,
        Log,
        Notifiable,
        HasFactory;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
		'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

	/**
	 * The accessors to append to the model's array.
	 *
	 * @var array
	 */
	protected $appends = [
		'permissions',
		'accounts',
		'roles',
	];

    /**
     * return the oauth providers for the user
     *
     * @return \App\Models\OAuthProvider
     */
    public function getAccountsAttribute()
    {
        $accounts = $this->oauthProviders()->select(['provider', 'provider_user_id', 'provider_user_data', 'created_at', 'updated_at'])->get();
        $result = [];

        foreach ($accounts as $key => $value) {
            if ($value['provider']) {
                $result[$value['provider']] = $value;
            }
        }

		return $result;
    }

    /**
     * Return the users' roles
     *
     * @return UserRole
     */
    public function getRolesAttribute()
    {
		return $this->getRoles();
    }

    /**
     * Return the users' permissions
     *
     * @return UserRole
     */
	public function getPermissionsAttribute()
	{
		return $this->getPermissions();
	}

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(\App\Models\OAuthProvider::class);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
