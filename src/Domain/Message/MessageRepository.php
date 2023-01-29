<?php

declare(strict_types=1);

namespace App\Domain\Message;

interface MessageRepository
{
    /**
     * @return Message[]
     */
    public function findMessageByUser(int $userId, int $chatId, int $limit = 10, int $after = 0): array;

    /**
     * @param int $fromId
     * @param int $toId
     * @param string $message
     * @return Message
     */
    public function createMessage(int $fromId, int $toId, string $message): Message;
}
