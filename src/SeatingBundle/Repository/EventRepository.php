<?php

namespace SeatingBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use SeatingBundle\Entity\Event;

class EventRepository extends ServiceEntityRepository
{
    private string $a = Event::ENTITY_ALIAS;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder($this->a);
    }
}
