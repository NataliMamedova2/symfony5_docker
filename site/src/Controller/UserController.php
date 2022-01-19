<?php

declare(strict_types=1);

namespace App\Controller;

use App\CommandBus\Command\User\CreateUserCommand;
use App\CommandBus\Command\User\UpdateUserCommand;
use App\CommandBus\CommandHandler\User\CreateUserCommandHandler;
use App\CommandBus\CommandHandler\User\UpdateUserCommandHandler;
use App\Exception\UniqueConstraintException;
use App\User\Entity\User;
use App\User\Request\CreateUserRequest;
use App\User\Request\UpdateUserRequest;
use App\User\Request\UserFilterRequest;
use App\User\UseCase\GetUserListHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{
    private const PAGE_LIMIT = 10;
    private const PAGE_COUNT = 1;

    /**
     * @Route("/users", methods={"POST"})
     */
    public function addUser(CreateUserRequest $request, CreateUserCommandHandler $handler): JsonResponse
    {
        try {
            $command = new CreateUserCommand($request);
            $handler($command);
        } catch (UniqueConstraintException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @ParamConverter("user")
     *
     * @Route("/users/{id}", methods={"POST"})
     */
    public function updateUser(User $user, UpdateUserRequest $request, UpdateUserCommandHandler $handler): JsonResponse
    {
        try {
           $command = new UpdateUserCommand($request, $user);
           $handler($command);
        } catch (UniqueConstraintException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/users", methods={"GET"})
     * @param UserFilterRequest $request
     * @param GetUserListHandler $handler
     * @return JsonResponse
     */
    public function getUserList(UserFilterRequest $request, GetUserListHandler $handler): JsonResponse
    {
        $result = $handler->handle($request,self::PAGE_COUNT,self::PAGE_LIMIT);

        return $this->json($result, Response::HTTP_OK);
    }
}
