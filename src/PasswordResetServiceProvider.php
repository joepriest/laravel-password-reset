<?php

namespace JoePriest\LaravelPasswordReset;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use JoePriest\LaravelPasswordReset\PasswordResetCommand;

class PasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        AboutCommand::add('JoePriest/PasswordReset', [
            'Version' => '1.0.0'
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/password-reset.php', 'password-reset');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PasswordResetCommand::class,
            ]);
        }
    }
}
