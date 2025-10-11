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
}
