<?php

declare(strict_types=1);

namespace App\User\Request;

use App\Request\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest implements RequestInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 60
     * )
     */
    private string $username;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(
     *      min = 2,
     *      max = 45
     * )
     */
    private string $email;

    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
