<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
		'id',
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id',
		'ip_address',
        'metadata',
		'action',
    ];

	/**
	 * The accessors to append to the model's array.
	 *
	 * @var array
	 */
	protected $appends = [
		'ip_filtered',
	];

    /**
     * The timestamp attributes.
     *
     * @var array
     */
	public $timestamps = false;

	/**
	 * Filter a IP address
	 *
	 * @return  string
	 */
	public function getIpFilteredAttribute()
	{
		$ip_array = explode('.', $this->ip_address);
		$new_ip = [];
		$i = 0;

		foreach($ip_array as $part) {
			if($part != $ip_array[0] && $part != end($ip_array)) {
				$new_ip[$i++] = str_repeat("*", strlen($part));
			} else {
				$new_ip[$i++] = $part;
			}
		}

		return implode('.', $new_ip);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
