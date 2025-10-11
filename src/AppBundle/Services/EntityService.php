<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityService
{
    public function __construct(
        protected EntityManagerInterface $em,
    )
    {
    }

    public function findOrReject(string $entityClass, $id): object
    {
        $entity = $this->em->getRepository($entityClass)->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found');
        }

        return $entity;
    }

    public function save($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete($entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function move($repoClass, $entity, $position): void
    {
        $maxPosition = $this->em->getRepository($repoClass)->findBy([], ['position' => 'desc'])[0]->getPosition();

        if (!(0 <= $position && $position <= $maxPosition)) {
            throw new BadRequestException('Invalid position provided');
        }

        $entity->setPosition($position);
    }
}
