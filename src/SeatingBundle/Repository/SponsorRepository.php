<?php

namespace SeatingBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use SeatingBundle\Entity\Sponsor;

class SponsorRepository extends ServiceEntityRepository
{
    private string $a = Sponsor::ENTITY_ALIAS;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponsor::class);
    }

    public function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder($this->a);
    }

}
