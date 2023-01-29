<?php

declare(strict_types=1);

namespace App\Domain\User;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

/**
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 */
class User extends Model implements JsonSerializable
{
    protected $table = 'users';

    protected $fillable = ['username', 'first_name', 'last_name', 'password'];

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
        ];
    }
}
