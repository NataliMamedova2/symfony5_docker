<?php

declare(strict_types=1);

namespace App\User\UseCase;

use App\User\Repository\UserRepository;
use App\User\Request\UserFilterRequest;
use JetBrains\PhpStorm\ArrayShape;
use Knp\Component\Pager\PaginatorInterface;

class GetUserListHandler
{
    private UserRepository $repository;
    private PaginatorInterface $paginator;

    public function __construct(UserRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    #[ArrayShape(['users' => "iterable", 'totalItemCount' => "int", 'pageCount' => "mixed"])]
    public function handle(UserFilterRequest $userFilterRequest, int $page, int $offset): array
    {
        $username = $userFilterRequest->getUsername() ?? '';
        $email = $userFilterRequest->getEmail() ?? '';

        $qb = $this->repository->getUserListDbBuilderByParams($username, $email);
        $result = $this->paginator->paginate($qb, $page, $offset);
        $items = $result->getItems();

        return [
            'users' => $items,
            'totalItemCount' => $result->getTotalItemCount(),
            'pageCount' => $result->getPageCount()
        ];
    }
}
