<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('app:create-initial-user')]
#[Description('Create the initial admin user since registration is disabled.')]
class CreateInitialUserCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (User::count() > 0) {
            $this->error('A user already exists. Aborting.');

            return self::FAILURE;
        }

        $name = $this->ask('Name');
        $email = $this->ask('Email address');
        $password = $this->secret('Password');
        $passwordConfirmation = $this->secret('Confirm password');

        if ($password !== $passwordConfirmation) {
            $this->error('Passwords do not match. Aborting.');

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->info("Initial user created: {$user->email}");

        return self::SUCCESS;
    }
}
