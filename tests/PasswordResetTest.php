<?php

namespace JoePriest\LaravelPasswordReset\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        Model::unguard();

        $this->user = User::create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'original-password',
        ]);

        Model::reguard();

        config(['password-reset.user_model' => User::class]);
    }

    /** @test */
    public function it_resets_the_password_of_a_given_user(): void
    {
        $this->artisan('user:reset-password --user 1 --password=new-password')
            ->expectsOutput('Password reset successfully!')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
    }

    /** @test */
    public function if_no_user_is_provided_the_list_of_users_can_be_searched(): void
    {
        $this->artisan('user:reset-password --password=new-password')
            ->expectsQuestion('Which user\'s password would you like to reset?', 'Test User')
            ->expectsOutput('Password reset successfully!')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
    }

    /** @test */
    public function if_no_password_is_provided_a_random_one_is_suggested(): void
    {
        $this->artisan('user:reset-password --user=1')
            ->expectsQuestion('What would you like the new password to be?', 'new-password')
            ->expectsOutput('Password reset successfully!')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
    }

    /** @test */
    public function if_the_random_flag_is_passed_a_random_password_is_used(): void
    {
        $this->artisan('user:reset-password --user=1 --random')
            ->expectsOutput('Password reset successfully!')
            ->assertSuccessful();

        $this->assertFalse(Hash::check('original-password', $this->user->fresh()->password));
    }
}
