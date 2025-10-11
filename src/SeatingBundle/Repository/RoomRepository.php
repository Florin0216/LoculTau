<?php

namespace SeatingBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use SeatingBundle\Entity\Room;

class RoomRepository extends ServiceEntityRepository
{
    private string $a = Room::ENTITY_ALIAS;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder($this->a);
    }
}
