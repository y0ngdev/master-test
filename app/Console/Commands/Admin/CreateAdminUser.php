<?php

namespace App\Console\Commands\Admin;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'add:admin
                            {email? : The admin email}
                            {name? : The admin name}
                            {password? : The admin password}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates the admin user post-installation.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
//        if (User::where('role', 'admin')->exists()) {
//            $this->error('An admin already exists. Aborting.');
//            return Command::FAILURE;
//        }

        $email = $this->argument('email') ?? text(
            label: 'Enter admin email',
            validate: ['email' => 'required|email|unique:users']
        );

        $name = $this->argument('name') ?? text(
            label: 'Enter admin name',
            required: true
        );



        $password = $this->argument('password');

        if (!$password) {
            $password = password(
                label: 'Enter password',
                required: true,
                validate: ['password' => 'required|string|min:8']
            );

            $confirmPassword = password(
                label: 'Confirm password',
                required: true
            );

            $validator = Validator::make([
                'password' => $password,
                'password_confirmation' => $confirmPassword,
            ], [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return Command::FAILURE;
            }
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'username' => 'admin',
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        $this->info("Admin user '$user->email' created successfully.");
        return Command::SUCCESS;

    }


}
