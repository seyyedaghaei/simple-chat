<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ChatsAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $user = $this->request->getAttribute("user");

        $chats = $this->userRepository->chats($user);

        return $this->respondWithData($chats);
    }
}
