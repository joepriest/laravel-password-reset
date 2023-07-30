# Password Reset
A simple artisan command to reset user passwords.

[![Latest Stable Version](https://poser.pugx.org/joepriest/laravel-password-reset/v/stable?format=flat-square)](https://packagist.org/packages/joepriest/laravel-password-reset)
[![Total Downloads](https://poser.pugx.org/joepriest/laravel-password-reset/downloads?format=flat-square)](https://packagist.org/packages/joepriest/laravel-password-reset)
[![License](https://poser.pugx.org/joepriest/laravel-password-reset/license?format=flat-square)](https://packagist.org/packages/joepriest/laravel-password-reset)


## Installation

Install the package via Composer:

```shell
composer require joepriest/laravel-password-reset
```

If you're using a version of Laravel prior to 5.5, add the service provider to `config/app.php`.

```php
'providers' => [
    // ...
    JoePriest\LaravelPasswordReset\PasswordResetServiceProvider::class,
];
```


## Usage

To reset a password, run `user:reset-password` from your console, with the following options:

- `--user=` : The ID of the user
- `--password=` : The new password
- `--R|random `: Use a random password

If no user ID is provided, you will be asked to choose a user (searching the `name` field by default, but this can be overridden).

If no new password is provided, and the `--random` flag is not set, you will be asked for one (a random one will be suggested).


## Configuration

The command uses default settings for the `user` model, and the `name` and `password` fields. If you wish to override these settings, publish the configuration file:

```shell
php artisan vendor:publish
```

Then alter the options in `config/password-reset.php`.
