<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Command;
use App\Models\User;

class UserVerify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user instance';

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

		// confirmation
		if ($this->confirm('Do you want to assign [' . $role . '] to [' . $user->firstname . ']?', true)) {
			$user->markEmailAsVerified();

            // Log the action
            $user->log("user.verified", [
                "admin_id" => "console",
            ]);

			$this->info("User {$username} validated.");
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
			$input = $this->ask('Search user by email, or id');
			$user = User::where('email', 'like', "%{$input}%")->orWhere('id', 'like', "%{$input}%")->first();
			if ($user) {
				if ($this->confirm('Do you mean ' . $user->firstname . '?', true)) {
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
}
