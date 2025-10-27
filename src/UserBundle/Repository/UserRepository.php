<?php

namespace UserBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use UserBundle\Entity\User;

class UserRepository extends ServiceEntityRepository
{
    private string $a = User::ENTITY_ALIAS;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class); // Make sure this is correct
    }

    public function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder($this->a);
    }
}
