<?php

namespace JoePriest\LaravelPasswordReset\Tests;

class User
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected string $email,
        protected string $password,
    ) {}
}
