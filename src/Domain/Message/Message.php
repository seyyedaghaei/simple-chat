<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JsonSerializable;

/**
 * @property int $id
 * @property string $message
 * @property int $from_id
 * @property int $to_id
 */
class Message extends Model implements JsonSerializable
{
    protected $table = 'messages';

    protected $fillable = ['message', 'to_id', 'from_id'];

    public function to(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function from(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'toId' => $this->from_id,
            'fromId' => $this->to_id,
        ];
    }
}
