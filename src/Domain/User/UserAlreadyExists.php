<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainException;

class UserAlreadyExists extends DomainException
{
    public $message = 'The username is already exists.';
}
