<?php

declare(strict_types=1);

namespace App\CommandBus\Command\User;

use App\User\Request\CreateUserRequest;

class CreateUserCommand
{
    private CreateUserRequest $request;

    public function __construct(CreateUserRequest $request)
    {
        $this->request = $request;
    }

    public function getRequest(): CreateUserRequest
    {
        return $this->request;
    }
}
