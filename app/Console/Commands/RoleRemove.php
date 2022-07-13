<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Role;

class RoleRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a role from a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$user = $this->askUser();

		$roles = $this->getRoles($user);

        if (!$roles) {
			$this->error('User doesn\'t own any roles');
			$this->newLine();

            // loop back to the user
		    $user = $this->askUser();
        }

        // select the role to remove
		$role = $this->choice('Choose the role', $roles);

		// Assign role
		if ($this->confirm("Do you want to remove [{$role}] from [{$user->username}]?", true)) {
            $role = UserRole::where('role', $role)->first();

            if (!$role) {
			    $this->error("User doesn't own {$role}");
            }

            $role->delete();
		}

        return Command::SUCCESS;
    }

	/**
	 * Search for a user by email or id
	 *
	 * @return  App\Model\User
	 */
	protected function askUser()
	{
		while (!isset($user)) {
			$input = $this->ask('Search user by email, username or id');
			$user = User::where('email', 'like', "%{$input}%")->orWhere('id', 'like', "%{$input}%")->orWhere('username', 'like', "%{$input}%")->first();
			if ($user) {
				if ($this->confirm("Do you mean {$user->username}?", true)) {
					break;
				} else {
					$user = null;
				}
			} else {
				$this->error('Could not find any user.');
				$this->newLine();
			}
		}

		return $user;
	}

	/**
	 * Parse the roles correctly
	 *
	 * @return  array
	 */
	protected function getRoles($user)
	{
		$roles = $user->roles()->get();
		$role_list = [];

        if (!is_array($roles)) {
            foreach ($roles as $role) {
                $role_list[] = $role->role;
            }
        }

		return $role_list;
	}
}
