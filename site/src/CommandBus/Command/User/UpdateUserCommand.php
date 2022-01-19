<?php

declare(strict_types=1);

namespace App\CommandBus\Command\User;

use App\User\Entity\User;
use App\User\Request\UpdateUserRequest;

class UpdateUserCommand
{
    private UpdateUserRequest $request;

    private User $user;

    public function __construct(UpdateUserRequest $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function getRequest(): UpdateUserRequest
    {
        return $this->request;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
