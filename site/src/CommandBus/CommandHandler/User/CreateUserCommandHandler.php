<?php

declare(strict_types=1);

namespace App\CommandBus\CommandHandler\User;

use App\CommandBus\Command\User\CreateUserCommand;
use App\Exception\UniqueConstraintException;
use App\User\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommandHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    public function __invoke(CreateUserCommand $command)
    {
        try {
            $request = $command->getRequest();
            $user = new User();
            $user->setEmail($request->getEmail());
            $user->setUsername($request->getUsername());
            $user->setPassword($this->passwordHasher->hashPassword($user, $request->getPassword()));

            $this->em->persist($user);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $message = 'Duplicate key (email or username)';

            throw new UniqueConstraintException($message);
        }
    }
}
