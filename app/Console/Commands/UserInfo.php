<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Command;
use App\Models\User;

class UserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:info {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the user info';

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

        // if there is no user, find one
        if (!$user) {
            while (!($user = $this->askUser())) {}
        }

        // return the user info
        $array = $user->toArray();

        $this->printArray($array);

        return Command::SUCCESS;
    }

    protected function printArray($array = [], $prefix = "")
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $this->info("{$key}");
                $prefix .= "\t";
                $this->printArray($value, $prefix);
                $prefix = "";
            } else {
                $this->info("{$prefix}{$key}: {$value}");
            }
        }
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
            if ($this->confirm("Do you mean {$user->username}?", true)) {
                return $user;
            }
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
