<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Command;
use App\Models\User;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

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
		$username = $this->ask('Username');

        $email = $this->ask('Email');

        $password = $this->secret('Password');

		// Validator
		$validator = $this->validate($username, $email, $password);

		// Handle errors
        if ($validator->fails()) {
			$this->error('Error');

			foreach ($validator->errors()->all() as $error) {
				$this->error($error);
			}

			return 1;
		}

        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

		$this->info("User {$username} created.");
		return 0;
    }

	protected function validate($username, $email, $password)
	{
		return $validator = Validator::make([
			'username' => $username,
			'email' => $email,
			'password' => $password,
		], [
            'username' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
	}
}
