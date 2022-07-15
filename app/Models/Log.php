<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\IP;

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
		'target_id',
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
		return IP::filterIpAdress($this->ip_address);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'id', 'target_id');
    }
}
