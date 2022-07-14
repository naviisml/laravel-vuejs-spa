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
    protected $signature = 'user:verify {user_id?}';

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
        // check if the command has a user_id parameter
        if ($user_id = $this->argument('user_id')) {
		    $user = $this->searchUser($user_id);
        }

        if (!isset($user) || !$user) {
            while (!($user = $this->askUser())) {}
        }

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
     * Ask the 'Search for a user' question
     *
     * @return  App\Models\User
     */
	protected function askUser()
	{
        $input = $this->ask('Search user by email, username or id');

        if ($user = $this->searchUser($input)) {
            return $user;
        }
	}

    /**
     * Search for a user by email, username or id
     *
     * @return  App\Models\User
     */
	protected function searchUser($input = null)
	{
        $user = User::where('email', 'like', "%{$input}%")->orWhere('id', 'like', "%{$input}%")->orWhere('username', 'like', "%{$input}%")->first();

		if (!$user) {
            $this->error("Could not find any user by {$input}.");
            $this->newLine();

            return false;
        }

        if ($this->confirm("Do you mean {$user->username}?", true)) {
            return $user;
        }

		return false;
	}
}
