<?php

declare(strict_types=1);

namespace App\Domain\User;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

/**
 * @property int $id
 * @property string $username
 * @property string $firstName
 * @property string $lastName
 */
class User extends Model implements JsonSerializable
{
    protected $table = 'users';

    protected $fillable = ['username', 'firstName', 'lastName'];

    protected $guarded = ['id', 'password'];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
