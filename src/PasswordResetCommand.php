<?php

namespace JoePriest\LaravelPasswordReset;

use Illuminate\Console\Command;

class PasswordResetCommand extends Command
{
    protected $signature = 'user:reset-password
        {--user= : The ID of the user}
        {--password= : The new password}
        {--R|random : Use a random password}
    ';

    protected $description = 'Reset the given user\'s password';

    protected string $userModel;

    protected string $searchField;

    protected string $passwordField;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->userModel = config('password-reset.user_model');
        $this->searchField = config('password-reset.search_field');
        $this->passwordField = config('password-reset.password_field');
    }

    public function handle()
    {
        $user = $this->fetchOrSearchForUser();
        $password = $this->selectPassword();

        $this->resetPasswordForUser($user, $password);
    }

    private function fetchOrSearchForUser()
    {
        if ($this->hasOption('user')) {
            return $this->tryToFetchUser();
        }

        return $this->searchForUser();
    }

    private function tryToFetchUser()
    {
        return $this->userModel::find($this->option('user')) ?? $this->fallbackToSearch();
    }

    private function fallbackToSearch()
    {
        $this->warn('User not found with the given ID.');

        return $this->searchForUser();
    }

    private function searchForUser()
    {
        $users = $this->userModel::select($this->searchField)
            ->get()
            ->pluck($this->searchField)
            ->all();

        $searchResult = $this->anticipate('Which user\'s password would you like to reset?', $users);

        return $this->userModel::where($this->searchField, $searchResult)->firstOrFail();
    }

    private function selectPassword()
    {
        if ($this->hasOption('password') && $this->option('password') !== null) {
            return $this->option('password');
        }

        $randomPassword = str()->random(14);

        if($this->option('random') === true){
            return $randomPassword;
        }

        return $this->ask("What would you like the new password to be?", $randomPassword);
    }

    public function resetPasswordForUser($user, $password)
    {
        $this->comment("Resetting password for {$user->{$this->searchField}}...");

        $user->{$this->passwordField} = bcrypt($password);

        $user->save();

        $this->info('Password reset successfully!');
        $this->comment("New password is: {$password}");
    }
}
