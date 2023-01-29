<?php

declare(strict_types=1);

namespace App\Application\Actions\Message;

use Psr\Http\Message\ResponseInterface as Response;

class ListMessagesAction extends MessageAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $user = $this->request->getAttribute("user");

        $chatId = (int) $this->resolveArg('id');

        $query = $this->request->getQueryParams();

        $limit = 10;
        if (isset($query['limit']) && is_numeric($query['limit'])) {
            $limit = (int) $query['limit'];
        }
        $after = 0;
        if (isset($query['after']) && is_numeric($query['after'])) {
            $after = (int) $query['after'];
        }

        $messages = $this->messageRepository->findMessageByUser($user->id, $chatId, $limit, $after);

        return $this->respondWithData($messages);
    }
}
