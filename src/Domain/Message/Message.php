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
 * @property mixed $created_at
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

    public static function fromArray($array): Message
    {
        $message = new Message();
        foreach ($array as $key => $value) {
            $message->{$key} = $value;
        }
        return $message;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'fromId' => $this->from_id,
            'toId' => $this->to_id,
            'createdAt' => $this->created_at,
        ];
    }
}
