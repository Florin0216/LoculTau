<?php

namespace SeatingBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Reservation;
use SeatingBundle\Entity\Seat;

class ReservationRepository extends ServiceEntityRepository
{
    private string $a = Reservation::ENTITY_ALIAS;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder($this->a);
    }

    public function findByEventAndSeat(Event $event, Seat $seat): ?Reservation
    {
        return $this->createQueryBuilder($this->a)
            ->andWhere($this->a.'.event = :event')    // filter by event
            ->andWhere($this->a.'.seat = :seat')           // filter by seat
            ->setParameter('event', $event)
            ->setParameter('seat', $seat)
            ->getQuery()
            ->getOneOrNullResult();           // or getResult() for multiple
    }

    public function addWhereKeyword(QueryBuilder $qb, string $keyword): QueryBuilder
    {
        $keyword = $keyword . '%';

        $qb
            ->innerJoin($this->a.'.event', Event::ENTITY_ALIAS)
            ->innerJoin($this->a.'.seat', Seat::ENTITY_ALIAS)
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like($this->a.'.email', ':keyword'),
                $qb->expr()->like($this->a.'.name', ':keyword'),
                $qb->expr()->like($this->a.'.uuid', ':keyword'),
                $qb->expr()->like(Event::ENTITY_ALIAS.'.title', ':keyword'),
                $qb->expr()->like(Seat::ENTITY_ALIAS.'.section', ':keyword'),
            ))
            ->setParameter('keyword', $keyword);

        return $qb;
    }
}
