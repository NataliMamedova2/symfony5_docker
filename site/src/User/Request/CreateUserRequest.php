<?php

declare(strict_types=1);

namespace App\User\Request;

use App\Request\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest implements RequestInterface
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
     * @Assert\Length(
     *      min = 10,
     *      max = 255
     * )
     */
    private string $password;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(
     *      min = 2,
     *      max = 45
     * )
     */
    private string $email;

    public function __construct(string $username, string $password, string $email)
    {
        $this->username = $username;
        $this->password = $password;
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

    public function getPassword(): string
    {
        return $this->password;
    }
}
