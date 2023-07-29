<?php

namespace JoePriest\LaravelPasswordReset\Tests;

use JoePriest\LaravelPasswordReset\PasswordResetServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PasswordResetServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }
}
