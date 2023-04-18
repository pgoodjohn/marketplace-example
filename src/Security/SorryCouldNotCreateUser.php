<?php


namespace App\Security;


class SorryCouldNotCreateUser extends \RuntimeException
{

    public static function becauseUserWithEmailAlreadyExists($email): self
    {
        return new self("Could not create user with email address {$email} because a user with this email already exists in the database");
    }

}