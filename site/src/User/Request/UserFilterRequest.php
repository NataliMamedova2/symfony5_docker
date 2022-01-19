<?php

declare(strict_types=1);

namespace App\User\Request;

use App\Request\RequestInterface;

class UserFilterRequest implements RequestInterface
{
    private string|null $username;

    private string|null $email;

    public function __construct(?string $username = null, ?string $email = null)
    {
        $this->username = $username;
        $this->email = $email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
