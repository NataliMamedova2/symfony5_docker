<?php

declare(strict_types=1);

namespace App\CommandBus\CommandHandler\User;

use App\CommandBus\Command\User\UpdateUserCommand;
use App\Exception\UniqueConstraintException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateUserCommandHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(UpdateUserCommand $command)
    {
        try {
            $request = $command->getRequest();
            $user = $command->getUser();
            $user->setEmail($request->getEmail());
            $user->setUsername($request->getUsername());

            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $message = 'Duplicate key (email or username)';

            throw new UniqueConstraintException($message);
        }
    }
}
