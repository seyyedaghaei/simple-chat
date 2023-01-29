<?php

declare(strict_types=1);

namespace App\Application\Actions\Message;

use Psr\Http\Message\ResponseInterface as Response;

class SendMessageAction extends MessageAction
{
    protected array $params = ['to', 'message'];

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $user = $this->request->getAttribute("user");
        $body = $this->getFormData();
        $message = $this->messageRepository->createMessage($user->id, (int) $body['to'], $body['message']);
        return $this->respondWithData($message);
    }
}
