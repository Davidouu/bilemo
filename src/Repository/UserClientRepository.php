<?php

namespace App\Repository;

use App\Entity\UserClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserClient>
 */
class UserClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserClient::class);
    }

    public function paginateUserClients(int $page, int $limit, $user): array
    {
        $qb = $this->createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->where('c.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }
}
