<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Role;

class RoleAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user';

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

		$roles = $this->getRoles();

		$role = $this->choice('Choose the role', $roles);

		if ($user->roles()->where('role', $role)->first()) {
			$this->error('User already owns [' . $role . ']');
			$this->newLine();
		}

		// Assign role
		if ($this->confirm('Do you want to assign [' . $role . '] to [' . $user->username . ']?', true)) {
			UserRole::create([
				'user_id' => $user->id,
				'role' => $role
			]);
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
				if ($this->confirm('Do you mean ' . $user->username . '?', true)) {
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
	protected function getRoles()
	{
		$roles = Role::get();
		$role_list = [];

		foreach ($roles as $role) {
			$role_list[] = $role->tag;
		}

		return $role_list;
	}
}
