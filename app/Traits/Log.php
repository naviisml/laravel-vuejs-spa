<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Log as LogModel;
use App\Facades\IP;

trait Log
{
	/**
	 * Create a user specific log
	 *
	 * @param   string  $action
	 *
	 * @return  App\Models\Log
	 */
	public function log($action = null, $metadata = [], $target_id = null)
	{
		return LogModel::create([
			'user_id' => $this->id,
            'target_id' => $target_id ?? $this->id,
			'ip_address' => IP::getIpAdress(),
			'action' => $action,
			'metadata' => json_encode($metadata),
		]);
	}

    /**
     * Get the users' logs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(){
        return $this->hasMany(LogModel::class)->orWhere('target_id', $this->id);
    }
}
