<?php

namespace SeatingBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SeatingBundle\Entity\Reminder;

class ReminderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reminder::class);
    }

    public function findPendingBetween(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $qb = $this->createQueryBuilder('r');

        return $qb
            ->andWhere('r.status = :status')
            ->andWhere('r.scheduledAt BETWEEN :start AND :end')
            ->setParameter('status', 'pending')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    public function saveAll(array $reminders): void
    {
        $em = $this->getEntityManager();

        foreach ($reminders as $reminder) {
            $em->persist($reminder);
        }

        $em->flush();
    }

}
