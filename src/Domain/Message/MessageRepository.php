<?php

declare(strict_types=1);

namespace App\Domain\Message;

interface MessageRepository
{
    /**
     * @return Message[]
     */
    public function findMessageByUser(int $userId, int $chatId, int $limit = 10, int $after = 0): array;
}
