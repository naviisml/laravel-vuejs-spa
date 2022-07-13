<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use Notifiable,
        HasFactory;

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
		$roles = $this->roles()->get()->toArray();

		usort($roles, function($a, $b) {
			return $a['order'] <=> $b['order'];
		});

		return $roles;
    }

    /**
     * Return the users' permissions
     *
     * @return UserRole
     */
	public function getPermissionsAttribute()
	{
		$roles = $this->getRolesAttribute();

		foreach ($roles as $role) {
			if (isset($role['data']) && is_array($role['data']['permissions']))
				$permissions = array_merge($permissions ?? [], array_filter($role['data']['permissions'], function($v, $k) {
                    return $v != "0";
                }, ARRAY_FILTER_USE_BOTH));
		}

		return $permissions ?? [];
	}

	/**
	 * Check if the user has a specific permission
	 *
	 * @param   string  $permission
	 *
	 * @return  boolean
	 */
	public function hasPermission($permission)
	{
		if (is_array($permission)) {
			$this->hasPermissions($permission);
		}

		if (!isset($this->permissions[$permission]) || $this->permissions[$permission] == false) {
			return false;
		}

		return true;
	}

	/**
	 * Loop through the array of permissions, and check if the user has all of them
	 *
	 * @param   array  $permissions
	 *
	 * @return  boolean
	 */
	public function hasPermissions($permissions)
	{
		if (is_array($permissions)) {
			foreach ($permissions as $permission)  {
				if ($this->hasPermission($permission) === false) {
					return false;
				}
			}

			return true;
		}

		return $this->hasPermission($permission);
	}

	/**
	 * Create a user specific log
	 *
	 * @param   string  $action
	 *
	 * @return  App\Models\User\Log
	 */
	public function log($action = null, $metadata = [], $target_id = null)
	{
		return Log::create([
			'user_id' => $this->id,
            'target_id' => $target_id ?? $this->id,
			'ip_address' => $this->getIp(),
			'action' => $action,
			'metadata' => json_encode($metadata),
		]);
	}

    /**
     * Get the users' roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function roles()
	{
		return $this->hasMany(UserRole::class, 'user_id', 'id');
	}

    /**
     * Get the users' logs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(){
        return $this->hasMany(Log::class)->orWhere('target_id', $this->id);
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

	/**
	 * Return the client's REAL ip
	 *
	 * @return  $ip_address
	 */
	protected function getIp()
	{
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}

		return request()->ip();
	}
}
