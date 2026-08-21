<?php

use App\Console\Commands\CreateInitialUserCommand;
use App\Models\User;

it('creates the initial user when no users exist', function () {
    $this->artisan(CreateInitialUserCommand::class)
        ->expectsQuestion('Name', 'Admin')
        ->expectsQuestion('Email address', 'admin@example.com')
        ->expectsQuestion('Password', 'secret-password')
        ->expectsQuestion('Confirm password', 'secret-password')
        ->assertSuccessful()
        ->expectsOutputToContain('Initial user created: admin@example.com');
});

it('aborts when a user already exists', function () {
    User::factory()->create();

    $this->artisan(CreateInitialUserCommand::class)
        ->assertFailed()
        ->expectsOutput('A user already exists. Aborting.');
});

it('aborts when passwords do not match', function () {
    $this->artisan(CreateInitialUserCommand::class)
        ->expectsQuestion('Name', 'Admin')
        ->expectsQuestion('Email address', 'admin@example.com')
        ->expectsQuestion('Password', 'secret-password')
        ->expectsQuestion('Confirm password', 'different-password')
        ->assertFailed()
        ->expectsOutput('Passwords do not match. Aborting.');

    expect(User::count())->toBe(0);
});
