<?php

declare(strict_types=1);

namespace Tests\Domain\Message;

use App\Domain\Message\Message;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            [1, 2, 1, 'Hi'],
            [2, 3, 4, 'Hey'],
            [3, 7, 2, 'Hello'],
            [4, 3, 24, 'Bye'],
            [5, 1, 9, 'Bye'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param int $id
     * @param int $fromId
     * @param int $toId
     * @param string $text
     */
    public function testGetters(int $id, int $fromId, int $toId, string $text)
    {
        $message = Message::fromArray([
            'id' => $id,
            'from_id' => $fromId,
            'to_id' => $toId,
            'message' => $text,
        ]);

        $this->assertEquals($id, $message->getId());
        $this->assertEquals($text, $message->getMessage());
    }

    /**
     * @dataProvider userProvider
     * @param int $id
     * @param int $fromId
     * @param int $toId
     * @param string $text
     */
    public function testJsonSerialize(int $id, int $fromId, int $toId, string $text)
    {
        $message = Message::fromArray([
            'id' => $id,
            'from_id' => $fromId,
            'to_id' => $toId,
            'message' => $text,
        ]);

        $expectedPayload = json_encode([
            'id' => $id,
            'message' => $text,
            'fromId' => $fromId,
            'toId' => $toId,
            'createdAt' => null,
        ]);

        $this->assertEquals($expectedPayload, json_encode($message));
    }
}
