<?php

namespace App\User\Repository;

use App\User\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserListDbBuilderByParams(string $username, string $email): QueryBuilder
    {
        $qb =  $this->createQueryBuilder('u');
        $qb->select(
                [
                     'u.id',
                     'u.email',
                     'u.username'
                ]
            )
            ->where($qb->expr()->orX(
                    $qb->expr()->like('u.email', ':email'),
                    $qb->expr()->like('u.username', ':username')
                )
            )
            ->setParameter('email', '%' . $email . '%')
            ->setParameter('username', '%' . $username . '%');

        return $qb;
    }
}
