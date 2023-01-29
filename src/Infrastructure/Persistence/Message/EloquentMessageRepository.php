<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Message;

use App\Domain\Message\Message;
use App\Domain\Message\MessageRepository;

class EloquentMessageRepository implements MessageRepository
{
    /**
     * {@inheritdoc}
     */
    public function findMessageByUser(int $userId, int $chatId, int $limit = 10, int $after = 0): array
    {
        return Message::query()
            ->where('id', '>', $after)
            ->where(function ($query) use ($userId, $chatId) {
                $query
                    ->where(function ($query) use ($userId, $chatId) {
                        $query
                            ->where('to_id', '=', $userId)
                            ->where('from_id', '=', $chatId);
                    })
                    ->orWhere(function ($query) use ($userId, $chatId) {
                        $query
                            ->where('to_id', '=', $chatId)
                            ->where('from_id', '=', $userId);
                    });
            })
            ->limit($limit)
            ->get()->sortBy('created_at')
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage(int $fromId, int $toId, string $message): Message {
        return Message::query()->create([
            'from_id' => $fromId,
            'to_id' => $toId,
            'message' => $message,
        ]);
    }
}
